<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('invoices', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('manager_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->string('number')->unique();
            $table->integer('currency_id')->unsigned();
            $table->double('exchangerate')->nullable();
            $table->string('reference')->nullable();
            $table->integer('invoiceable_id')->nullable();
            $table->string('invoiceable_type')->nullable();
            $table->date('date');
            $table->string('year_date')->nullable();
            $table->text('due_date')->nullable();
            $table->text('delivery_date')->nullable();
            $table->integer('paymentcondition_id')->nullable();
            $table->integer('deliverycondition_id')->nullable();
            $table->double('sub_total');
            $table->double('discount')->nullable();
            $table->double('shipping')->nullable();
            $table->string('created_by')->nullable();
            $table->double('total');
            $table->text('terms')->nullable();
            $table->tinyInteger('status_id')->default('1');
            $table->double('amount_paid')->default(0);
            $table->double('debit_amount')->nullable()->default(0);
            $table->double('credit_amount')->nullable()->default(0);
            $table->double('vat_paid')->nullable()->default(0);
            $table->string('vat_status')->nullable();
            $table->date('request_date')->nullable();
            $table->date('confirmed_date')->nullable();
            $table->date('declined_date')->nullable();
            $table->timestamps();
        });

        Schema::create('invoice_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->integer('uom_id')->nullable();
            $table->float('qty');
            $table->float('qty_on_hand')->nullable();
            $table->text('uom_unit')->nullable();
            $table->double('unit_price');
            $table->timestamps();
        });

        Schema::create('invoice_item_taxes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('invoice_item_id')->unsigned();
            $table->string('name');
            $table->float('rate');
            $table->string('tax_authority');
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
        Schema::dropIfExists('invoices');
        Schema::dropIfExists('invoice_items');
        Schema::dropIfExists('invoice_item_taxes');
    }
}
