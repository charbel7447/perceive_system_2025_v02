<?php

namespace App\Http\Controllers\Settings;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

// global route middleware to authorize

class UserController extends Controller
{
    public function index()
    {
        return api([
            'data' => User::search()
        ]);
    }

    public function search()
    {
        $results = User::when(request('q'), function($query) {
                $query->where('name', 'like', '%'.request('q').'%');
            })
            ->limit(6)
            ->get();

        return api([
            'results' => $results
        ]);
    }
    

    public function create()
    {
        $form = [
            'name' => '',
            'title' => null,
            'telephone' => null,
            'extension' => null,
            'mobile_number' => null,
            'manager_id' => null,
            'email' => '',
            'password' => '',
            'password_confirmation' => '',
            'email_signature' => 'Best Regards',
            'is_admin' => 0,
            'is_active' => 1,
            'is_settings_tab'=> 1,
            'is_procurment_tab'=> 1,
            'is_sales_tab'=> 1,
            'is_accounting_tab'=> 1,
            'is_company_tab'=> 1,
            'is_dashboard'=> 1,
            'is_deliverycondition_tab'=> 0,
            'is_paymentcondition_tab'=> 0,
            'is_exchangerate_tab'=> 0,
            'is_uom_tab'=> 0,
            'is_counters_tab'=> 0,
            'is_currencies_tab'=> 0,
            'is_warehouses_tab'=> 0,
            'is_categories_tab'=> 0,
            'is_subcategories_tab'=> 0,
            'is_accounts_tab'=> 0,
            'is_transferaccounts_tab'=> 0,
            'is_deposit_tab'=> 0,
            'is_returndeposit_tab'=> 0,
            'is_employees_tab'=> 0,
            'is_payroll_tab'=> 0,
            'is_clients_tab'=> 0,
            'is_quotations_tab'=> 0,
            'is_salesorders_tab'=> 0,
            'is_advancepayments_tab'=> 0,
            'is_invoices_tab'=> 0,
            'is_creditnotes_tab'=> 0,
            'is_debitnotes_tab'=> 0,
            'is_clientpayments_tab'=> 0,
            'is_clientsoa_tab'=> 0,
            'is_vendorexpenses_tab'=> 0,
            'is_bills_tab'=> 0,
            'is_vendorpayments_tab'=> 0,
            'is_vendorsoa_tab'=> 0,
            'is_products_tab'=> 0,
            'is_receiveorders_tab'=> 0,
            'is_vendors_tab'=> 0,
            'is_purchaseorders_tab'=> 0,
            'is_transfers_tab'=> 0,
            'is_productsdivision_tab'=> 0,
            'is_productsaggregation_tab'=> 0,
            'is_displayoverview_tab'=> 0,
            'is_displaysales_tab'=> 0,
            'is_displayaccounting_tab'=> 0,
            'is_displaystock_tab'=> 0,
            'company' => settings()->get('company_name')
        ];

        return api([
            'form' => $form
        ]);
    }

   


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|max:255',
            'title' => 'nullable|max:255',
            'email' => 'required|email|unique:users,email|max:255',
            'mobile_number' => 'nullable|max:255',
            'telephone' => 'nullable|max:255',
            'extension' => 'nullable|max:255',
            'password' => 'required|confirmed|min:6|max:60',
            'email_signature' => 'required|max:255',
            'is_admin' => 'required|boolean',
            'is_active' => 'required|boolean'
        ]);

        $model = new User;
        $model->fill($request->all());
        $model->is_admin = $request->is_admin;
        $model->is_active = $request->is_active;
        $model->save();

        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }

    public function show($id)
    {
        return api([
            'data' => User::findOrFail($id)
        ]);
    }

    public function edit($id)
    {
        $user = User::with('manager')
        ->orderBy('name')
        ->findOrFail($id);
        $user->company = settings()->get('company_name');
        return api([
            'form' => $user
        ]);
    }

    public function update(Request $request, $id)
    {
        $model = User::findOrFail($id);

        $request->validate([
            'name' => 'required|max:255',
            'title' => 'nullable|max:255',
            'email' => 'required|email|unique:users,email,'.$model->id.',id',
            'mobile_number' => 'nullable|max:255',
            'telephone' => 'nullable|max:255',
            'extension' => 'nullable|max:255',
            'password' => 'sometimes|confirmed|min:6|max:60',
            'email_signature' => 'required|max:255',
            'is_admin' => 'required|boolean',
            'is_active' => 'required|boolean'
        ]);

        $model->fill($request->all());
        $model->is_admin = $request->is_admin;
        $model->is_active = $request->is_active;
        $model->save();

        return api([
            'saved' => true,
            'id' => $model->id
        ]);
    }

    public function destroy($id)
    {
        $this->authorize('delete');

        $model = User::findOrFail($id);

        // cannot self delete

        if(auth()->id() == $model->id) {
            return api([
                'errors' => [],
                'message' => 'Cannot delete yourself!'
            ], 422);
        }

        // delete user
        $model->delete();

        return api([
            'deleted' => true
        ]);
    }
}
