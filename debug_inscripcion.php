<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make('Illuminate\Contracts\Http\Kernel');
$response = $kernel->handle(
    $request = \Illuminate\Http\Request::capture()
);

// Código de prueba
$inscritos = \DB::select("SELECT id, gestion_id, modalidad_id, turno_id, grupo_id FROM inscripcions LIMIT 1");
if (count($inscritos) > 0) {
    echo "Primer inscrito:\n";
    echo json_encode($inscritos[0], JSON_PRETTY_PRINT);
} else {
    echo "No hay inscritos";
}

// Revisar si existe un grupo
$grupos = \DB::select("SELECT id, nombre, id_gestion, id_modalidad, id_turno FROM grupos LIMIT 1");
if (count($grupos) > 0) {
    echo "\nPrimer grupo:\n";
    echo json_encode($grupos[0], JSON_PRETTY_PRINT);
} else {
    echo "\nNo hay grupos";
}
