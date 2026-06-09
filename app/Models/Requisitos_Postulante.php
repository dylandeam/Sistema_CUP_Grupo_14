<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Requisitos_Postulante extends Model
{
    use HasFactory;

    protected $table = 'requisitos_postulantes';

    protected $fillable = [
        'postulante_codigo',
        'fotocopia_ci',
        'certificado_nacimiento',
        'titulo_bachiller',
        'libreta_colegio',
    ];

    // Relación con postulante
    public function postulante()
    {
        return $this->belongsTo(Postulante::class, 'postulante_codigo', 'codigo');
    }
}
