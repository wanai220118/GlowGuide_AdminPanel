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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('product_number')->unique();
            $table->string('image');
            $table->string('name');
            $table->string('category');
            $table->text('description');
            $table->float('price');
            $table->integer('qty');
            $table->date('expiry_date')->nullable();
            $table->string('inventory');
            // $table->date('updated_at');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
