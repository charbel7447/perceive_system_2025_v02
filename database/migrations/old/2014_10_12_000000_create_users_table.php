<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');

             // optional
             $table->string('title')->nullable();
             $table->string('telephone')->nullable();
             $table->string('extension')->nullable();
             $table->string('mobile_number')->nullable();
 
             // login
             $table->string('email')->unique();
             $table->string('password');
             $table->boolean('is_admin')->default(0);
             $table->boolean('is_active')->default(0);
             $table->integer('manager_id')->default(1);
 
             //sidebar
             $table->boolean('is_settings_tab')->nullable();
             $table->boolean('is_procurment_tab')->nullable();
             $table->boolean('is_sales_tab')->nullable();
             $table->boolean('is_accounting_tab')->nullable();
             $table->boolean('is_company_tab')->nullable();
             $table->boolean('is_dashboard')->nullable();

            //settings tab             
             $table->boolean('is_deliverycondition_tab')->nullable();
             $table->boolean('is_paymentcondition_tab')->nullable();
             $table->boolean('is_exchangerate_tab')->nullable();
             $table->boolean('is_uom_tab')->nullable();
             $table->boolean('is_counters_tab')->nullable();
             $table->boolean('is_currencies_tab')->nullable();
             $table->boolean('is_warehouses_tab')->nullable();
             $table->boolean('is_categories_tab')->nullable();
             $table->boolean('is_subcategories_tab')->nullable();

             // deliverycondition
             $table->boolean('is_deliverycondition_create')->nullable();
             $table->boolean('is_deliverycondition_edit')->nullable();
             $table->boolean('is_deliverycondition_delete')->nullable();
             $table->boolean('is_deliverycondition_view')->nullable();

             // paymentcondition
             $table->boolean('is_paymentcondition_create')->nullable();
             $table->boolean('is_paymentcondition_edit')->nullable();
             $table->boolean('is_paymentcondition_delete')->nullable();
             $table->boolean('is_paymentcondition_view')->nullable();

             // exchangerate
             $table->boolean('is_exchangerate_create')->nullable();
             $table->boolean('is_exchangerate_edit')->nullable();
             $table->boolean('is_exchangerate_delete')->nullable();
             $table->boolean('is_exchangerate_view')->nullable();

             // uom
             $table->boolean('is_uom_create')->nullable();
             $table->boolean('is_uom_edit')->nullable();
             $table->boolean('is_uom_delete')->nullable();
             $table->boolean('is_uom_view')->nullable();

             // counters
             $table->boolean('is_counters_create')->nullable();
             $table->boolean('is_counters_edit')->nullable();
             $table->boolean('is_counters_delete')->nullable();
             $table->boolean('is_counters_view')->nullable();

             // currencies
             $table->boolean('is_currencies_create')->nullable();
             $table->boolean('is_currencies_edit')->nullable();
             $table->boolean('is_currencies_delete')->nullable();
             $table->boolean('is_currencies_view')->nullable();

             // warehouses
             $table->boolean('is_warehouses_create')->nullable();
             $table->boolean('is_warehouses_edit')->nullable();
             $table->boolean('is_warehouses_delete')->nullable();
             $table->boolean('is_warehouses_view')->nullable();

             // categories
             $table->boolean('is_categories_create')->nullable();
             $table->boolean('is_categories_edit')->nullable();
             $table->boolean('is_categories_delete')->nullable();
             $table->boolean('is_categories_view')->nullable();

             //sub categories
             $table->boolean('is_subcategories_create')->nullable();
             $table->boolean('is_subcategories_edit')->nullable();
             $table->boolean('is_subcategories_delete')->nullable();
             $table->boolean('is_subcategories_view')->nullable();
             
            //company tab             
            $table->boolean('is_accounts_tab')->nullable();
            $table->boolean('is_transferaccounts_tab')->nullable();
            $table->boolean('is_deposit_tab')->nullable();
            $table->boolean('is_returndeposit_tab')->nullable();
            $table->boolean('is_employees_tab')->nullable();
            $table->boolean('is_payroll_tab')->nullable();
            
            //accounts
            $table->boolean('is_accounts_create')->nullable();
            $table->boolean('is_accounts_edit')->nullable();
            $table->boolean('is_accounts_delete')->nullable();
            $table->boolean('is_accounts_view')->nullable();

            //transferaccounts
            $table->boolean('is_transferaccounts_create')->nullable();
            $table->boolean('is_transferaccounts_edit')->nullable();
            $table->boolean('is_transferaccounts_delete')->nullable();
            $table->boolean('is_transferaccounts_view')->nullable();

            //deposit
            $table->boolean('is_deposit_create')->nullable();
            $table->boolean('is_deposit_edit')->nullable();
            $table->boolean('is_deposit_delete')->nullable();
            $table->boolean('is_deposit_view')->nullable();
            
            //returndeposit
             $table->boolean('is_returndeposit_create')->nullable();
             $table->boolean('is_returndeposit_edit')->nullable();
             $table->boolean('is_returndeposit_delete')->nullable();
             $table->boolean('is_returndeposit_view')->nullable();

            //employees
            $table->boolean('is_employees_create')->nullable();
            $table->boolean('is_employees_edit')->nullable();
            $table->boolean('is_employees_delete')->nullable();
            $table->boolean('is_employees_view')->nullable();
            
            //payroll
            $table->boolean('is_payroll_create')->nullable();
            $table->boolean('is_payroll_edit')->nullable();
            $table->boolean('is_payroll_delete')->nullable();
            $table->boolean('is_payroll_view')->nullable();

            //sales
            $table->boolean('is_clients_tab')->nullable();
            $table->boolean('is_quotations_tab')->nullable();
            $table->boolean('is_salesorders_tab')->nullable();

            //clients
            $table->boolean('is_clients_create')->nullable();
            $table->boolean('is_clients_edit')->nullable();
            $table->boolean('is_clients_delete')->nullable();
            $table->boolean('is_clients_view')->nullable();

            //quotations
            $table->boolean('is_quotations_create')->nullable();
            $table->boolean('is_quotations_edit')->nullable();
            $table->boolean('is_quotations_delete')->nullable();
            $table->boolean('is_quotations_view')->nullable();
            
            //salesorders
            $table->boolean('is_salesorders_create')->nullable();
            $table->boolean('is_salesorders_edit')->nullable();
            $table->boolean('is_salesorders_delete')->nullable();
            $table->boolean('is_salesorders_view')->nullable();
            
            //Accounting Tab
            $table->boolean('is_advancepayments_tab')->nullable();
            $table->boolean('is_invoices_tab')->nullable();
            $table->boolean('is_creditnotes_tab')->nullable();
            $table->boolean('is_debitnotes_tab')->nullable();
            $table->boolean('is_clientpayments_tab')->nullable();
            $table->boolean('is_clientsoa_tab')->nullable();
            $table->boolean('is_vendorexpenses_tab')->nullable();
            $table->boolean('is_bills_tab')->nullable();
            $table->boolean('is_vendorpayments_tab')->nullable();
            $table->boolean('is_vendorsoa_tab')->nullable();
            
            //advancepayments
            $table->boolean('is_advancepayments_create')->nullable();
            $table->boolean('is_advancepayments_edit')->nullable();
            $table->boolean('is_advancepayments_delete')->nullable();
            $table->boolean('is_advancepayments_view')->nullable();

            //invoices
            $table->boolean('is_invoices_create')->nullable();
            $table->boolean('is_invoices_edit')->nullable();
            $table->boolean('is_invoices_delete')->nullable();
            $table->boolean('is_invoices_view')->nullable();

            //creditnotes
            $table->boolean('is_creditnotes_create')->nullable();
            $table->boolean('is_creditnotes_edit')->nullable();
            $table->boolean('is_creditnotes_delete')->nullable();
            $table->boolean('is_creditnotes_view')->nullable();

            //debitnotes
            $table->boolean('is_debitnotes_create')->nullable();
            $table->boolean('is_debitnotes_edit')->nullable();
            $table->boolean('is_debitnotes_delete')->nullable();
            $table->boolean('is_debitnotes_view')->nullable();

            //clientpayments
            $table->boolean('is_clientpayments_create')->nullable();
            $table->boolean('is_clientpayments_edit')->nullable();
            $table->boolean('is_clientpayments_delete')->nullable();
            $table->boolean('is_clientpayments_view')->nullable();
            
            //clientsoa
            $table->boolean('is_clientsoa_create')->nullable();
            $table->boolean('is_clientsoa_edit')->nullable();
            $table->boolean('is_clientsoa_delete')->nullable();
            $table->boolean('is_clientsoa_view')->nullable();

            //vendorexpenses
            $table->boolean('is_vendorexpenses_create')->nullable();
            $table->boolean('is_vendorexpenses_edit')->nullable();
            $table->boolean('is_vendorexpenses_delete')->nullable();
            $table->boolean('is_vendorexpenses_view')->nullable();

            //bills
            $table->boolean('is_bills_create')->nullable();
            $table->boolean('is_bills_edit')->nullable();
            $table->boolean('is_bills_delete')->nullable();
            $table->boolean('is_bills_view')->nullable();

            //vendorpayments
            $table->boolean('is_vendorpayments_create')->nullable();
            $table->boolean('is_vendorpayments_edit')->nullable();
            $table->boolean('is_vendorpayments_delete')->nullable();
            $table->boolean('is_vendorpayments_view')->nullable();

            //vendorsoa
            $table->boolean('is_vendorsoa_create')->nullable();
            $table->boolean('is_vendorsoa_edit')->nullable();
            $table->boolean('is_vendorsoa_delete')->nullable();
            $table->boolean('is_vendorsoa_view')->nullable();
            
            //Procurment & Stock Tab
            $table->boolean('is_products_tab')->nullable();
            $table->boolean('is_receiveorders_tab')->nullable();
            $table->boolean('is_vendors_tab')->nullable();
            $table->boolean('is_purchaseorders_tab')->nullable();
            $table->boolean('is_transfers_tab')->nullable();
            $table->boolean('is_productsdivision_tab')->nullable();
            $table->boolean('is_productsaggregation_tab')->nullable();
            $table->boolean('is_stockmovement_tab')->nullable();
            

            //products
            $table->boolean('is_products_create')->nullable();
            $table->boolean('is_products_edit')->nullable();
            $table->boolean('is_products_delete')->nullable();
            $table->boolean('is_products_view')->nullable();

            //receiveorders
            $table->boolean('is_receiveorders_create')->nullable();
            $table->boolean('is_receiveorders_edit')->nullable();
            $table->boolean('is_receiveorders_delete')->nullable();
            $table->boolean('is_receiveorders_view')->nullable();
            
            //vendors
            $table->boolean('is_vendors_create')->nullable();
            $table->boolean('is_vendors_edit')->nullable();
            $table->boolean('is_vendors_delete')->nullable();
            $table->boolean('is_vendors_view')->nullable();

            //purchaseorders
            $table->boolean('is_purchaseorders_create')->nullable();
            $table->boolean('is_purchaseorders_edit')->nullable();
            $table->boolean('is_purchaseorders_delete')->nullable();
            $table->boolean('is_purchaseorders_view')->nullable();

            //transfers
            $table->boolean('is_transfers_create')->nullable();
            $table->boolean('is_transfers_edit')->nullable();
            $table->boolean('is_transfers_delete')->nullable();
            $table->boolean('is_transfers_view')->nullable();

            //productsdivision
            $table->boolean('is_productsdivision_create')->nullable();
            $table->boolean('is_productsdivision_edit')->nullable();
            $table->boolean('is_productsdivision_delete')->nullable();
            $table->boolean('is_productsdivision_view')->nullable();

            //productsaggregation
            $table->boolean('is_productsaggregation_create')->nullable();
            $table->boolean('is_productsaggregation_edit')->nullable();
            $table->boolean('is_productsaggregation_delete')->nullable();
            $table->boolean('is_productsaggregation_view')->nullable();

            $table->boolean('is_stockmovement_view')->nullable();
            
            //overview
            $table->boolean('is_displayoverview_tab')->nullable();
            $table->boolean('is_displaysales_tab')->nullable();
            $table->boolean('is_displayaccounting_tab')->nullable();
            $table->boolean('is_displaystock_tab')->nullable();
            
             //Production
             $table->boolean('is_displayproduction_tab')->nullable();
             $table->boolean('is_production_tab')->nullable();
          
            // email
             $table->text('email_signature');
 
             $table->rememberToken();
             $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
