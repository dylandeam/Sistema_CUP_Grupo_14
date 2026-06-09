<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Postulante extends Model
{
    use HasFactory;

    protected $table = 'postulantes';
    protected $primaryKey = 'codigo';   // PK personalizada
    public $incrementing = false;       // No autoincrementa
    protected $keyType = 'string';      // Tipo de la PK

    protected $fillable = [
        'usuario_id',
        'codigo',
        'nombre',
        'apellidos',
        'ci',
        'fecha_nacimiento',
        'sexo',
        'telefono',
        'direccion',
        'colegio',
        'ciudad',
        'foto',
    ];

    // Relación con usuario
    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
