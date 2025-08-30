<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable();
            $table->integer('manager_id')->nullable();
            $table->string('number')->nullable(); 
            $table->string('document_type')->nullable();
            $table->string('description')->nullable();
            $table->string('link')->nullable();
            $table->string('document_number')->nullable();
            $table->integer('document_id')->nullable();
            $table->integer('qty')->nullable();
            $table->timestamp('date')->nullable();
            $table->integer('viewed')->nullable();
            $table->string('status')->nullable();
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
        Schema::dropIfExists('notifications');
    }
}
