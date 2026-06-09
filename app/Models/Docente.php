<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docentes';
    protected $primaryKey = 'codigo';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'usuario_id',
        'codigo',
        'nombre',
        'apellido',
        'ci',
        'fecha_nacimiento',
        'telefono',
        'direccion',
        'foto',
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    public function getRouteKeyName()
    {
        return 'codigo';
    }

    // Relación con requisitos del docente
    public function requisitos()
    {
        return $this->hasMany(Requisitos_Docente::class, 'docente_codigo', 'codigo');
    }

    // Relación muchos a muchos con materias (tabla pivote docente_materia)
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'docente_materia', 'codigo_docente', 'materia_id', 'codigo', 'id')
                    ->withPivot('estado');
    }

    public function asignaciones()
    {
        return $this->hasMany(Docente_Materia::class, 'codigo_docente', 'codigo');
    }

    // Relación con carga horaria
    public function cargaHoraria()
    {
        return $this->hasMany(CargaHoraria::class, 'docente_codigo', 'codigo');
    }

    public function getEstadoAsignacionAttribute()
    {
        if ($this->relationLoaded('asignaciones')) {
            return optional($this->asignaciones->first())->estado;
        }

        return $this->asignaciones()->value('estado');
    }
}
