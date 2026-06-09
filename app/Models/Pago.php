<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pago extends Model
{
    use HasFactory;

    protected $table = 'pagos';
    protected $primaryKey = 'id';

    protected $fillable = [
        'monto',
        'fecha',
        'estado',
        'comprobante',
        'inscripcion_id',
    ];

    // Relación con inscripción
    public function inscripcion()
    {
        return $this->belongsTo(Inscripcion::class, 'inscripcion_id', 'id');
    }
}
