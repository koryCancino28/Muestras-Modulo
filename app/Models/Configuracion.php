<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Configuracion extends Model
{
    use HasFactory;

    protected $table = 'configuraciones';

    protected $fillable = [
        'nombre',
        'valor',
        'descripcion',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'valor' => 'decimal:4',
    ];

    /**
     * Usuario que creó la configuración.
     */
    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Usuario que actualizó la configuración.
     */
    public function actualizador()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
