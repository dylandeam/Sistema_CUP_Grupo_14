<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Asistencia extends Model
{
    use HasFactory;

    protected $table = 'asistencias';

    protected $fillable = [
        'fecha',
        'estado',
        'codigo_postulante',
        'id_grupo',
    ];

    /**
     * Relación con Postulante
     */
    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'codigo_postulante', 'codigo');
    }

    /**
     * Relación con Grupo
     */
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'id_grupo');
    }
}
