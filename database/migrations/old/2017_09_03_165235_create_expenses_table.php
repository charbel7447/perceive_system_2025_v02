<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateExpensesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('expenses', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('number')->unique();
            $table->integer('vendor_id')->unsigned();
            $table->integer('currency_id')->unsigned();
            $table->double('amount_paid')->nullable();
            $table->double('amount_paid_lbp')->nullable();
            $table->double('exchangerate')->nullable();
            $table->date('payment_date');
            $table->text('description')->nullable();
            $table->string('created_by')->nullable();
            $table->string('document')->nullable();
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
        Schema::dropIfExists('expenses');
    }
}
