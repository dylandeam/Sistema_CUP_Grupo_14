<?php
// Test file to debug inscripcions loading
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';

use App\Models\Grupo;
use App\Models\Inscripcion;
use App\Models\Gestion;
use Illuminate\Http\Request;

try {
    // Get active gestion
    $gestion = Gestion::where('estado', 'Activa')->first();
    echo "Gestión activa: " . ($gestion ? $gestion->id : "No encontrada") . "\n";
    
    // Get first grupo
    $grupo = Grupo::first();
    echo "Primer grupo: " . ($grupo ? json_encode(['id' => $grupo->id, 'id_gestion' => $grupo->id_gestion, 'id_modalidad' => $grupo->id_modalidad, 'id_turno' => $grupo->id_turno]) : "No encontrado") . "\n";
    
    if ($grupo) {
        // Get inscritos by grupo data
        $inscritos = Inscripcion::where('gestion_id', $grupo->id_gestion)
            ->where('modalidad_id', $grupo->id_modalidad)
            ->where('turno_id', $grupo->id_turno)
            ->with('postulante')
            ->count();
        echo "Inscritos encontrados: " . $inscritos . "\n";
    }
    
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
