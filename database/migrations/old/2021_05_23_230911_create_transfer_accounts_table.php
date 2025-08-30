<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransferAccountsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transfer_accounts', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->unique();
            $table->integer('from_account_id')->nullable();
            $table->integer('to_account_id')->nullable();
            $table->double('amount')->nullable();
            $table->double('exchangerate')->nullable();
            $table->date('transfer_date');
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
        Schema::dropIfExists('transfer_accounts');
    }
}
