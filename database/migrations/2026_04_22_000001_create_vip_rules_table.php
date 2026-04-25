<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('vip_rules', function (Blueprint $table) {
            $table->id();
            $table->string('level_name')->unique();
            $table->decimal('discount_per_kg', 8, 2)->default(0);
            $table->decimal('min_sales_kg', 10, 2)->default(0);
            $table->decimal('max_sales_kg', 10, 2)->nullable();
            $table->decimal('min_recharge_amount', 12, 2)->default(0);
            $table->integer('priority')->default(0);
            $table->boolean('is_active')->default(true);
            $table->integer('expiry_days')->default(30);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('vip_rules');
    }
};
