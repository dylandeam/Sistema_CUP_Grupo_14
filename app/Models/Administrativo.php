<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrativo extends Model
{
    use HasFactory;

    protected $table = 'administrativos';

    protected $fillable = [
        'usuario_id',
        'codigo',
        'nombre',
        'apellido',
        'ci',
        'fecha_nacimiento',
        'telefono',
        'direccion',
        'cargo',
        'foto',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'usuario_id');
    }
}
