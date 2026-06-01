<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente_Materia extends Model
{
    use HasFactory;

    // Nombre de la tabla intermedia
    protected $table = 'docente_materia';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'docente_id',
        'materia_id',
        'estado',
    ];

    /**
     * Relación con el modelo Docente
     */
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_id');
    }

    /**
     * Relación con el modelo Materia
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }
}

