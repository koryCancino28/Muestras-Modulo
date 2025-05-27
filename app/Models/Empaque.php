<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Empaque extends Model
{
    use HasFactory;

    protected $table = 'empaques';

    protected $fillable = [
        'nombre',
        'tipo',
        'precio',
        'estado',
        'cantidad',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'estado' => 'boolean',
    ];

    /**
     * Creador del registro.
     */
    public function creador()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Última persona que actualizó.
     */
    public function actualizador()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Productos finales donde se usa este empaque.
     */
    public function bases()
    {
        return $this->belongsToMany(Base::class)
            ->withPivot('cantidad')
            ->withTimestamps();
    }

    /**
     * Filtro por tipo de empaque.
     */
    public function scopeMaterial($query)
    {
        return $query->where('tipo', 'material');
    }

    public function scopeEnvase($query)
    {
        return $query->where('tipo', 'envase');
    }
}
