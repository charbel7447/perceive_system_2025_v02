<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEmployeeReportTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_report', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('employee_id')->nullable();
            $table->timestamp('from_date')->nullable();
            $table->timestamp('to_date')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('employee_report_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('report_id')->nullable();
            $table->integer('currency_id')->nullable();
            $table->text('type')->nullable();
            $table->text('number')->nullable();
            $table->double('amount_paid')->nullable();
            $table->double('amount_paid_lbp')->nullable();
            $table->double('exchangerate')->nullable();
            $table->integer('employee_id')->nullable();
            $table->integer('payroll_id')->nullable();
            $table->date('payroll_date')->nullable();
            
            $table->integer('deposit_id')->nullable();
            $table->date('deposit_date')->nullable();
            $table->integer('to_account_id')->nullable();
            $table->double('deposit_amount')->nullable();
            

            $table->integer('return_deposit_id')->nullable();
            $table->date('return_deposit_date')->nullable();
            $table->integer('from_account_id')->nullable();
            $table->double('return_deposit_amount')->nullable();

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
        Schema::dropIfExists('employee_report');
    }
}
