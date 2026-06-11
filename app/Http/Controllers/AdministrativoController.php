<?php

namespace App\Http\Controllers;

use App\Models\Administrativo;
use App\Models\User;
use App\Services\BitacoraService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Spatie\Permission\Models\Role;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Validator;

class AdministrativoController extends Controller
{
    public function index()
    {
        $administrativos = Administrativo::with('user.roles')->get();
        return view('admin.administrativos.index', compact('administrativos'));
    }

    public function create()
    {
        $roles = Role::all();
        return view('admin.administrativos.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'apellido'        => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email',
            'ci'              => 'required|unique:administrativos,ci',
            'fecha_nacimiento'=> 'required|date',
            'telefono'        => 'nullable|string|max:20',
            'direccion'       => 'nullable|string|max:255',
            'cargo'           => 'required|string|max:255',
            'rol'             => 'required',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'ci.unique' => 'El CI ya está registrado en otro administrativo.',
            'email.unique' => 'El correo ya está en uso.',
        ]);

        $usuario = User::create([
            'name'     => $request->nombre . ' ' . $request->apellido,
            'email'    => $request->email,
            'password' => Hash::make($request->ci),
        ]);
        $usuario->assignRole($request->rol);

        $codigo = strtoupper(substr($request->apellido,0,1)) .
                  strtoupper(substr($request->nombre,0,1)) .
                  substr($request->ci,0,4);

        $fotoPath = $request->hasFile('foto')
            ? $request->file('foto')->store('administrativos', 'public')
            : null;

        Administrativo::create([
            'codigo'          => $codigo,
            'usuario_id'      => $usuario->id,
            'nombre'          => $request->nombre,
            'apellido'        => $request->apellido,
            'ci'              => $request->ci,
            'fecha_nacimiento'=> $request->fecha_nacimiento,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'cargo'           => $request->cargo,
            'foto'            => $fotoPath,
        ]);

        BitacoraService::registrar('Creó administrativo: ' . $request->nombre . ' ' . $request->apellido);

