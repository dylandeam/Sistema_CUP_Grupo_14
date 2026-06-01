<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Docente extends Model
{
    use HasFactory;

    protected $table = 'docentes';

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
        'estado',   // <- importante para el select
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }

    // Relación con requisitos del docente
    public function requisitos()
    {
        return $this->hasMany(Requisitos_Docente::class, 'docente_id');
    }

    // Relación muchos a muchos con materias (tabla pivote docente_materia)
    public function materias()
    {
        return $this->belongsToMany(Materia::class, 'docente_materia', 'docente_id', 'materia_id');
    }
}
