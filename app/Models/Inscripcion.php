<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Postulante;
use App\Models\Gestion;
use App\Models\Modalidad;
use App\Models\Pago;
use App\Models\Requisitos_Postulante;
use App\Models\Inscripcion_Carrera;
use App\Models\Carrera;
use App\Models\Turno;
use App\Models\Grupo;

class Inscripcion extends Model
{
    use HasFactory;

    protected $table = 'inscripcions';

    // Campos que se pueden asignar masivamente
    protected $fillable = [
        'estado',
        'fecha_insc',
        'costo',
        'postulante_codigo',
        'gestion_id',
        'modalidad_id',
        'turno_id',
        'grupo_id',
    ];

    // Relación con postulante
    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'postulante_codigo', 'codigo');
    }

    // Relación con gestión
    public function gestion()
    {
        return $this->belongsTo(Gestion::class, 'gestion_id', 'id');
    }

    // Relación con modalidad
    public function modalidad()
    {
        return $this->belongsTo(Modalidad::class, 'modalidad_id', 'id');
    }

    // Relación con turno
    public function turno()
    {
        return $this->belongsTo(Turno::class, 'turno_id', 'id');
    }

    // Relación con grupo
    public function grupo()
    {
        return $this->belongsTo(Grupo::class, 'grupo_id', 'id');
    }

    public function pago()
    {
        return $this->hasOne(Pago::class, 'inscripcion_id', 'id');
    }

    public function requisitos()
    {
        return $this->hasOne(Requisitos_Postulante::class, 'postulante_codigo', 'postulante_codigo');
    }

    public function inscripcionCarreras()
    {
        return $this->hasMany(Inscripcion_Carrera::class, 'inscripcion_id', 'id');
    }

    public function primeraCarrera()
    {
        return $this->hasOne(Inscripcion_Carrera::class, 'inscripcion_id', 'id')->where('orden_pref', 1);
    }

    public function segundaCarrera()
    {
        return $this->hasOne(Inscripcion_Carrera::class, 'inscripcion_id', 'id')->where('orden_pref', 2);
    }
}
