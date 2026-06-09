<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Turno;

class Grupo extends Model
{
    use HasFactory;

    protected $table = 'grupos';

    protected $fillable = [
        'nombre',
        'cupos',
        'id_gestion',
        'id_turno',
        'id_modalidad',
        'id_aula',
    ];

    // Relaciones
    public function gestion()
    {
        return $this->belongsTo(Gestion::class, 'id_gestion');
    }

    public function turno()
    {
        return $this->belongsTo(Turno::class, 'id_turno');
    }

    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'id_modalidad');
    }

    public function aula()
    {
        return $this->belongsTo(Aula::class, 'id_aula');
    }

}
