<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAttributesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attributes', function (Blueprint $table) {
            $table->increments('id');
            $table->text('description')->nullable();
            $table->string('number')->unique();
            $table->integer('user_id')->nullable();
            $table->text('created_by')->nullable();
            $table->timestamps();
        });

        Schema::create('attributes_value', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('attribute_id')->unsigned();
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
        Schema::dropIfExists('attributes');
    }
}
