<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Gestion extends Model
{
    use HasFactory;

    protected $table = 'gestions'; // nombre de la tabla
    protected $primaryKey = 'id'; // llave primaria id
    protected $fillable = [
        'semestre',
        'año',
        'estado',
    ];
}