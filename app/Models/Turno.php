<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Turno extends Model
{
    use HasFactory;

    protected $table = 'turnos'; // nombre de la tabla
    protected $primaryKey = 'id'; // llave primaria id
    protected $fillable = [
        'nombre',
    ];

    // Reglas de validación (ejemplo si usas FormRequest o Validator)
    public static $rules = [
        'nombre' => 'required|string|max:255|unique:turnos,nombre',
    ];
}
