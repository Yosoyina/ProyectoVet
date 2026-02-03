<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class duenos extends Model
{
    use HasFactory;

    //Tabla de la base de datos
    protected $table = 'duenos';

    // campos de la tabla
    protected $fillable = [
        'nombre',
        'apellido',
    ];

    // Un dueño puede tener muchos animales
    public function animales()
    {
        return $this->hasMany(Animales::class);
    }
}
