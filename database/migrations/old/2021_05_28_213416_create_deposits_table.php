<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDepositsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('deposits', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->unique();
            $table->integer('employee_id')->nullable();
            $table->integer('currency_id')->nullable();
            $table->integer('to_account_id')->nullable();
            $table->double('amount')->nullable();
            $table->double('exchangerate')->nullable();
            $table->date('deposit_date');
            $table->string('year_date')->nullable();
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
        Schema::dropIfExists('deposits');
    }
}
