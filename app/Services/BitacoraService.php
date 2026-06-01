<?php

namespace App\Services;

use App\Models\Bitacora;
use Illuminate\Support\Facades\Auth;

class BitacoraService
{
    public static function registrar(string $accion): void
    {
        $usuario = Auth::user();
        $nombreUsuario = $usuario ? trim(($usuario->name ?? '') ?: $usuario->email) : 'Sistema';

        Bitacora::create([
            'usuario' => $nombreUsuario,
            'accion' => $accion,
            'hora' => now(),
        ]);
    }
}
