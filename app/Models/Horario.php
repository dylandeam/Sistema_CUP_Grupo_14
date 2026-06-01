<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios'; // nombre de la tabla
    protected $primaryKey = 'id'; // llave primaria id
    protected $fillable = [
        'hora_inicio',
        'hora_fin',
    ];
}