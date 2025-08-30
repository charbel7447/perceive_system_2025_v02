<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorPaymentsReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_payments_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->double('amount_applied')->nullable();
            $table->double('amount_applied_lbp')->nullable();
            $table->double('exchangerate')->nullable();
            $table->timestamp('from_date')->nullable();
            $table->timestamp('to_date')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('vendor_payments_report_items', function(Blueprint $table) {
            $table->increments('id'); 
            $table->integer('report_id')->nullable();
            $table->integer('vendor_payment_id')->nullable();
            $table->integer('bill_id')->nullable();
            $table->integer('vendor_id')->nullable();
            $table->double('amount_applied')->nullable();
            $table->double('amount_applied_lbp')->nullable();
            $table->double('amount_applied_lbp_rate')->nullable();
            $table->double('amount_applied_vat')->nullable();
            $table->double('amount_applied_vat_rate')->nullable();
            $table->text('payment_mode')->nullable();
            $table->timestamp('payment_date')->nullable();
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
        Schema::dropIfExists('vendor_payments_report');
    }
}
