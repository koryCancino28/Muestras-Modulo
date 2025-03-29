<?php

namespace App\Events;

use App\Models\Muestras;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class MuestraActualizada implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $muestra;

    public function __construct(Muestras $muestra)
    {
        // Cargamos las relaciones necesarias
        $this->muestra = $muestra->load(['clasificacion.unidadMedida']);
    }

    /**
     * El canal en el que se transmitirá el evento.
     */
    public function broadcastOn()
    {
        return new Channel('muestras');
    }

    /**
     * El nombre del evento que se emitirá.
     */
    public function broadcastAs()
    {
        return 'muestra.actualizada';
    }

    /**
     * Datos a emitir con el evento.
     */
    public function broadcastWith(): array
    {
        return [
            'muestra' => [
                'id' => $this->muestra->id,
                'nombre_muestra' => $this->muestra->nombre_muestra,
                'clasificacion' => $this->muestra->clasificacion ? $this->muestra->clasificacion->nombre_clasificacion : null,
                'unidad_de_medida' => $this->muestra->clasificacion && $this->muestra->clasificacion->unidadMedida 
                    ? $this->muestra->clasificacion->unidadMedida->nombre_unidad_de_medida 
                    : null,
                'tipo_muestra' => $this->muestra->tipo_muestra,
                'cantidad_de_muestra' => $this->muestra->cantidad_de_muestra,
                'estado' => $this->muestra->estado,
                'observacion' => $this->muestra->observacion,
                'fecha_actualizacion' => $this->muestra->updated_at->format('Y-m-d H:i:s'),
                'aprobado_jefe_comercial' => $this->muestra->aprobado_jefe_comercial,
                'aprobado_coordinadora' => $this->muestra->aprobado_coordinadora 
            ]
        ];
    }
}