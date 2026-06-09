<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisitos_Docente extends Model
{
    use HasFactory;

    protected $table = 'requisitos_docente';

    protected $fillable = [
        'docente_codigo',
        'nombre_titulo',
        'nombre_maestria',
        'nombre_diplomado',

    ];

    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_codigo', 'codigo');
    }
} 