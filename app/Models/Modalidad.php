<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Modalidad extends Model
{
    use HasFactory;

    protected $table = 'modalidades'; // nombre de la tabla
    protected $primaryKey = 'id';     // llave primaria

    protected $fillable = [
        'nombre',
    ];

    // Reglas de validación (ejemplo si usas FormRequest o Validator)
    public static $rules = [
        'nombre' => 'required|string|max:255|unique:modalidades,nombre',
    ];
}
