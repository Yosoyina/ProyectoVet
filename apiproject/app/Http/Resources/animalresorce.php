<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class animalresorce extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */


    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'nombre' => $this->nombre,
            'tipo' => $this->tipo,
            'peso' => $this->peso,
            'enfermedad' => $this->enfermedad,
            'comentarios' => $this->comentarios,
            'dueno_id' => $this->dueno_id,
        ];
    }
}

