<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateReceiveOrderReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receive_orders_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->timestamp('from_date')->nullable();
            $table->integer('purchase_order_id')->nullable();
            $table->timestamp('to_date')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('receive_orders_report_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('report_id')->nullable();
            $table->text('receive_order_id')->nullable();
            $table->timestamp('receive_order_date')->nullable();
            $table->text('product_id')->nullable();
            $table->double('received_qty')->nullable();
            $table->integer('purchase_order_id')->nullable();
            $table->double('purchase_order_item_id')->nullable();
            $table->integer('uom_id')->nullable();
            $table->text('vendor_id')->nullable();
            $table->double('exchangerate')->nullable();
            $table->timestamp('from_date')->nullable();
            $table->timestamp('to_date')->nullable();
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
        Schema::dropIfExists('receive_order_report');
    }
}
