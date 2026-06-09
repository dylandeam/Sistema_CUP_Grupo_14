<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CargaHoraria extends Model
{
    use HasFactory;

    protected $table = 'carga_horarias';

    protected $fillable = [
        'docente_codigo',
        'materia_id',
        'grupo_id',
        'horario_id',
        'aula_id',
        'gestion_id',
    ];

    // Relaciones
    public function docente()
    {
        return $this->belongsTo(Docente::class, 'docente_codigo', 'codigo');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'materia_id');
    }

    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id');
    }

    public function horario()
    {
        return $this->belongsTo(Horario::class, 'horario_id');
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'aula_id');
    }

    public function gestion()
    {
        return $this->belongsTo(Gestion::class, 'gestion_id');
    }
}
