<?php

// database/migrations/2025_08_19_000002_create_stock_count_products_table.php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('stock_count_products', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('stock_count_id');
            $table->unsignedBigInteger('product_id');
            $table->string('code')->nullable();
            $table->unsignedBigInteger('category_id')->nullable();
            $table->unsignedBigInteger('sub_category_id')->nullable();
            $table->unsignedBigInteger('sub_sub_category_id')->nullable();
            $table->unsignedBigInteger('uom_id')->nullable();
            $table->integer('current_stock')->default(0);
            $table->integer('inventoried_stock')->default(0);
            $table->timestamp('scanned_at')->nullable();
            $table->timestamps();

            $table->foreign('stock_count_id')->references('id')->on('stock_counts')->onDelete('cascade');
        });
    }

    public function down(): void {
        Schema::dropIfExists('stock_count_products');
    }
};
