<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class  CreateCreditNotesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credit_notes', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('number')->unique();
            $table->integer('client_id')->unsigned();
            $table->integer('currency_id')->unsigned();
            $table->integer('quotation_id')->nullable();
            $table->double('amount_received')->nullable();
            $table->double('amount_received_lbp')->nullable();
            $table->double('exchangerate')->nullable();
            $table->date('payment_date');
            $table->date('applied_date')->nullable();
            $table->double('applied_amount')->nullable();
            $table->string('payment_mode');
            $table->string('payment_reference')->nullable();
            $table->string('created_by')->nullable();
            $table->text('description');
            $table->string('document')->nullable();
            $table->integer('status_id')->default('1');
            $table->timestamps();
        });

        Schema::create('credit_notes_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('credit_notes_id')->unsigned();
            $table->integer('invoice_id')->unsigned();
            $table->double('amount_applied');
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
        Schema::dropIfExists('credit_notes');
        Schema::dropIfExists('credit_notes_items');
    }
}
