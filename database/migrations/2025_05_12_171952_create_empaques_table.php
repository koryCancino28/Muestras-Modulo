<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('empaques', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->enum('tipo', ['material', 'envase']); // Columna discriminadora
            $table->decimal('precio', 10, 2);
            $table->boolean('estado')->default(true); // true = con stock, false = sin stock
            $table->integer('cantidad');
            $table->text('descripcion')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->onDelete('set null');
            $table->foreignId('updated_by')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamps();
            $table->index('tipo'); // índice para búsquedas SELECT * FROM empaques WHERE tipo = 'material';
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('empaques');
    }
};
