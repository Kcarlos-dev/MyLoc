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
        Schema::create('menu__items', function (Blueprint $table) {
            $table->id("item_id");
            $table->string("name")->unique();
            $table->string("description");
            $table->decimal("price",10, 2);
            $table->string("category");
            $table->integer("stock_quantity");
            $table->boolean("is_available");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu__items');
    }
};
