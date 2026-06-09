<?php
require __DIR__ . '/vendor/autoload.php';
$app = require __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Models\User::first();
if (! $u) {
    echo 'no user';
    exit(1);
}

$p = App\Models\Postulante::create([
    'codigo' => 'TEST01',
    'usuario_id' => $u->id,
    'nombre' => 'Prueba',
    'apellidos' => 'Test',
    'ci' => '12345678',
    'fecha_nacimiento' => '2000-01-01',
    'sexo' => 'M',
    'telefono' => null,
    'direccion' => 'Test',
    'ciudad' => 'Test',
    'colegio' => 'Test',
    'foto' => null,
]);

echo 'codigo=' . ($p->codigo ?? 'NULL') . PHP_EOL;
