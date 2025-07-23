<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('sales', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id');
            $table->string('name');
            $table->text('description')->nullable();
            $table->decimal('discount_percent', 5, 2)->nullable(); // VD: 5.00, 10.00
            $table->decimal('discount_amount', 15, 2)->nullable(); // Nếu giảm giá cố định
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('shops')->onDelete('cascade');
        });

        // Bảng pivot: sale_product (nhiều sản phẩm cho 1 sale)
        Schema::create('sale_product', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('sale_id');
            $table->unsignedBigInteger('product_id');
            $table->timestamps();

            $table->foreign('sale_id')->references('id')->on('sales')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');
            $table->unique(['sale_id', 'product_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sale_product');
        Schema::dropIfExists('sales');
    }
};
