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
        Schema::create('payrolls', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->date('start_date');
            $table->date('end_date');
            $table->decimal('basic_pay', 10, 2);
            $table->decimal('overtime_pay', 10, 2);
            $table->decimal('holiday_pay', 10, 2);
            $table->decimal('total_deductions', 10, 2);
            $table->decimal('total_additions', 10, 2);
            $table->decimal('net_pay', 10, 2);
            $table->enum('status', ['DRAFT', 'CANCELED', 'APPROVED', 'RELEASED']);
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payrolls');
    }
};
