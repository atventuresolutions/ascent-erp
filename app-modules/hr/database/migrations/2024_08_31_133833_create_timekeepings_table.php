<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('timekeepings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained('employees')->cascadeOnDelete();
            $table->date('date');
            $table->time('first_time_in');
            $table->time('first_time_out');
            $table->time('break_start_time')->comment('System generated, from compensation table.');
            $table->time('break_end_time')->comment('System generated, from compensation table.');
            $table->time('second_time_in');
            $table->time('second_time_out');
            $table->double('total_rendered')->comment('System generated, in minutes not hours.');
            $table->double('total_overtime')->comment('System generated, in minutes not hours.');
            $table->double('total_late')->comment('System generated, in minutes not hours.');
            $table->double('total_undertime')->comment('System generated, in minutes not hours.');
            $table->enum('status', ['PENDING', 'APPROVED']);
            $table->longText('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('timekeepings');
    }
};
