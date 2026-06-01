<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Materia extends Model
{
    use HasFactory;

    protected $table = 'materias'; // Nombre de la tabla en la base de datos
    protected $primaryKey = 'id';

    protected $fillable = [
        'nombre',
        'ponderacion',
        'area', // <- importante para validar afinidad con el área del docente
    ];

    // Relación muchos a muchos con docentes
    public function docentes()
    {
        return $this->belongsToMany(Docente::class, 'docente_materia', 'materia_id', 'docente_id');
    }
}
