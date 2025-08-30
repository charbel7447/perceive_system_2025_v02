<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('person');
            $table->string('company')->nullable();
            $table->string('email')->nullable();
            $table->string('work_phone')->nullable();
            $table->string('mobile_number')->nullable();
            $table->string('vat_number')->nullable();
            $table->integer('vat_status')->nullable();
            $table->integer('currency_id')->unsigned();
            $table->text('billing_address')->nullable();
            $table->text('shipping_address')->nullable();
            $table->text('payment_details')->nullable();
            $table->double('total_expense')->default(0);
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('vendors');
    }
}
