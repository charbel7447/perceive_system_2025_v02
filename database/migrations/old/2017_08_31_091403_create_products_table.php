<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->unsigned();
            $table->string('code')->unique();
            $table->text('description');
            $table->text('barcode')->nullable();
            $table->text('status')->nullable();
            $table->double('unit_price')->nullable();
            $table->double('minimum_stock')->default(0)->nullable();
            $table->boolean('has_inventory')->default(0);
            $table->double('qty_on_hand')->default(0);
            $table->integer('uom_id');
            $table->integer('warehouse_id')->nullable()->default(1);
            $table->integer('raw_material_type_id')->nullable();
            $table->integer('category_id');
            $table->integer('product_type')->nullable();
            $table->integer('sub_category_id');
            $table->integer('sub_sub_category_id')->nullable();
            $table->double('qty')->default(0)->nullable();
            $table->integer('company')->default(1);
            $table->integer('currency_id')->unsigned();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('product_items', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('vendor_id')->unsigned();
            $table->string('reference');
            $table->double('price');
            // $table->string('tax_name')->nullable();
            // $table->float('tax_rate')->nullable();
            $table->integer('currency_id')->unsigned();
            $table->timestamps();
        });

        Schema::create('product_taxes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->string('name');
            $table->float('rate');
            $table->string('tax_authority');
            $table->timestamps();
        });

        Schema::create('product_attributes', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('product_id')->unsigned();
            $table->integer('attribute_id')->nullable();
            $table->text('attribute_value')->nullable();
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
        Schema::dropIfExists('products');
        Schema::dropIfExists('product_items');
        Schema::dropIfExists('product_taxes');
    }
}
