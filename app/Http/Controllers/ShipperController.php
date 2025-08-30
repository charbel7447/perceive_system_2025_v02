<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Shipper;
use DB;
use Auth;
use App\ExchangeRate\ExchangeRate;

class ShipperController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        if ($user->is_Define_Shippers_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'data' => Shipper::search()
            ]);
        }
    }

    public function search()
    {
        $results = Shipper::with('currency')
            ->orderBy('company')
            ->when(request('q'), function($query) {
                $query->where('company', 'like', '%'.request('q').'%')
                    ->orWhere('person', 'like', '%'.request('q').'%')
                    ->orWhere('email', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get(['id', 'person', 'company', 'currency_id','vat_status','container_size'])
            ->when(request('with') == 'bills', function($vendors) {
                return $vendors->map(function($vendor) {
                    $exchange = ExchangeRate::where('currency2','=',2)->take(1)->latest()->value('value2');
                    $vendor->bills = $vendor->shipperbills()->whereIn('status_id', [1, 2])
                        ->get([
                            'amount_paid', 'total', 'date', 'status_id', 'due_date',
                            'number', 'id as shipper_bill_id',
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
        if ($user->is_Define_Shippers_create == 0 && $user->is_admin != 1){
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
            'payment_details' => ''
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
        if ($user->is_Define_Shippers_create == 0 && $user->is_admin != 1){
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

        $model = new Shipper;
        $model->fill($request->all());
        $model->user_id = auth()->id();
        $username = Auth::user()->name;
        $model ->created_by = $username;
        $model->save();

        return api([
            'saved' => true,
            'id' => $model->id
        ]);
        }
    }

    public function show($id)
    {
        $user = auth()->user();
        if ($user->is_Define_Shippers_view == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $vendor = Shipper::with(['currency'])->findOrFail($id);

        $stats = [
            'total_expense' => $vendor->total_expense,
        ];
            return api([
                'data' => $vendor,
                'stats' => $stats
            ]);
        }
    }

    public function showPurchaseOrders($id)
    {
        $vendor = Shipper::findOrFail($id);

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
        $vendor = Shipper::findOrFail($id);

        $model = $vendor->receiveOrders()
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return api([
            'model' => $model
        ]);
    }


    public function edit($id)
    {
        $user = auth()->user();
        if ($user->is_Define_Shippers_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
            return api([
                'form' => Shipper::with(['currency'])->findOrFail($id)
            ]);
        }
    }

    public function update(Request $request, $id)
    {
        $user = auth()->user();
        if ($user->is_Define_Shippers_edit == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = Shipper::findOrFail($id);

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
        $model->save();

        return api([
            'saved' => true,
            'id' => $model->id
        ]);
        }
    }

    public function destroy($id)
    {
        $user = auth()->user();
        if ($user->is_Define_Shippers_delete == 0 && $user->is_admin != 1){
                return response()->json(['error' => 'Forbidden.'], 403);
        }else{
        $model = Shipper::findOrFail($id);

        // check whether this particular vendor belongs to
        // $bills = $model->bills()->count();
        // $purchaseOrders = $model->purchaseOrders()->count();
        // $expenses = $model->expenses()->count();
        // $vendorPayments = $model->payments()->count();
        // $products = $model->products()->count();

        // // invoice, etc.
        // // if yes provide warning

        // if($products || $purchaseOrders || $expenses || $bills || $vendorPayments) {
        //     return api([
        //         'message' => 'Delete all the vendor relations first',
        //         'errors' => []
        //     ], 422);
        // }

        $model->delete();

        return api([
            'deleted' => true
        ]);
    }
}
}
