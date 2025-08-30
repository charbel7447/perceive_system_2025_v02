<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSalesOrderReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sales_order_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->timestamp('from_date')->nullable();
            $table->timestamp('to_date')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('sales_order_report_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('report_id')->nullable();
            $table->text('sales_order_id')->nullable();
            $table->timestamp('sales_order_date')->nullable();
            $table->text('product_id')->nullable();
            $table->double('qty')->nullable();
            $table->double('qty_received')->nullable();
            $table->double('total')->nullable();
            $table->double('unit_price')->nullable();
            $table->integer('status_id')->nullable();
            $table->integer('uom_id')->nullable();
            $table->text('client_id')->nullable();
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
        Schema::dropIfExists('sales_order_report');
    }
}
