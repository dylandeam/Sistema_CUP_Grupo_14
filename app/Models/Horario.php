<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Horario extends Model
{
    use HasFactory;

    protected $table = 'horarios'; // nombre de la tabla

    protected $fillable = [
        'turno_id',
        'hora_inicio',
        'hora_fin',
    ];

    /**
     * Relación: un horario pertenece a un turno.
     */
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id');
    }

    public function getDescripcionAttribute(): string
    {
        return sprintf('%s - %s', $this->hora_inicio, $this->hora_fin);
    }
}
