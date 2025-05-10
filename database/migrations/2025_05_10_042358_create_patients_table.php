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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->string('image');
            $table->string('patient_number')->unique();
            $table->string('name');
            $table->date('date_of_birth');
            $table->enum('gender', ['Male', 'Female'])->nullable();
            $table->string('email');
            $table->string('phone_No');
            $table->string('address');
            $table->date('registration_date');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
