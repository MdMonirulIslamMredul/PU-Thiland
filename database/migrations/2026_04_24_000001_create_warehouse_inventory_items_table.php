<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('warehouse_inventory_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->cascadeOnDelete();
            $table->string('grade')->nullable();
            $table->string('specification')->nullable();
            $table->decimal('quantity_kg', 16, 3)->default(0);
            $table->decimal('avg_cost', 16, 4)->default(0);
            $table->timestamps();

            $table->unique(['product_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('warehouse_inventory_items');
    }
};
