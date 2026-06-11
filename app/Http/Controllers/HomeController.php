<?php

namespace App\Http\Controllers;

use App\Models\Inscripcion;
use App\Models\Resultado;
use App\Models\Grupo;
use App\Models\User;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $user = User::with('roles')->find(auth()->id());
        $isAdmin = $user->roles->pluck('name')->intersect(['ADMINISTRADOR', 'ADMINISTRATIVO'])->count() > 0;

        $statistics = [];
        if ($isAdmin) {
            $statistics = [
                'total_inscritos' => Inscripcion::count(),
                'total_aprobados' => Resultado::where('estado', 'ADMITIDO')->count(),
                'total_reprobados' => Resultado::where('estado', 'NO ADMITIDO')->count(),
                'total_grupos' => Grupo::count(),
            ];
        }

        return view('home', compact('isAdmin', 'statistics'));
    }
}
