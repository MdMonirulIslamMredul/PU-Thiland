<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recharge_orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('payment_gateway_id')->constrained()->cascadeOnDelete();
            $table->decimal('amount', 12, 2);
            $table->string('status')->default('pending');
            $table->string('payment_method');
            $table->string('payment_proof')->nullable();
            $table->text('admin_note')->nullable();
            $table->foreignId('approved_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('approved_at')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('recharge_orders');
    }
};
