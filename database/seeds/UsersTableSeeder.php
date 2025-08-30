<?php

use Illuminate\Database\Seeder;
use Faker\Factory;
use App\User;
use App\Account;
use App\Category;
use App\Client;
use App\DeliveryCondition;
use App\SubCategory;
use App\Warehouse;
use App\UOM;
use App\ExchangeRate\ExchangeRate;
use App\PaymentCondition;
use App\RawMaterialType;
use App\Vendor;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Factory::create();

        User::truncate();

        // test admin
        User::create([
            'name' => 'Administrator',
            'title' => 'Administrator',
            'telephone' => '+000 00 123 456',
            'extension' => '100',
            'mobile_number' => '+000 00 123 456',
            'email' => 'kabbouchi_erp@outlook.com',
            'password' => 'qwerty123',
            'email_signature' => 'Best Regards'.PHP_EOL,
            'is_admin' => 1,
            'is_active' => 1,
            'manager_id'=> 1,
            'is_settings_tab'=> 1,
            'is_procurment_tab'=> 1,
            'is_sales_tab'=> 1,
            'is_accounting_tab'=> 1,
            'is_company_tab'=> 1,
            'is_dashboard'=> 1,
            'is_deliverycondition_tab'=> 1,
            'is_paymentcondition_tab'=> 1,
            'is_exchangerate_tab'=> 1,
            'is_uom_tab'=> 1,
            'is_counters_tab'=> 1,
            'is_currencies_tab'=> 1,
            'is_warehouses_tab'=> 1,
            'is_categories_tab'=> 1,
            'is_subcategories_tab'=> 1,
            'is_accounts_tab'=> 1,
            'is_transferaccounts_tab'=> 1,
            'is_deposit_tab'=> 1,
            'is_returndeposit_tab'=> 1,
            'is_employees_tab'=> 1,
            'is_payroll_tab'=> 1,
            'is_clients_tab'=> 1,
            'is_quotations_tab'=> 1,
            'is_salesorders_tab'=> 1,
            'is_advancepayments_tab'=> 1,
            'is_invoices_tab'=> 1,
            'is_creditnotes_tab'=> 1,
            'is_debitnotes_tab'=> 1,
            'is_clientpayments_tab'=> 1,
            'is_clientsoa_tab'=> 1,
            'is_vendorexpenses_tab'=> 1,
            'is_bills_tab'=> 1,
            'is_vendorpayments_tab'=> 1,
            'is_vendorsoa_tab'=> 1,
            'is_products_tab'=> 1,
            'is_receiveorders_tab'=> 1,
            'is_vendors_tab'=> 1,
            'is_purchaseorders_tab'=> 1,
            'is_transfers_tab'=> 1,
            'is_productsdivision_tab'=> 1,
            'is_productsaggregation_tab'=> 1,
            'is_displayoverview_tab'=> 1,
            'is_displaysales_tab'=> 1,
            'is_displayaccounting_tab'=> 1,
            'is_displaystock_tab'=> 1,
            'is_stockmovement_tab' => 1,
            'is_stockmovement_view' => 1,
            'is_displayproduction_tab'=> 1,
            'is_production_tab'=> 1,
        ]);

        User::create([
            'name' => 'Info',
            'title' => 'Info',
            'telephone' => '+000 00 123 456',
            'extension' => '100',
            'mobile_number' => '+000 00 123 456',
            'email' => 'info@perceiveagency.me',
            'password' => 'qwerty123',
            'email_signature' => 'Best Regards'.PHP_EOL,
            'is_admin' => 1,
            'is_active' => 1,
            'manager_id'=> 2,
            'is_settings_tab'=> 1,
            'is_procurment_tab'=> 1,
            'is_sales_tab'=> 1,
            'is_accounting_tab'=> 1,
            'is_company_tab'=> 1,
            'is_dashboard'=> 1,
            'is_deliverycondition_tab'=> 1,
            'is_paymentcondition_tab'=> 1,
            'is_exchangerate_tab'=> 1,
            'is_uom_tab'=> 1,
            'is_counters_tab'=> 1,
            'is_currencies_tab'=> 1,
            'is_warehouses_tab'=> 1,
            'is_categories_tab'=> 1,
            'is_subcategories_tab'=> 1,
            'is_accounts_tab'=> 1,
            'is_transferaccounts_tab'=> 1,
            'is_deposit_tab'=> 1,
            'is_returndeposit_tab'=> 1,
            'is_employees_tab'=> 1,
            'is_payroll_tab'=> 1,
            'is_clients_tab'=> 1,
            'is_quotations_tab'=> 1,
            'is_salesorders_tab'=> 1,
            'is_advancepayments_tab'=> 1,
            'is_invoices_tab'=> 1,
            'is_creditnotes_tab'=> 1,
            'is_debitnotes_tab'=> 1,
            'is_clientpayments_tab'=> 1,
            'is_clientsoa_tab'=> 1,
            'is_vendorexpenses_tab'=> 1,
            'is_bills_tab'=> 1,
            'is_vendorpayments_tab'=> 1,
            'is_vendorsoa_tab'=> 1,
            'is_products_tab'=> 1,
            'is_receiveorders_tab'=> 1,
            'is_vendors_tab'=> 1,
            'is_purchaseorders_tab'=> 1,
            'is_transfers_tab'=> 1,
            'is_productsdivision_tab'=> 1,
            'is_productsaggregation_tab'=> 1,
            'is_displayoverview_tab'=> 1,
            'is_displaysales_tab'=> 1,
            'is_displayaccounting_tab'=> 1,
            'is_displaystock_tab'=> 1,
            'is_stockmovement_tab' => 1,
            'is_stockmovement_view' => 1,
            'is_displayproduction_tab'=> 1,
            'is_production_tab'=> 1,
        ]);

       
        Account::create([
            'currency_id' => 1,
            'name' => 'USD Account',
            'balance' => 0
        ]);

        Account::create([
            'currency_id' => 2,
            'name' => 'Euro Account',
            'balance' => 0
        ]);

        Category::create([
            'user_id' => 1,
            'name' => 'Main Category',
            'number' => '10',
            'parent_id' => 0,
            'description' => 'Main Category',
            'created_by' => 'Administrator',

        ]);

        SubCategory::create([
            'user_id' => 1,
            'name' => 'Sub Category',
            'number' => '10-001',
            'parent_id' => 1,
            'description' => 'Sub Category',
            'created_by' => 'Administrator',

        ]);

        Warehouse::create([
            'user_id' => 1,
            'name' => 'Finished Product Warehouse',
            'number' => 'WH-1000000',
            'description' => 'Finished Product Warehouse',
            'created_by' => 'Administrator',

        ]);

        ExchangeRate::create([
            'user_id' => 1,
            'currency1' => '1',
            'currency2' => '2',
            'value1' => '1',
            'value2' => '1',
            'created_by' => 'Administrator',
            'exchangedate' => today(),

        ]);

        UOM::create([
            'user_id' => 1,
            'unit' => 'KG',
            'created_by' => 'Administrator',
        ]);

        Vendor::create([
            'user_id' => 1,
            'person' => 'Vendor Name',
            'company' => 'Vendor Company',
            'email' => 'Vendor@email.com',
            'vat_status' => '1',
            'currency_id' => '1',
            'billing_Address' => 'Vendor Warehouse',
            'shipping_Address' => 'Vendor Warehouse',
            'created_by' => 'Administrator',
        ]);

        Client::create([
            'user_id' => 1,
            'person' => 'Client Name',
            'company' => 'Client Company',
            'email' => 'Client email.com',
            'vat_status' => '1',
            'currency_id' => '1',
            'billing_Address' => 'Client Warehouse',
            'shipping_Address' => 'Client Warehouse',
            'created_by' => 'Administrator',
        ]);

        DeliveryCondition::create([
            'user_id' => 1,
            'name' => 'Delivery Condition',
            'created_by' => 'Administrator',
        ]);

        PaymentCondition::create([
            'user_id' => 1,
            'name' => 'Payment Condition',
            'created_by' => 'Administrator',
        ]);

        RawMaterialType::create([
            'code' => 'RMT-1000',
            'name' => 'Type 1',
        ]);

    }
}
