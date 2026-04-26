<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('warehouse_stock_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('warehouse_inventory_item_id')->constrained('warehouse_inventory_items')->cascadeOnDelete();
            $table->enum('movement_type', ['in', 'out']);
            $table->decimal('quantity_kg', 16, 3);
            $table->decimal('unit_cost', 16, 4)->default(0);
            $table->decimal('total_cost', 18, 4)->default(0);
            $table->string('reference_type')->nullable();
            $table->unsignedBigInteger('reference_id')->nullable();
            $table->string('note')->nullable();
            $table->timestamps();

            $table->index(['reference_type', 'reference_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_stock_movements');
    }
};
