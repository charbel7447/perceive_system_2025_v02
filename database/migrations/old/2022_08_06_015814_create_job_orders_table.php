<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->integer('manager_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->string('number')->unique();
            $table->integer('currency_id')->unsigned();
            $table->integer('machine_id')->nullable();
            $table->integer('sales_order_id')->nullable();
            $table->string('reference')->nullable();
            $table->date('date');
            $table->string('description')->nullable();
            $table->string('year_date')->nullable();
            $table->date('due_date')->nullable();
            $table->text('delivery_date')->nullable();
            $table->text('comment')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->double('sales_order_qty')->nullable();
            $table->double('job_order_qty')->nullable();
            $table->integer('finished_product_id')->nullable();
            $table->integer('product_type_id')->nullable();
            $table->integer('uom_id')->nullable();
            $table->string('created_by')->nullable();
            $table->string('vat_status')->nullable();
            $table->date('request_date')->nullable();
            $table->date('confirmed_date')->nullable();
            $table->date('declined_date')->nullable();
            $table->tinyInteger('status_id')->default('1');
            $table->timestamps();
        });

        Schema::create('job_order_attributes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('job_order_id')->nullable();
            $table->integer('attribute_id')->nullable();
            $table->timestamps();
        });

        Schema::create('job_order_raw_materials', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('job_order_id')->nullable();
            $table->integer('raw_material_id')->nullable();
            $table->timestamp('production_date')->nullable();
            $table->timestamps();
        });

        Schema::create('job_order_machines', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('job_order_id')->nullable();
            $table->integer('machine_id')->nullable();
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
        Schema::dropIfExists('job_orders');
    }
}
