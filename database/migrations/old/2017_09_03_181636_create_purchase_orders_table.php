<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePurchaseOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('manager_id')->unsigned();
            $table->integer('vendor_id')->unsigned();
            $table->string('number')->unique();
            $table->integer('currency_id')->unsigned();
            $table->string('reference')->nullable();
            $table->integer('paymentcondition_id')->nullable();
            $table->integer('deliverycondition_id')->nullable();
            $table->double('exchangerate')->nullable();
            $table->text('delivery_time')->nullable();
            $table->date('date');
            $table->double('total');
            $table->double('subtotal');
            $table->double('totaltax');
            $table->text('terms')->nullable();
            $table->tinyInteger('status_id')->default('1');
            $table->tinyInteger('vat_status')->nullable();
            $table->boolean('is_received')->default(0);
            $table->string('created_by')->nullable();
            $table->date('request_date')->nullable();
            $table->date('confirmed_date')->nullable();
            $table->date('declined_date')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_order_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_order_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->string('vendor_reference');
            $table->float('qty');
            $table->integer('uom_id')->nullable();
            $table->text('uom_unit')->nullable();
            $table->float('qty_received')->default(0);
            $table->double('unit_price');
            $table->text('tax_name')->nullable();
            $table->double('tax_rate')->nullable();
            $table->timestamps();
        });

        Schema::create('purchase_order_item_taxes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('purchase_order_item_id')->unsigned();
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
        Schema::dropIfExists('purchase_orders');
        Schema::dropIfExists('purchase_order_items');
    }
}
