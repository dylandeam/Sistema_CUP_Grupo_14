<?php

namespace App\Http\Controllers;

use App\Models\Docente;
use App\Models\Docente_Materia;
use App\Models\User;
use App\Models\Materia;
use App\Models\RequisitosDocente;
use App\Models\DocenteMateria;
use App\Models\Requisitos_Docente;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class DocenteController extends Controller
{
    /**
     * Listado de docentes
     */
    public function index()
    {
        $docentes = Docente::with(['user.roles', 'materias'])->get();
        return view('admin.docentes.index', compact('docentes'));
    }

    /**
     * Formulario de creación
     */
    public function create()
    {
        $roles = Role::all();
        $materias = Materia::orderBy('nombre')->get();

        return view('admin.docentes.create', compact('roles', 'materias'));
    }

    /**
     * Guardar nuevo docente con requisitos y materia
     */
    public function store(Request $request)
    {
        $request->validate([
            'nombre'            => 'required|string|max:255',
            'apellido'          => 'required|string|max:255',
            'email'             => 'required|email|unique:users,email',
            'ci'                => 'required|unique:docentes,ci',
            'fecha_nacimiento'  => 'required|date',
            'telefono'          => 'nullable|string|max:20',
            'direccion'         => 'nullable|string|max:255',
            'estado'            => 'required|in:activo,baja',
            'foto'              => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'titulo'            => 'nullable|boolean',
            'maestria'          => 'nullable|boolean',
            'diplomado'         => 'nullable|boolean',
            'nombre_titulo'     => 'required_with:titulo|string|max:150',
            'nombre_maestria'   => 'required_with:maestria|string|max:150',
            'nombre_diplomado'  => 'required_with:diplomado|string|max:150',
            'materia_id'        => 'required|exists:materias,id',
        ]);

        $titulo = $request->boolean('titulo');
        $maestria = $request->boolean('maestria');
        $diplomado = $request->boolean('diplomado');

        if (! $titulo || ! $maestria || ! $diplomado) {
            return back()->withInput()->withErrors(['requisitos' => 'No cumple con los 3 requisitos y no puede ser contratado.']);
        }

        $area = $this->inferirArea(
            $request->nombre_titulo,
            $request->nombre_maestria,
            $request->nombre_diplomado
        );

        if (! $area) {
            return back()->withInput()->withErrors(['requisitos' => 'Los títulos ingresados no son afines a un área válida y no puede ser contratado.']);
        }

        $materia = Materia::findOrFail($request->materia_id);

        // Crear usuario
        $usuario = User::create([
            'name'     => $request->nombre . ' ' . $request->apellido,
            'email'    => $request->email,
            'password' => Hash::make($request->ci),
        ]);
        $usuario->assignRole('DOCENTE');

        // Generar código
        $codigo = strtoupper(substr($request->apellido, 0, 1)) .
                  strtoupper(substr($request->nombre, 0, 1)) .
                  substr($request->ci, 0, 4);

        // Foto
        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('docentes', 'public')
            : null;

        // Crear docente
        $docente = Docente::create([
            'usuario_id'      => $usuario->id,
            'codigo'          => $codigo,
            'nombre'          => $request->nombre,
            'apellido'        => $request->apellido,
            'ci'              => $request->ci,
            'fecha_nacimiento'=> $request->fecha_nacimiento,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'foto'            => $fotoPath,
            'estado'          => $request->estado,
        ]);

        // Guardar requisitos
        Requisitos_Docente::create([
            'docente_id'       => $docente->id,
            'titulo'           => $titulo,
            'nombre_titulo'    => $request->nombre_titulo,
            'maestria'         => $maestria,
            'nombre_maestria'  => $request->nombre_maestria,
            'diplomado'        => $diplomado,
            'nombre_diplomado' => $request->nombre_diplomado,
            'area_especialidad'=> $area,
        ]);

        // Guardar asignación de materia
        Docente_Materia::create([
            'docente_id' => $docente->id,
            'materia_id' => $materia->id,
            'estado'     => $request->estado,
        ]);

        BitacoraService::registrar('Creó docente y le asignó materia');

        return redirect()->route('admin.docentes.index')
            ->with('mensaje', 'Docente registrado correctamente con requisitos y materia')
            ->with('icono', 'success');
    }

    private function inferirArea(string $titulo, string $maestria, string $diplomado): ?string
    {
        $texto = mb_strtolower(trim($titulo . ' ' . $maestria . ' ' . $diplomado));

        if (preg_match('/matem[aá]tica|álgebra|estad[ií]stica|geometr[ií]a|probabilidad|cálculo|matemáticas/', $texto)) {
            return 'matematicas';
        }

        if (preg_match('/computaci[oó]n|programaci[oó]n|sistemas|software|inform[aá]tica|tecnolog[ií]a/', $texto)) {
            return 'computacion';
        }

        if (preg_match('/f[ií]sica|ingenier[ií]a|mec[aá]nica|electr[oó]nica|qu[ií]mica|biomec[aá]nica/', $texto)) {
            return 'fisica';
        }

        if (preg_match('/ingl[eé]s|idioma|lengua extranjera|traducci[oó]n|literatura inglesa|filolog[ií]a/', $texto)) {
            return 'ingles';
        }

        return null;
    }

    /**
     * Formulario de edición
     */
    public function edit(Docente $docente)
    {
        $roles = Role::all();
        $materias = Materia::whereIn('nombre', ['Matemática','Computación','Física','Inglés'])->get();
        return view('admin.docentes.edit', compact('docente','roles','materias'));
    }

    /**
     * Actualizar docente
     */
    public function update(Request $request, Docente $docente)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'apellido'        => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $docente->usuario_id,
            'ci'              => 'required|unique:docentes,ci,' . $docente->id,
            'fecha_nacimiento'=> 'required|date',
            'telefono'        => 'nullable|string|max:20',
            'direccion'       => 'nullable|string|max:255',
            'estado'          => 'required|in:activo,baja',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ]);

        // Actualizar usuario
        $usuario = $docente->user;
        $usuario->update([
            'name'  => $request->nombre . ' ' . $request->apellido,
            'email' => $request->email,
        ]);
        $usuario->syncRoles(['DOCENTE']);

        // Regenerar código
        $codigo = strtoupper(substr($request->apellido,0,1)) .
                  strtoupper(substr($request->nombre,0,1)) .
                  substr($request->ci,0,4);

        // Foto
        $fotoPath = $docente->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('docentes', 'public');
        }

        // Actualizar docente
        $docente->update([
            'codigo'          => $codigo,
            'nombre'          => $request->nombre,
            'apellido'        => $request->apellido,
            'ci'              => $request->ci,
            'fecha_nacimiento'=> $request->fecha_nacimiento,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'foto'            => $fotoPath,
            'estado'          => $request->estado,
        ]);

        BitacoraService::registrar('Actualizó docente ' . $docente->codigo);

        return redirect()->route('admin.docentes.index')
            ->with('mensaje', 'Docente actualizado correctamente')
            ->with('icono', 'success');
    }

    /**
     * Eliminar docente
     */
    public function destroy(Docente $docente)
    {
        if ($docente->foto && Storage::disk('public')->exists($docente->foto)) {
            Storage::disk('public')->delete($docente->foto);
        }

        if ($docente->user) {
            $docente->user->delete();
        }

        $docente->delete();

        BitacoraService::registrar('Eliminó docente ' . $docente->codigo);

        return redirect()->route('admin.docentes.index')
            ->with('mensaje', 'Docente eliminado correctamente')
            ->with('icono', 'success');
    }
}
