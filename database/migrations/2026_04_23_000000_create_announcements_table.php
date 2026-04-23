<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('announcements', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('short_description')->nullable();
            $table->text('body')->nullable();
            $table->string('image')->nullable();
            $table->string('attachment')->nullable();
            $table->string('type')->default('announcement');
            $table->string('priority')->default('normal');
            $table->string('status')->default('draft');
            $table->timestamp('publish_date')->nullable();
            $table->timestamp('expiry_date')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('announcements');
    }
};
