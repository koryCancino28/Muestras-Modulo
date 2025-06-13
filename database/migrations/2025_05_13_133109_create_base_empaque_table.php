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
        Schema::create('base_empaque', function (Blueprint $table) {
            $table->id();
            $table->foreignId('base_id')->constrained('base')->onDelete('cascade');
            $table->foreignId('empaque_id')->constrained('empaques')->onDelete('cascade');
            $table->decimal('cantidad', 8, 4); // Cuánto se usa de ese empaque en esa base
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('base_empaque');
    }
};
