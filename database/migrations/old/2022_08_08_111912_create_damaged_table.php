<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDamagedTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('damaged', function (Blueprint $table) {
            $table->increments('id');
            $table->string('number')->nullable(); 
            $table->string('code')->nullable();
            $table->string('description')->nullable();
            $table->integer('qty')->nullable();
            $table->date('date')->nullable();
            $table->string('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('damaged_items', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('damaged_id')->nullable(); 
            $table->integer('product_id')->nullable();
            $table->integer('transfer_qty')->nullable();
            $table->integer('uom_id')->nullable();
            $table->date('date')->nullable();
            $table->string('created_by')->nullable();
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
        Schema::dropIfExists('damaged');
    }
}
