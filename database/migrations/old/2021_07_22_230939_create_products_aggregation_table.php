<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsAggregationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_aggregation', function (Blueprint $table) {
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
            $table->integer('category_id');
            $table->integer('sub_category_id');
            $table->integer('sub_sub_category_id')->nullable();
            $table->double('qty')->default(0)->nullable();
            $table->integer('currency_id')->unsigned();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('product_aggregation_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('product_aggregation_id')->unsigned();
            $table->integer('product_id')->unsigned();
            $table->text('product_code')->nullable();
            $table->text('product_name')->nullable();
            $table->double('product_price')->nullable();
            $table->integer('qty_on_hand')->unsigned();
            $table->string('uom_id');
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
        Schema::dropIfExists('products_aggregation');
        Schema::dropIfExists('product_aggregation_items');
    }
}
