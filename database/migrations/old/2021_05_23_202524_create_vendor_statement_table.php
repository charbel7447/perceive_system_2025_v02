<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVendorStatementTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_statement', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id')->nullable();
            $table->text('person')->nullable();
            $table->text('company')->nullable();
            $table->date('date')->nullable();
            $table->date('due_date')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('vendor_statement_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('vendor_id')->nullable();
            $table->integer('statement_id');
            $table->text('reference_number')->nullable();
            $table->integer('reference_id')->nullable();
            $table->double('amount_applied')->nullable();
            $table->text('type')->nullable();
            $table->date('reference_date')->nullable();
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
        Schema::dropIfExists('vendor_statement');
    }
}
