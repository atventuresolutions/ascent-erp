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
        Schema::create('compensation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->decimal('daily_rate', 10);
            $table->integer('daily_working_hours');

            // multipliers
            $table->double('overtime_multiplier');
            $table->double('holiday_multiplier');
            $table->double('special_holiday_multiplier');

            // shift timings
            $table->time('shift_start_time');
            $table->time('shift_end_time');
            $table->time('break_start_time');
            $table->time('break_end_time');
            $table->integer('late_grace_period');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('compensation');
    }
};
