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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->string('code', 9)->unique();
            $table->string('firstname');
            $table->string('lastname');
            $table->string('email');
            $table->string('mobile_number');
            $table->string('telephone_number');
            $table->string('address');
            $table->date('birthday');
            $table->string('emergency_contact_name');
            $table->string('emergency_contact_number');
            $table->string('emergency_contact_relationship');
            $table->string('job_title');
            $table->string('department');
            $table->string('employment_status');
            $table->string('date_hired');
            $table->string('date_regularized')->nullable();
            $table->string('date_resigned')->nullable();
            $table->string('date_terminated')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
