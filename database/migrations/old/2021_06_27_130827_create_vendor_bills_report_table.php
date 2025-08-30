<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorBillsReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_bills_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->timestamp('from_date')->nullable();
            $table->timestamp('to_date')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('vendor_bills_report_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('report_id')->nullable();
            $table->integer('bill_id')->nullable();
            $table->integer('purchase_order_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->double('amount_paid')->nullable();
            $table->integer('status_id')->nullable();
            
            $table->double('total')->nullable();
            $table->double('exchangerate')->nullable();
            $table->timestamp('bill_date')->nullable();
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
        Schema::dropIfExists('vendor_bills_report');
    }
}
