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
            // $table->string('patient_number')->unique();
            $table->date('date_of_birth');
            $table->string('name');
            $table->string('email');
            $table->string('password');
            $table->string('phone_No');
            $table->enum('gender', ['Male', 'Female'])->after('name')->nullable();
            // $table->foreignId('owner_id')->constrained('owners')->cascadeOnDelete();
            // $table->string('type');
            //add conditionID & cartID
            $table->string('image');
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
