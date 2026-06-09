<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NotaExamen extends Model
{
    use HasFactory;

    protected $table = 'nota_examens';
    protected $fillable = ['id_inscripcion', 'id_examen', 'id_materia', 'nota_materia', 'nota_ponderada'];
    public $timestamps = true;

    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'id_inscripcion');
    }

    public function examen()
    {
        return $this->belongsTo(Examen::class, 'id_examen');
    }

    public function materia()
    {
        return $this->belongsTo(Materia::class, 'id_materia');
    }
}
