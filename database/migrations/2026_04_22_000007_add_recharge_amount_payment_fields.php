<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->decimal('recharge_amount', 12, 2)->default(0)->after('vip_expiry_date');
        });

        Schema::table('orders', function (Blueprint $table) {
            $table->string('payment_method')->nullable()->after('vip_discount_amount');
            $table->decimal('recharge_used_amount', 10, 2)->default(0)->after('payment_method');
        });
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn(['payment_method', 'recharge_used_amount']);
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('recharge_amount');
        });
    }
};
