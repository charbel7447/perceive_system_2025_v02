<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_payments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('number')->unique();
            $table->integer('vendor_id')->unsigned();
            $table->integer('currency_id')->unsigned();
            $table->double('amount_paid')->nullable();
            $table->double('amount_paid_usd')->nullable();
            $table->double('amount_paid_lbp')->nullable();
            $table->double('amount_paid_lbprate')->nullable();
            $table->date('payment_date');
            $table->string('payment_mode');
            $table->string('payment_reference')->nullable();
            $table->string('document')->nullable();
            $table->integer('status_id')->default('1');
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('vendor_payment_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_payment_id')->unsigned();
            $table->integer('bill_id')->unsigned();
            $table->double('amount_applied')->nullable()->default('0');
            $table->double('amount_applied_lbp')->nullable()->default('0');
            $table->double('amount_applied_lbp_rate')->nullable()->default('1');
            $table->double('amount_applied_vat')->nullable()->default('0');
            $table->double('amount_applied_vat_rate')->nullable()->default('1');
            $table->double('vat_paid')->nullable()->default('0');
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
        Schema::dropIfExists('vendor_payments');
        Schema::dropIfExists('vendor_payment_items');
    }
}
