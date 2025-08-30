<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMachinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('machines', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code')->nullable(); 
            $table->string('name')->nullable();
            $table->double('speed')->nullable();
            $table->timestamps();
        });

        Schema::create('machines_settings', function(Blueprint $table) {
            $table->increments('id');
            $table->integer('machines_id')->unsigned();
            $table->integer('settings_id')->nullable();
            $table->string('settings_name')->nullable();
            $table->string('settings_value')->nullable();
            $table->text('settings_comment')->nullable();
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
        Schema::dropIfExists('machines');
    }
}
