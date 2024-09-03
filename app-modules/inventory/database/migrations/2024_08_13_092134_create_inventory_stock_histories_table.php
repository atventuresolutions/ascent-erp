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
        Schema::create('inventory_stock_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('inventory_stock_id')->constrained('inventory_stocks')->onDelete('cascade');
            $table->integer('quantity');
            $table->string('type');
            $table->string('system', 15)
                  ->comment('The system that made the change. E.g. pos, inventory, etc.');
            $table->string('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('inventory_stock_histories');
    }
};
