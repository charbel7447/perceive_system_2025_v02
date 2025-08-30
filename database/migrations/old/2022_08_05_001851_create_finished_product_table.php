<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFinishedProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finished_product', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('code')->unique();
            $table->string('generated_code')->unique();
            $table->text('description');
            $table->text('barcode')->nullable();
            $table->text('status')->nullable();
            $table->double('unit_price')->nullable();
            $table->double('minimum_stock')->default(0)->nullable();
            $table->boolean('has_inventory')->default(0);
            $table->double('qty_on_hand')->default(0);
            $table->integer('uom_id');
            $table->string('warehouse')->nullable()->default(1);
            $table->integer('type_id')->nullable();
            $table->integer('product_type')->nullable();
            $table->double('qty')->default(0)->nullable();
            $table->integer('currency_id')->unsigned();
            $table->integer('company')->default(1);
            $table->text('comment')->nullable();
            $table->string('created_by')->nullable();
            $table->string('document')->nullable();
            $table->timestamps();
        });

        Schema::create('finished_product_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('client_id')->unsigned();
            $table->string('reference');
            $table->double('price');
            // $table->string('tax_name')->nullable();
            // $table->float('tax_rate')->nullable();
            $table->integer('currency_id')->unsigned();
            $table->timestamps();
        });

        Schema::create('finished_product_taxes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->string('name');
            $table->float('rate');
            $table->string('tax_authority');
            $table->timestamps();
        });

        Schema::create('finished_product_attributes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('attribute_id')->nullable();
            $table->text('attribute_value')->nullable();
            $table->timestamps();
        });

        Schema::create('finished_product_materials', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('material_id')->nullable();
            $table->string('percentage')->nullable();
            $table->timestamps();
        });

        Schema::create('finished_product_machines', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('machine_process_id')->nullable();
            $table->integer('product_id')->unsigned();
            $table->double('speed')->nullable();
            $table->integer('machine_id')->nullable();
            $table->string('machine')->nullable();
            $table->text('comment')->nullable();
            $table->timestamps();
        });

        Schema::create('finished_product_machines_settings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('machines_id')->unsigned();
            $table->integer('settings_id')->nullable();
            $table->text('settings_name')->nullable();
            $table->text('settings_value')->nullable();
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
        Schema::dropIfExists('finished_product');
        Schema::dropIfExists('finished_product_items');
        Schema::dropIfExists('finished_product_taxes');
        Schema::dropIfExists('finished_product_attributes');
    }
}
