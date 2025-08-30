<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDebitNoteReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('debit_note_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('product_id')->nullable();
            $table->integer('client_id')->nullable();
            $table->timestamp('from_date')->nullable();
            $table->timestamp('to_date')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('debit_note_report_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('report_id')->nullable();
            $table->text('debit_note_id')->nullable();
            $table->timestamp('debit_note_date')->nullable();
            $table->text('product_id')->nullable();
            $table->double('qty')->nullable();
            $table->double('qty_received')->nullable();
            $table->double('amount_received')->nullable();
            $table->double('amount_applied')->nullable();
            $table->double('amount_applied_lbp')->nullable();
            $table->double('amount_applied_vat')->nullable();
            $table->double('invoice_id')->nullable();
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
        Schema::dropIfExists('debit_note_report');
    }
}
