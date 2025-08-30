<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Client;
use App\Invoice\Invoice;
use DB;
use Auth;
use App\ExchangeRate\ExchangeRate;
use DateTime;
use Illuminate\Support\Facades\Hash;

use App\ChartOfAccount;

use App\Jobs\ClientBalances;
use App\Excel\ClientsTable as ClientsTable;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Facades\Excel;


class ClientController extends Controller
{

    public function update_clients_balance()
    {
         \App\ClientBalanceSchedule::truncate();
         $sch = new \App\ClientBalanceSchedule;
         $sch->date = now();
         $sch->save();
         
        $all_clients = Client::get();
        foreach($all_clients as $client){
            $invoices = Invoice::where('client_id','=',$client->id)->sum('total');
            $payments = \App\ClientPayment\ClientPayment::where('client_id','=',$client->id)->sum('amount_received');
            $credit = \App\CreditNote\CreditNote::where('client_id','=',$client->id)->sum('amount_received');
            $debit = \App\DebitNote\DebitNote::where('client_id','=',$client->id)->sum('amount_received');
            $advance = \App\AdvancePayment\AdvancePayment::where('client_id','=',$client->id)->sum('amount_received');

            $balance = $invoices + $debit - $payments - $credit - $advance;

            Client::where('id', $client->id)->update(['balance' => $balance]);

        }
    }
    public function index()
    {
        //  $all_clients = Client::get();
        // foreach($all_clients as $client){
        //     $invoices = Invoice::where('client_id','=',$client->id)->sum('total');
        //     $payments = \App\ClientPayment\ClientPayment::where('client_id','=',$client->id)->sum('amount_received');
        //     $credit = \App\CreditNote\CreditNote::where('client_id','=',$client->id)->sum('amount_received');
        //     $debit = \App\DebitNote\DebitNote::where('client_id','=',$client->id)->sum('amount_received');
        //     $advance = \App\AdvancePayment\AdvancePayment::where('client_id','=',$client->id)->sum('amount_received');

        //     $balance = $invoices + $debit - $payments - $credit - $advance;

        //     Client::where('id', $client->id)->update(['balance' => $balance]);

        // }
        
        // dispatch(new ClientBalances());

        $user = auth()->user();
        if ($user->is_clients_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Client::orderby('created_at','desc')->search()
            ]);
        }
    }

    public function city()
    {
        $citys_client = Client::get();
        $city_Array = [];
        foreach($citys_client as $city){
            $city_Array[] = ["name" => $city->city];
        }
        return ($city_Array);
    }

    public function state()
    {
        $states_client = Client::get();
        $state_Array = [];
        foreach($states_client as $state){
            $state_Array[] = ["name" => $state->state];
        }
        return ($state_Array);
    }

    public function zip()
    {
        $zips_client = Client::get();
        $zip_Array = [];
        foreach($zips_client as $zip){
            $zip_Array[] = ["name" => $zip->zipcode];
        }
        return ($zip_Array);
    }

    public function client_dropdown1()
    {
        $client_dropdown1 = \App\ClientDropDown1::orderBy('id')
            ->get(['id', 'name']);

        $client_dropdown1_Array = [];
        foreach($client_dropdown1 as $client_dropdown1){
            $client_dropdown1_Array[] = ["name" => $client_dropdown1->name];
        }
        return ($client_dropdown1_Array);
    }

    public function client_dropdown2()
    {
        $client_dropdown2 = \App\ClientDropDown2::orderBy('id')
            ->get(['id', 'name']);

        $client_dropdown2_Array = [];
        foreach($client_dropdown2 as $client_dropdown2){
            $client_dropdown2_Array[] = ["name" => $client_dropdown2->name];
        }
        return ($client_dropdown2_Array);
    }
    
    public function client_seller()
    {
        $client_seller = \App\Sellers\Sellers::orderBy('id')
            ->get(['id', 'name']);

        $client_seller_Array = [];
        foreach($client_seller as $client_seller){
            $client_seller_Array[] = ["name" => $client_seller->name];
        }
        return ($client_seller_Array);
    }
    

    
    public function filter(Request $request)
    {
      
     
      $client_dropdown1 = $request->client_dropdown1;
     $client_dropdown2 = $request->client_dropdown2;
     $client_seller = $request->client_seller;

   
        $capitalizeName = (strtolower($request->client_name));
        // Start building the query
        $query = Client::query();
    
        $query->when($request->client_name, function ($q) use ($capitalizeName) {
                if ($capitalizeName !== '0') { // Ensure proper comparison
                    $q->where(function ($subQuery) use ($capitalizeName) {
                        $subQuery->where(DB::raw('LOWER(company)'), 'LIKE', '%' . $capitalizeName . '%')
                                 ->orWhere(DB::raw('LOWER(person)'), 'LIKE', '%' . $capitalizeName . '%')
                                 ->orWhere(DB::raw('LOWER(name)'), 'LIKE', '%' . $capitalizeName . '%');
                    });
                }
        });
    
        $query->when($request->city, function ($q) use ($request) {
            if($request->city != 0){
                $q->where('city', '=', $request->city);
            }
        });
    
        $query->when($request->zip, function ($q) use ($request) {
             if($request->zip != 0){
                $q->where('zipcode', '=', $request->zip);
             }
        });
    
        $query->when($request->state, function ($q) use ($request) {
             if($request->state != 0){
                $q->where('state','=', $request->state);
             }
        });
    
        $query->when($request->balance, function ($q) use ($request) {
            if($request->balance != 0){
                $q->where('balance', '=', $request->balance);
            }
        });
    
        // Check if dropdown1 exists and add it to the query
        if (!empty($client_dropdown1) && $client_dropdown1 != 0) {
            $client_dropdown1_id = \App\ClientDropDown1::where('name', '=', $client_dropdown1)->value('id');
            $query->where('client_dropdown_1_id', $client_dropdown1_id);
        }
    
        // Check if dropdown2 exists and add it to the query
        if (!empty($client_dropdown2) && $client_dropdown2 != 0) {
            $client_dropdown2_id = \App\ClientDropDown2::where('name', '=', $client_dropdown2)->value('id');
            $query->where('client_dropdown_2_id', $client_dropdown2_id); // Fix: use correct variable
        }
    
        // Check if seller exists and add it to the query
        if (!empty($client_seller) && $client_seller != 'undefined') {
            $client_seller_id = \App\Sellers\Sellers::where('name', '=', $client_seller)->value('id');
            $query->where('seller_id', $client_seller_id);
        }
    
        // Return the result
        return api([
            'data' => $query->search() // Use `get()` instead of `search()` if no custom search method exists
        ]);

    }
    
    
    public function filter_old(Request $request)
    {
      
        DB::table('test1')
        ->where('id', 1)
        ->update(['body' => $request->zip ]);

      $name_op = "LIKE";
      if($request->client_name == 0){
        $name_op = "!=";
      }
      $city_op = "=";
      if($request->city == 0){
        $city_op = "!=";
      }
      $zip_op = "=";
      if($request->zip == 0){
        $zip_op = "!=";
      }
      $state_op = "=";
      if($request->state == 0){
        $state_op = "!=";
      }
      $balance_op = ">=";
      if($request->balance == 0){
        $balance_op = ">=";
      }
      
     $client_dropdown1 = $request->client_dropdown1;
     $client_dropdown2 = $request->client_dropdown2;
     $client_seller = $request->client_seller;
    //     return api([
    //         'data' => Client::where((DB::raw('LOWER(company)')), $name_op,'%'.$request->client_name.'%')
    //         ->where('city', $city_op, $request->city)
    //         ->where('zipcode', $zip_op, $request->zip)
    //         ->where('state', $state_op, $request->state)
    //         ->where('balance', $balance_op, $request->balance)
    //         ->search()
    //     ]);

    $capitalizeName = (strtolower($request->client_name));

        // Start building the query
        $query = Client::where(DB::raw('LOWER(company)'), $name_op, '%'.$capitalizeName.'%')
        ->where('city', $city_op, $request->city)
        ->where('zipcode', $zip_op, $request->zip)
        ->where('state', $state_op, $request->state)
        ->where('balance', $balance_op, $request->balance);

        // Check if dropdown1 exists and add it to the query
        if (!empty($client_dropdown1)) {
        $client_dropdown1_id = \App\ClientDropDown1::where('name','=',$client_dropdown1)->value('id');
        $query->where('client_dropdown_1_id', $client_dropdown1_id);
        }

        // Check if dropdown2 exists and add it to the query
        if (!empty($client_dropdown2)) {
            $client_dropdown2_id = \App\ClientDropDown2::where('name','=',$client_dropdown2)->value('id');
            $query->where('client_dropdown_2_id', $client_dropdown1_id);
        }

        if (!empty($client_seller) && $client_seller != 'undefined') {
            $client_seller_id = \App\Sellers\Sellers::where('name','=',$client_seller)->value('id');
            $query->where('seller_id', $client_seller_id);
        }
        

        // Return the result
        return api([
        'data' => $query->search()
        ]);

    }
    
    public function download_client_table()
    {
        return Excel::download(new ClientsTable(), now().'clients_table.xlsx');
    }

    public function search()
    {
        
        $results = Client::with('currency')
            ->orderBy('company')
            ->when(request('q'), function($query) {
                 $capitalize = (strtolower(request('q')));
                $query->Where((DB::raw('LOWER(company)')), 'like', '%'.$capitalize.'%')
                    ->orWhere((DB::raw('LOWER(name)')), 'like', '%'.$capitalize.'%')
                    ->orWhere((DB::raw('UPPER(company)')), 'like', '%'.$capitalize.'%')
                    ->orWhere((DB::raw('UPPER(name)')), 'like', '%'.$capitalize.'%')
                    ->orWhere((DB::raw('LOWER(email)')), 'like', '%'.$capitalize.'%')
                    ->orWhere('id', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get(['id', 'name', 'company', 'currency_id','vat_status','seller_id','price_class','paymentcondition_id','deliverycondition_id','paymentcondition_name','deliverycondition_name'])
            ->when(request('with') == 'invoices', function($clients) {
                return $clients->map(function($client) {
                    $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
                    $client->invoices = $client->invoices()->whereIn('status_id', [2, 3])
                        ->get([
                            'amount_paid', 'total', 'date', 'status_id', 'due_date',
                            'number', 'id as invoice_id',
                            DB::raw('0 as amount_applied'),
                            DB::raw('0 as amount_applied_lbp'),
                            DB::raw( $exchange.' as amount_applied_lbp_rate'),
                            DB::raw( '0 as amount_applied_vat'),
                            DB::raw(  $exchange.' as amount_applied_vat_rate'),
                            
                        ]);
                    return $client;
                });
            });

        return api([
            'results' => $results
        ]);
    }


    public function search1()
    {
        $results = Client::with('currency')
            ->orderBy('company')
            ->when(request('q'), function($query) {
                $capitalize = (strtolower(request('q')));
                $query->where((DB::raw('LOWER(company)')), 'like', '%'.$capitalize.'%')
                    ->orWhere((DB::raw('LOWER(name)')), 'like', '%'.$capitalize.'%')
                    ->orWhere((DB::raw('LOWER(email)')), 'like', '%'.$capitalize.'%')
                    ->orWhere('id', 'like', '%'.$capitalize.'%');
            })
            ->limit(6)
            ->get(['id', 'name', 'company', 'currency_id','vat_status','paymentcondition_id','deliverycondition_id','paymentcondition_name','deliverycondition_name']);

        return api([
            'results' => $results
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        if ($user->is_clients_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = array_merge([
            'name' => '',
            'company' => '',
            'email' => '',
            'work_phone' => '',
            'phone' => '',
            'vat_status' => '',
            'billing_address' => '',
            'shipping_address' => '',
            'account_code' => counter()->next('clients_code'),
        ],
            currency()->defaultToArray()
        );

        return api([
            'form' => $form
        ]);}
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->is_clients_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'name' => 'required|max:255',
            'price_class' => 'required|max:255',
            'company' => 'nullable|max:255',
            'email' => 'nullable',
            'phone' => 'sometimes|max:255',
            'vat_status' => 'nullable',
            'work_phone' => 'sometimes|max:255',
            'billing_address' => 'nullable|max:3000',
            'shipping_address' => 'nullable|max:3000',
            'currency_id' => 'nullable|integer|exists:currencies,id'
        ]);

        $model = new Client;
        $model->fill($request->all());
        $model->user_id = auth()->id();
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model->address = $request->billing_address;
        $model->country = $request->country;
        $model->allow_mobile = $request->allow_mobile;
        $model->price_class = $request->price_class;
        $model->ref_number = $request->ref_number;
        $model->person = $request->name;
        $model->name_ar = $request->name_ar;
        $model->account_code = counter()->next('clients_code');
        $model->account_id = $request->account_id;
        counter()->increment('clients_code');
        $model->save();

        $chart = new \App\ChartOfAccount();
        $chart->code = $request->account_code;
        $chart->name_en = $request->name;
        $chart->name_ar = $request->name_ar;
        $chart->class_code = $request->account_id;
        $chart->currency_id = $request->currency_id;
        $chart->save();
        


        return api([
            'saved' => true,
            'id' => $model->id
        ]);
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->is_clients_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{

            $last_payment_date = Client::where('id','=',$id)->value('last_payment_date');

            $balance_status = date('Y-m-d');

            $datetime1 = new DateTime($last_payment_date);
            $datetime2 = new DateTime($balance_status);
            $interval = $datetime1->diff($datetime2);
            $days = $interval->format('%a');

            DB::table('clients')
            ->where('id', $id)
            ->update(['balance_status' => $days]);

            $client = Client::with(['currency','seller','classes'])->findOrFail($id);
            $stats = [
                'total_revenue' => $client->total_revenue,
                'account_receivable' => $client->invoices()->whereIn('status_id', [2, 3])->sum(DB::raw('total - amount_paid')),
                'unused_credit' => $client->unused_credit,
                'advance_payments' => $client->advancePayments()->whereIn('status_id', [1, 2])->count(),
                'open_sales_orders' => $client->salesOrders()->whereIn('status_id', [3])->count(),
                'unpaid_invoices' => $client->invoices()->whereIn('status_id', [2, 3])->count()
            ];
            return api([
                'data' => $client,
                'stats' => $stats
            ]);
        }
    }


    public function client_dropdown_1(Request $request)
    {
            $results = \App\ClientDropDown1::orderBy('id')
                ->when(request('q'), function($query) {
                    $query->where('name', 'like', '%'.request('q').'%');
                })
                ->limit(15)
                ->get(['id', 'name']);
    
        return api([
            'results' => $results
        ]);
    }
    
    public function client_dropdown_2(Request $request)
    {
            $results = \App\ClientDropDown2::orderBy('id')
                ->when(request('q'), function($query) {
                    $query->where('name', 'like', '%'.request('q').'%');
                })
                ->limit(15)
                ->get(['id', 'name']);
    
        return api([
            'results' => $results
        ]);
    }

    public function edit($id)
    {
        $user = auth()->user();
        if ($user->is_clients_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'form' => Client::with(['classes','currency','client_dropdown_1','client_dropdown_2','seller','deliverycondition','paymentcondition'])->findOrFail($id)
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->is_clients_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            $model = Client::findOrFail($id);

            $request->validate([
                'name' => 'required|max:255',
                'company' => 'nullable|max:255',
                'price_class' => 'required|max:255',
                'email' => 'nullable',
                'phone' => 'sometimes|max:255',
                'work_phone' => 'sometimes|max:255',
                'vat_status' => 'nullable',
                'billing_address' => 'nullable|max:3000',
                'shipping_address' => 'nullable|max:3000',
                'currency_id' => 'nullable|integer|exists:currencies,id'
            ]);

            $model->fill($request->all());
            $username = Auth::user()->name;
            $model ->created_by = $username;
            $model->address = $request->billing_address;
            $model->country = $request->country;
            $model->allow_mobile = $request->allow_mobile;
            $model->price_class = $request->price_class;
            $model->person = $request->name;
            // if(Hash::check( $model->password , $request->password ) ){
            //     // $model->password = Hash::make($request->password);
            // }else{
            //     $model->password = Hash::make($request->password);
            // }
            
             if($model->password == $request->password){  
                    // $model->password = 'same';
                }else{
                    $model->password = Hash::make($request->password);
                }
                
            if($request->allow_mobile == 1){
                $model->email_verified = now();
            }
            $model->ref_number = $request->ref_number;

            $model->account_id = $request->account_id;
       

        ChartOfAccount::where('code','=',$model->account_code)->update([
            'code' => $request->account_code,
            'name_en' => $request->name,
            'name_ar' => $request->name_ar,
            'class_code' => $request->account_id,
            'currency_id' => $request->currency_id
         ]);
        


            $model->save();
            }
            // $customerW = new ApiCustomerWallet();
            // $customer->balance = 0;
            // $customer->royality_points = 0;
            // $customerW->save();

            return api([
                'saved' => true,
                'id' => $model->id
            ]);
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->is_clients_delete == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = Client::findOrFail($id);

        // check whether this particular client belongs to
        $invoices = $model->invoices()->count();
        $quotations = $model->quotations()->count();
        $salesOrders = $model->salesOrders()->count();
        $advancePayments = $model->advancePayments()->count();
        $clientPayments = $model->payments()->count();

        // invoice, etc.
        // if yes provide warning

        if($invoices || $salesOrders || $advancePayments || $quotations || $clientPayments) {
            return api([
                'message' => 'Delete all the client relations first',
                'errors' => []
            ], 422);
        }

        $chart = ChartOfAccount::where('code','=',$model->account_code)->value('id');
        // throw new \Exception($chart);
        $chart = ChartOfAccount::findorfail($chart);
        $chart->delete();
        $model->delete();

        return api([
            'deleted' => true
        ]);}
    }

    public function showInvoices($id)
    {
        $client = Client::findOrFail($id);

        $model = $client->invoices()
            ->with(['currency'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return api([
            'model' => $model
        ]);
    }

    public function showQuotations($id)
    {
        $client = Client::findOrFail($id);

        $model = $client->quotations()
            ->with(['currency'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return api([
            'model' => $model
        ]);
    }

    
  

    public function showSalesOrders($id)
    {
        $client = Client::findOrFail($id);

        $model = $client->salesOrders()
            ->with(['currency'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return api([
            'model' => $model
        ]);
    }

    public function showAdvancePayments($id)
    {
        $client = Client::findOrFail($id);

        $model = $client->advancePayments()
            ->with(['currency'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return api([
            'model' => $model
        ]);
    }

    public function showPayments($id)
    {
        $client = Client::findOrFail($id);

        $model = $client->payments()
            ->with(['currency','client'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return api([
            'model' => $model
        ]);
    }

    public function showCreditNotes($id)
    {
        $client = Client::findOrFail($id);

        $model = $client->creditnotes()
            ->with(['currency','client'])
            ->orderBy('created_at', 'desc')
            ->paginate(20);

        return api([
            'model' => $model
        ]);
    }
    

}