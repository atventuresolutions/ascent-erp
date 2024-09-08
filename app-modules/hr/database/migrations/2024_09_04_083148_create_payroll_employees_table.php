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
        Schema::create('payroll_employees', function (Blueprint $table) {
            $table->id();
            $table->foreignId('payroll_id')->constrained('payrolls')->cascadeOnDelete();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->decimal('basic_pay', 10, 2);
            $table->decimal('overtime_pay', 10, 2);
            $table->decimal('holiday_pay', 10, 2);
            $table->decimal('total_deductions', 10, 2);
            $table->decimal('total_additions', 10, 2);
            $table->decimal('net_pay', 10, 2);
            $table->longText('notes')->nullable();
            $table->json('data')->comment("stores complete payroll data for employee");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('payroll_employees');
    }
};
