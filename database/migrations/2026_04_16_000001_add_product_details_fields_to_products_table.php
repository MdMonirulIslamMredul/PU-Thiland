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
        Schema::table('products', function (Blueprint $table) {
            $table->string('grade')->nullable()->after('price');
            $table->text('specification')->nullable()->after('grade');
            $table->decimal('open_price', 10, 2)->nullable()->after('specification');
            $table->decimal('quantity', 10, 2)->nullable()->after('open_price');
            $table->enum('unit_type', ['piece', 'weight'])->nullable()->after('quantity');
            $table->string('unit_name')->nullable()->after('unit_type');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('products', function (Blueprint $table) {
            $table->dropColumn([
                'grade',
                'specification',
                'open_price',
                'quantity',
                'unit_type',
                'unit_name',
            ]);
        });
    }
};
