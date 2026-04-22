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
        Schema::create('user_addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->string('recipient_name');
            $table->string('phone');
            $table->text('address');
            $table->string('label')->nullable();
            $table->timestamps();
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->foreignId('user_address_id')->nullable()->constrained('user_addresses')->nullOnDelete()->after('note');
            $table->string('delivery_recipient_name')->after('user_address_id');
            $table->string('delivery_phone')->after('delivery_recipient_name');
            $table->text('delivery_address')->after('delivery_phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropForeign(['user_address_id']);
            $table->dropColumn(['user_address_id', 'delivery_recipient_name', 'delivery_phone', 'delivery_address']);
        });

        Schema::dropIfExists('user_addresses');
    }
};
