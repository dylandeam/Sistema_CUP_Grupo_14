<?php

namespace App\Http\Controllers;

use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class PasswordController extends Controller
{
    public function edit()
    {
        return view('auth.passwords.change');
    }

    public function update(Request $request)
    {
        $request->validate([
            'current_password' => ['required'],
            'new_password' => [
                'required', 
                'string', 
                'min:8', 
                'confirmed',
                'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*[!@#$%^&*()_+\-=\[\]{};:\'"\\|,.<>\/?])/'
            ],
        ], [
            'new_password.regex' => 'La contraseña debe contener al menos una mayúscula, una minúscula y un carácter especial.',
        ]);

        /** @var \App\Models\User $user */ // Comentario para Intelephense
        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'La contraseña actual no coincide']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        BitacoraService::registrar('Actualizó su contraseña');

        return redirect()->route('home')->with('status', 'Contraseña actualizada correctamente.');
    }
}
