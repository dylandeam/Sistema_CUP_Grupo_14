<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Examen extends Model
{
    use HasFactory;

    // Nombre de la tabla
    protected $table = 'examenes';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'nro_examen',
        'fecha',
        'ponderacion',
        'gestion_id',
    ];

    /**
     * Relación: Un examen pertenece a una gestión
     */
    public function gestion()
    {
        return $this->belongsTo(Gestion::class, 'gestion_id');
    }
}
