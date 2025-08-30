<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Carbon\Carbon;

class CreateMenuTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name')->nullable(); 
            $table->string('link')->nullable();
            $table->timestamps();
        });
        $this->seedData();
    }

    protected function seedData()
    {   
        
        $now = Carbon::today();
        // echo $now->year;
        // echo $now->month;
        // echo $now->weekOfYear;

        $menu = [

            //sales
            'Clients' => '/clients',
            'Quotations' => '/quotations',
            'Sales Orders' => '/sales_orders',

            //Procurment & Stock
            'Raw Materials' => '/products',
            'Raw Material Type' => '/raw_material_type',
            'Receive Orders' => '/receive_orders',
            'Vendors' => '/vendors',
            'Purchase Orders' => '/purchase_orders',
            'Transfers' => '/transfers',
            'Products Division' => '/products_division',
            'Products Aggregation' => '/products_aggregation',
            'Stock Movement' => '/stock_movement',
            'Damaged Deteriorate' => '/damaged_deteriorate',

            //settings tab
            'Delivery Condition' => '/deliverycondition',
            'Payment Condition' => '/paymentcondition',
            'Exchange Rate' => '/exchangerate',
            'UOM' => '/uom',
            'Counters' => '/counters',
            'Currencies' => '/currencies',
            'Warehouses' => '/warehouses',
            'Categories' => '/categories',
            'Sub Categories' => '/subcategories',

            //company
            'Accounts' => '/accounts',
            'Transfer Accounts' => '/transfer_accounts',
            'Deposit' => '/deposits',
            'Return Deposit' => '/return_deposits',
            'Employees' => '/employees',
            'Payroll' => '/payroll',

            //Accounting
            'Client Advance Payments' => '/advance_payments',
            'Client Invoices' => '/invoices',
            'Credit Notes' => '/credit_notes',
            'Debit Notes' => '/debit_notes',
            'Client Payments' => '/client_payments',
            'Client Statement of Account' => '/statement',
            'Vendor Expenses' => '/expenses',
            'Vendor Bills' => '/bills',
            'Vendor Payments' => '/vendor_payments',
            'Vendor Statement of Account' => '/vendor_statement',

            //Administrator
            'Settings' => '/settings',
            'Users' => '/users',
            
        ];

        $processed = [];
        $today = today();

        foreach($menu as $key => $value) {
            $processed[] = [
                'name' => $key,
                'link' => $value,
                'created_at' => $today,
                'updated_at' => $today
            ];
        }

        DB::table('menu')->insert($processed);
    }
    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu');
    }
}
