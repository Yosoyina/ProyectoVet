<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class animales extends Model
{
    use HasFactory;

    //Tabla de la base de datos
    protected $table = 'animales';

    // campos de la tabla
    protected $fillable = [
        'nombre',
        'tipo',
        'peso',
        'enfermedad',
        'comentarios',
        'dueno_id',
    ];

    // Un animal pertenece a un dueño
    public function dueno()
    {
        return $this->belongsTo(duenos::class);
    }
}
