<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisitos_Docente extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'requisitos_docente';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'docente_id',
        'titulo',
        'nombre_titulo',
        'maestria',
        'nombre_maestria',
        'diplomado',
        'nombre_diplomado',
        'area_especialidad',
    ];

    /**
     * Relación con el modelo Docente
     */
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id');
    }
}

