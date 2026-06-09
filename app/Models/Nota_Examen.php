<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaExamen extends Model
{
    use HasFactory;

    protected $table = 'nota_examens';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nota_materia',
        'nota_ponderada',
        'id_examen',
        'id_materia',
        'id_inscripcion',
    ];

    /**
     * Relación con Examen
     */
    public function examen()
    {
        return $this->belongsTo(Examen::class, 'id_examen');
    }

    /**
     * Relación con Materia
     */
    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }

    /**
     * Relación con Inscripción
     */
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion');
    }
}
