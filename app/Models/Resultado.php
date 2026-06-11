<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Inscripcion;
use App\Models\Inscripcion_Carrera;

class Resultado extends Model
{
    use HasFactory;

    protected $table = 'resultados';

    protected $fillable = [
        'estado',
        'promedio_examen',
        'id_inscripcion',
        'id_inscripcion_carrera',
    ];

    /**
     * Relación con Inscripcion
     */
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion');
    }

    /**
     * Relación con Inscripcion_Carrera
     */
    public function inscripcionCarrera()
    {
        return $this->belongsTo(Inscripcion_Carrera::class, 'id_inscripcion_carrera');
    }
}
