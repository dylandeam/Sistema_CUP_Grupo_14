<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aula extends Model
{
    use HasFactory;

    protected $table = 'aulas'; // nombre de la tabla
    protected $primaryKey = 'id'; // llave primaria id
    protected $fillable = [
        'modulo',
        'nro_aula',
        'piso',
    ];
}