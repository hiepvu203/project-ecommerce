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
        Schema::create('discount_codes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shop_id')->nullable(); // null = mã toàn sàn
            $table->string('code')->unique();
            $table->string('type')->default('amount'); // amount | percent | freeship
            $table->decimal('value', 15, 2)->nullable(); // số tiền hoặc % giảm
            $table->decimal('min_order_amount', 15, 2)->nullable();
            $table->integer('usage_limit')->nullable(); // tổng số lần sử dụng
            $table->integer('usage_per_user')->nullable(); // số lần mỗi user được dùng
            $table->timestamp('start_at');
            $table->timestamp('end_at');
            $table->boolean('active')->default(true);
            $table->timestamps();

            $table->foreign('shop_id')->references('id')->on('shops')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discount_codes');
    }
};