        return redirect()->route('admin.administrativos.index')
            ->with('mensaje', 'Administrativo registrado correctamente')
            ->with('icono', 'success');
    }

    public function show(Administrativo $administrativo)
    {
        return view('admin.administrativos.show', compact('administrativo'));
    }

    public function edit(Administrativo $administrativo)
    {
        $roles = Role::all();
        return view('admin.administrativos.edit', compact('administrativo','roles'));
    }

    public function update(Request $request, Administrativo $administrativo)
    {
        $request->validate([
            'nombre'          => 'required|string|max:255',
            'apellido'        => 'required|string|max:255',
            'email'           => 'required|email|unique:users,email,' . $administrativo->usuario_id,
            'ci'              => 'required|unique:administrativos,ci,' . $administrativo->codigo . ',codigo',
            'fecha_nacimiento'=> 'required|date',
            'telefono'        => 'nullable|string|max:20',
            'direccion'       => 'nullable|string|max:255',
            'cargo'           => 'required|string|max:255',
            'rol'             => 'required',
            'foto'            => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
        ], [
            'ci.unique' => 'El CI ya está registrado en otro administrativo.',
            'email.unique' => 'El correo ya está en uso.',
        ]);

        $usuario = $administrativo->user;
        $usuario->update([
            'name'  => $request->nombre . ' ' . $request->apellido,
            'email' => $request->email,
        ]);
        $usuario->syncRoles([$request->rol]);

        $codigo = strtoupper(substr($request->apellido,0,1)) .
                  strtoupper(substr($request->nombre,0,1)) .
                  substr($request->ci,0,4);

        $fotoPath = $administrativo->foto;
        if ($request->hasFile('foto')) {
            if ($fotoPath && Storage::disk('public')->exists($fotoPath)) {
                Storage::disk('public')->delete($fotoPath);
            }
            $fotoPath = $request->file('foto')->store('administrativos', 'public');
        }

        $administrativo->update([
            'codigo'          => $codigo,
            'nombre'          => $request->nombre,
            'apellido'        => $request->apellido,
            'ci'              => $request->ci,
            'fecha_nacimiento'=> $request->fecha_nacimiento,
            'telefono'        => $request->telefono,
            'direccion'       => $request->direccion,
            'cargo'           => $request->cargo,
            'foto'            => $fotoPath,
        ]);

        BitacoraService::registrar('Actualizó administrativo: ' . $administrativo->codigo);

        return redirect()->route('admin.administrativos.index')
            ->with('mensaje', 'Administrativo actualizado correctamente')
            ->with('icono', 'success');
    }

    public function destroy(Administrativo $administrativo)
    {
        if ($administrativo->foto && Storage::disk('public')->exists($administrativo->foto)) {
            Storage::disk('public')->delete($administrativo->foto);
        }

        if ($administrativo->user) {
            $administrativo->user->delete();
        }

        $administrativo->delete();

        BitacoraService::registrar('Eliminó administrativo: ' . $administrativo->codigo);

        return redirect()->route('admin.administrativos.index')
            ->with('mensaje', 'Administrativo eliminado correctamente')
            ->with('icono', 'success');
    }

    public function showImportForm()
    {
        return view('admin.administrativos.import');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,csv,txt|max:2048',
        ]);
        $data = Excel::toArray([], $request->file('file'))[0]; // primera hoja
        
        $errores = [];
        $filasValidas = [];
        $ciUsados = [];
        $emailUsados = [];
        
        foreach ($data as $fila => $row) {
            if ($fila === 0) continue; // saltar encabezado
            
            // Normalizar valores
            $ci = isset($row[2]) ? preg_replace('/[^0-9]/','', $row[2]) : '';
            $email = trim($row[4] ?? '');
            
            $filaData = [
                'nombre'          => trim($row[0] ?? ''),
                'apellido'        => trim($row[1] ?? ''),
                'ci'              => $ci,
                'fecha_nacimiento'=> isset($row[3]) ? \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($row[3])->format('Y-m-d') : '',
                'email'           => $email,
                'telefono'        => isset($row[5]) ? preg_replace('/[^0-9]/','',$row[5]) : null,
                'direccion'       => trim($row[6] ?? ''),
                'cargo'           => trim($row[7] ?? ''),
                'rol'             => trim($row[8] ?? 'ADMINISTRATIVO'),
            ];
            
            // Validar duplicados dentro del archivo
            if (in_array($ci, $ciUsados)) {
                $errores[] = "❌ Error en fila ".($fila+1).": El CI $ci está duplicado en el archivo.";
                continue;
            }
            
            if (in_array($email, $emailUsados)) {
                $errores[] = "❌ Error en fila ".($fila+1).": El Email $email está duplicado en el archivo.";
                continue;
            }
            
            $ciUsados[] = $ci;
            $emailUsados[] = $email;
            
            // Validar contra BD
            $validator = Validator::make($filaData, [
                'nombre' => 'required|string|max:255',
                'apellido' => 'required|string|max:255',
                'ci' => 'required|unique:administrativos,ci',
                'email' => 'required|email|unique:users,email',
                'fecha_nacimiento' => 'required|date',
                'telefono' => 'nullable|string|max:20',
                'direccion' => 'nullable|string|max:255',
                'cargo' => 'required|string|max:255',
            ], [
                'nombre.required' => 'El campo Nombre es obligatorio.',
                'apellido.required' => 'El campo Apellido es obligatorio.',
                'ci.required' => 'El campo CI es obligatorio.',
                'ci.unique' => 'El CI ya está registrado.',
                'email.required' => 'El campo Email es obligatorio.',
                'email.email' => 'El Email no tiene un formato válido.',
                'email.unique' => 'El Email ya está registrado.',
                'fecha_nacimiento.required' => 'La Fecha de nacimiento es obligatoria.',
                'fecha_nacimiento.date' => 'La Fecha de nacimiento no es válida.',
                'cargo.required' => 'El campo Cargo es obligatorio.',
            ]);
            
            if ($validator->fails()) {
                $errores[] = "❌ Error en fila ".($fila+1).": ".implode(' | ', $validator->errors()->all());
                continue;
           }
           
           // Si pasa validación, guardamos la fila para insertar después
           $filasValidas[] = $filaData;
        }
        
        // Si hay errores → no se guarda nada
        if (!empty($errores)) {
            return back()->with('mensaje', implode('<br>', $errores))->with('icono', 'danger');
        }
        
        // Si no hay errores → insertar todas las filas
        foreach ($filasValidas as $row) {
            $usuario = User::create([
                'name' => $row['nombre'].' '.$row['apellido'],
                'email' => $row['email'],
                'password' => Hash::make($row['ci']),
            ]);
            $usuario->assignRole('ADMINISTRATIVO');
            
            $codigo = strtoupper(substr($row['apellido'],0,1))
                . strtoupper(substr($row['nombre'],0,1))
                . substr($row['ci'],0,4);
                
                Administrativo::create([
                    'codigo' => $codigo,
                    'usuario_id' => $usuario->id,
                    'nombre' => $row['nombre'],
                    'apellido' => $row['apellido'],
                    'ci' => $row['ci'],
                    'fecha_nacimiento' => $row['fecha_nacimiento'],
                    'telefono' => $row['telefono'],
                    'direccion' => $row['direccion'],
                    'cargo' => $row['cargo'],
                    'foto' => null,
                ]);
            }
            
        return back()->with('mensaje', '✅ Carga masiva realizada correctamente')->with('icono', 'success');
    }
} 