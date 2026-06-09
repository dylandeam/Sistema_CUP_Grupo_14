<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Requisitos_Docente;

use Illuminate\Http\Request;

class RequisitoDocenteController extends Controller
{
    public function create(Docente $docente)
    {
        return view('admin.requisitos_docente.create', compact('docente'));
    }

    public function store(Request $request, Docente $docente)
    {
        $request->validate([
            'titulo'           => 'required|boolean',
            'maestria'         => 'required|boolean',
            'diplomado'        => 'required|boolean',
            'nombre_titulo'    => 'nullable|string|max:150',
            'nombre_maestria'  => 'nullable|string|max:150',
            'nombre_diplomado' => 'nullable|string|max:150',
        ]);

        if (!$request->titulo || !$request->maestria || !$request->diplomado) {
            return back()->withErrors(['requisitos' => 'Usted no cumple los requisitos para ser contratado.']);
        }

        $areasValidas = ['matematicas','computacion','fisica','ingles'];
        $area = null;

        if ($request->nombre_titulo) {
            $area = $this->inferirArea($request->nombre_titulo, $areasValidas);
        } elseif ($request->nombre_maestria) {
            $area = $this->inferirArea($request->nombre_maestria, $areasValidas);
        } elseif ($request->nombre_diplomado) {
            $area = $this->inferirArea($request->nombre_diplomado, $areasValidas);
        }

        if (!$area) {
            return back()->withErrors(['area' => 'Los títulos/maestrías/diplomados no son afines a las áreas permitidas.']);
        }

        Requisitos_Docente::create([
            'docente_codigo'   => $docente->codigo,
            'titulo'           => $request->titulo,
            'nombre_titulo'    => $request->nombre_titulo,
            'maestria'         => $request->maestria,
            'nombre_maestria'  => $request->nombre_maestria,
            'diplomado'        => $request->diplomado,
            'nombre_diplomado' => $request->nombre_diplomado,
        ]);

        return redirect()->route('admin.docente_materia.create', $docente)
            ->with('mensaje','Requisitos registrados correctamente. Ahora puede asignar materias.');
    }

    private function inferirArea(string $texto, array $areasValidas): ?string
    {
        $texto = strtolower($texto);

        if (str_contains($texto, 'álgebra') || str_contains($texto, 'cálculo') || str_contains($texto, 'matemática') 
            || str_contains($texto, 'estadística') || str_contains($texto, 'geometría') || str_contains($texto, 'probabilidad')) {
            return 'matematicas';
        }

        if (str_contains($texto, 'computación') || str_contains($texto, 'programación') || str_contains($texto, 'sistemas') 
            || str_contains($texto, 'software') || str_contains($texto, 'informática') || str_contains($texto, 'tecnología')) {
            return 'computacion';
        }

        if (str_contains($texto, 'física') || str_contains($texto, 'ingeniería') || str_contains($texto, 'mecánica') 
            || str_contains($texto, 'electrónica') || str_contains($texto, 'química')) {
            return 'fisica';
        }

        if (str_contains($texto, 'inglés') || str_contains($texto, 'idioma') || str_contains($texto, 'lengua extranjera') 
            || str_contains($texto, 'traducción') || str_contains($texto, 'literatura inglesa') || str_contains($texto, 'filología')) {
            return 'ingles';
        }

        return null;
    }
}
