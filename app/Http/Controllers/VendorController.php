<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Vendor;
use DB;
use Auth;
use App\ExchangeRate\ExchangeRate;
use App\ChartOfAccount;

class VendorController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_vendors_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Vendor::orderby('created_at','desc')->search()
            ]);
        }
    }

    public function search()
    {
        $results = Vendor::with('currency')
            ->orderBy('company')
            ->when(request('q'), function($query) {
                $query->where('company', 'like', '%'.request('q').'%')
                    ->orWhere('person', 'like', '%'.request('q').'%')
                    ->orWhere('email', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get(['id', 'person', 'company', 'currency_id','vat_status','discount'])
            ->when(request('with') == 'bills', function($vendors) {
                return $vendors->map(function($vendor) {
                    $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
                    $vendor->bills = $vendor->bills()->whereIn('status_id', [1, 2])
                        ->get([
                            'amount_paid', 'total', 'date', 'status_id', 'due_date',
                            'number', 'id as bill_id',
                            DB::raw('0 as amount_applied'),
                            DB::raw('0 as amount_applied_lbp'),
                            DB::raw('1 as amount_applied_lbp_rate'),
                            DB::raw( $exchange.' as amount_applied_lbp_rate'),
                            
                        ]);
                    return $vendor;
                });
            });

        return api([
            'results' => $results
        ]);
    }

    public function create()
    {
        $user = auth()->user();
        if ($user->is_vendors_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $form = array_merge([
            'person' => '',
            'company' => '',
            'email' => '',
            'work_phone' => '',
            'mobile_number' => '',
            'billing_address' => '',
            'shipping_address' => '',
            'payment_details' => '',
            'account_code' => counter()->next('vendors_code'),
        ],
            currency()->defaultToArray()
        );

        return api([
            'form' => $form
        ]);
            }
    }

    public function store(Request $request)
    {
        $user = auth()->user();
        if ($user->is_vendors_create == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $request->validate([
            'person' => 'required|max:255',
            'company' => 'nullable|max:255',
            'email' => 'nullable|email',
            'mobile_number' => 'sometimes|max:255',
            'work_phone' => 'sometimes|max:255',
            'billing_address' => 'nullable|max:3000',
            'shipping_address' => 'nullable|max:3000',
            'payment_details' => 'nullable|max:3000',
            'currency_id' => 'nullable|integer|exists:currencies,id'
        ]);

        $model = new Vendor;
        $model->fill($request->all());
        $model->user_id = auth()->id();
        $username = Auth::user()->name;
        $model->name_ar = $request->name_ar;
        $model ->created_by = $username;
        $model->account_code = counter()->next('vendors_code');
        $model->account_id = $request->account_id;
        counter()->increment('vendors_code');
        $model->save();

        $chart = new \App\ChartOfAccount();
        $chart->code = $request->account_code;
        $chart->name_en = $request->company;
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
        if ($user->is_vendors_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $vendor = Vendor::with(['currency','classes'])->findOrFail($id);

        $stats = [
            'total_expense' => $vendor->total_expense,
            'account_payable' => $vendor->bills()->whereIn('status_id', [1, 2])->sum(DB::raw('total - amount_paid')),
            'open_purchase_orders' => $vendor->purchaseOrders()->whereIn('status_id', [3])->count(),
            'unpaid_bills' => $vendor->bills()->whereIn('status_id', [1, 2])->count()
        ];
            return api([
                'data' => $vendor,
                'stats' => $stats
            ]);
        }
    }

    public function showBills($id)
    {
        $vendor = Vendor::findOrFail($id);

        $model = $vendor->bills()
            ->with(['currency'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return api([
            'model' => $model
        ]);
    }

    public function showPurchaseOrders($id)
    {
        $vendor = Vendor::findOrFail($id);

        $model = $vendor->purchaseOrders()
            ->with(['currency'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return api([
            'model' => $model
        ]);
    }

    public function showRecevieOrders($id)
    {
        $vendor = Vendor::findOrFail($id);

        $model = $vendor->receiveOrders()
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return api([
            'model' => $model
        ]);
    }

    public function showExpenses($id)
    {
        $vendor = Vendor::findOrFail($id);

        $model = $vendor->expenses()
            ->with(['currency'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return api([
            'model' => $model
        ]);
    }

    public function showProducts($id)
    {
        $vendor = Vendor::findOrFail($id);

        $model = $vendor->products()
            ->with(['product', 'currency'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return api([
            'model' => $model
        ]);
    }

    public function showPayments($id)
    {
        $vendor = Vendor::findOrFail($id);

        $model = $vendor->payments()
            ->with(['currency'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return api([
            'model' => $model
        ]);
    }

    public function edit($id)
    {
        $user = auth()->user();
        if ($user->is_vendors_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'form' => Vendor::with(['currency','classes'])->findOrFail($id)
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->is_vendors_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = Vendor::findOrFail($id);

        $request->validate([
            'person' => 'nullable|max:255',
            'company' => 'nullable|max:255',
            'email' => 'nullable|email|unique:vendors,email,'.$model->id.',id',
            'mobile_number' => 'sometimes|max:255',
            'work_phone' => 'sometimes|max:255',
            'billing_address' => 'nullable|max:3000',
            'shipping_address' => 'nullable|max:3000',
            'payment_details' => 'nullable|max:3000',
            'currency_id' => 'nullable|integer|exists:currencies,id'
        ]);

        $model->fill($request->all());
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model->account_id = $request->account_id;
        $model->save();

        ChartOfAccount::where('code','=',$model->account_code)->update([
            'code' => $request->account_code,
            'name_en' => $request->company,
            'name_ar' => $request->name_ar,
            'class_code' => $request->account_id,
            'currency_id' => $request->currency_id
         ]);
       

        return api([
            'saved' => true,
            'id' => $model->id
        ]);
        }
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->is_vendors_delete == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = Vendor::findOrFail($id);

        // check whether this particular vendor belongs to
        $bills = $model->bills()->count();
        $purchaseOrders = $model->purchaseOrders()->count();
        $expenses = $model->expenses()->count();
        $vendorPayments = $model->payments()->count();
        $products = $model->products()->count();

        // invoice, etc.
        // if yes provide warning

        if($products || $purchaseOrders || $expenses || $bills || $vendorPayments) {
            return api([
                'message' => 'Delete all the vendor relations first',
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
        ]);
    }
}
}
