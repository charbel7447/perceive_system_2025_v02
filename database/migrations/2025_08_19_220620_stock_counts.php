<?php

// database/migrations/2025_08_19_000001_create_stock_counts_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_counts', function (Blueprint $table) {
            $table->id();
            $table->date('count_date');
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->unsignedBigInteger('sub_sub_category_id')->nullable();
            $table->unsignedBigInteger('user_id');
            $table->string('created_by');
            $table->timestamps();
        });
    }

    public function down(): void {
        Schema::dropIfExists('stock_counts');
    }
};

