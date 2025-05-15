<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('volumenes', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('clasificacion_id')->constrained('clasificaciones')->onDelete('cascade'); // Relación con presentacion_farmaceutica
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('volumenes');
    }
};
