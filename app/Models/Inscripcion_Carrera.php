<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inscripcion_Carrera extends Model
{
    use HasFactory;

    protected $table = 'inscripcion_carrera';
    protected $primaryKey = 'id';

    protected $fillable = [
        'orden_pref',
        'inscripcion_id',
        'carrera_id',
    ];

    // Relación con inscripción
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id', 'id');
    }

    // Relación con carrera
    public function carrera()
    {
        return $this->belongsTo(Carrera::class, 'carrera_id', 'id');
    }
}
