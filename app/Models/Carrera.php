<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Carrera extends Model
{
    use HasFactory;

    protected $table = 'carreras'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id';
    protected $fillable = [
        'sigla',
        'nombre',
        'cupos_disponibles'
    ];
}

