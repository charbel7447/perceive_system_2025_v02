<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsDivisionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products_division', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('product_id');
            $table->text('code');
            $table->integer('category_id')->nullable();
            $table->integer('warehouse_id')->nullable();
            $table->integer('uom_id')->nullable();
            $table->integer('to_uom_id')->nullable();
            $table->double('qty_on_hand')->nullable();
            $table->double('to_qty_on_hand')->nullable();
            
            $table->text('created_by');
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
        Schema::dropIfExists('products_division');
    }
}
