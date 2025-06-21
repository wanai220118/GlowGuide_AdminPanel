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
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->enum('specialist', ['Aesthetic Doctor', 'Dermatologist', 'Esthetician'])->nullable();
            $table->json('working_days')->nullable(); // Changed from 'day' to 'working_days'
            $table->boolean('slot1')->default(false);
            $table->boolean('slot2')->default(false);
            $table->boolean('slot3')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('staff');
    }
};
