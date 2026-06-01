<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

use App\Http\Controllers\GestionController; 
use App\Http\Controllers\CarreraController;
use App\Http\Controllers\MateriaController;
use App\Http\Controllers\AulaController;
use App\Http\Controllers\HorarioController;
use App\Http\Controllers\RolController;
use App\Http\Controllers\AdministrativoController;
use App\Http\Controllers\BitacoraController;
use App\Http\Controllers\DocenteController;
use App\Http\Controllers\RequisitoDocenteController;
use App\Http\Controllers\DocenteMateriaController;
use App\Http\Controllers\PasswordController;
use App\Http\Controllers\UserController;



; 

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');



// Rutas para Gestiones
Route::get('/admin/gestiones', [GestionController::class,'index'])->name('admin.gestiones.index')->middleware('auth', 'can:admin.gestiones.index');
Route::get('/admin/gestiones/create', [GestionController::class,'create'])->name('admin.gestiones.create')->middleware('auth', 'can:admin.gestiones.create');;
Route::post('/admin/gestiones', [GestionController::class,'store'])->name('admin.gestiones.store')->middleware('auth', 'can:admin.gestiones.store');;
Route::get('/admin/gestiones/{gestion}', [GestionController::class,'show'])->name('admin.gestiones.show')->middleware('auth', 'can:admin.gestiones.show');;
Route::get('/admin/gestiones/{gestion}/edit', [GestionController::class,'edit'])->name('admin.gestiones.edit')->middleware('auth', 'can:admin.gestiones.edit');;
Route::put('/admin/gestiones/{gestion}', [GestionController::class,'update'])->name('admin.gestiones.update')->middleware('auth', 'can:admin.gestiones.update');;
Route::delete('/admin/gestiones/{gestion}', [GestionController::class,'destroy'])->name('admin.gestiones.destroy')->middleware('auth', 'can:admin.gestiones.destroy');;

//Rutas para Carreras
Route::get('/admin/carreras', [CarreraController::class,'index'])->name('admin.carreras.index')->middleware('auth', 'can:admin.carreras.index');
Route::get('/admin/carreras/create', [CarreraController::class,'create'])->name('admin.carreras.create')->middleware('auth', 'can:admin.carreras.create');
Route::post('/admin/carreras', [CarreraController::class,'store'])->name('admin.carreras.store')->middleware('auth', 'can:admin.carreras.store');
Route::get('/admin/carreras/{carrera}', [CarreraController::class,'show'])->name('admin.carreras.show')->middleware('auth', 'can:admin.carreras.show');
Route::get('/admin/carreras/{carrera}/edit', [CarreraController::class,'edit'])->name('admin.carreras.edit')->middleware('auth', 'can:admin.carreras.edit');
Route::put('/admin/carreras/{carrera}', [CarreraController::class,'update'])->name('admin.carreras.update')->middleware('auth', 'can:admin.carreras.update');
Route::delete('/admin/carreras/{carrera}', [CarreraController::class,'destroy'])->name('admin.carreras.destroy')->middleware('auth', 'can:admin.carreras.destroy');

//Rutas para Materias
Route::get('/admin/materias', [MateriaController::class,'index'])->name('admin.materias.index')->middleware('auth', 'can:admin.materias.index');
Route::get('/admin/materias/create', [MateriaController::class,'create'])->name('admin.materias.create')->middleware('auth', 'can:admin.materias.create');
Route::post('/admin/materias', [MateriaController::class,'store'])->name('admin.materias.store')->middleware('auth', 'can:admin.materias.store');
Route::get('/admin/materias/{materia}', [MateriaController::class,'show'])->name('admin.materias.show')->middleware('auth', 'can:admin.materias.show');
Route::get('/admin/materias/{materia}/edit', [MateriaController::class,'edit'])->name('admin.materias.edit')->middleware('auth', 'can:admin.materias.edit');
Route::put('/admin/materias/{materia}', [MateriaController::class,'update'])->name('admin.materias.update')->middleware('auth', 'can:admin.materias.update');
Route::delete('/admin/materias/{materia}', [MateriaController::class,'destroy'])->name('admin.materias.destroy')->middleware('auth', 'can:admin.materias.destroy');

// Rutas para Aulas
Route::get('/admin/aulas', [AulaController::class,'index'])->name('admin.aulas.index')->middleware('auth', 'can:admin.aulas.index');
Route::get('/admin/aulas/create', [AulaController::class,'create'])->name('admin.aulas.create')->middleware('auth', 'can:admin.aulas.create');
Route::post('/admin/aulas', [AulaController::class,'store'])->name('admin.aulas.store')->middleware('auth', 'can:admin.aulas.store');
Route::get('/admin/aulas/{aula}', [AulaController::class,'show'])->name('admin.aulas.show')->middleware('auth', 'can:admin.aulas.show');
Route::get('/admin/aulas/{aula}/edit', [AulaController::class,'edit'])->name('admin.aulas.edit')->middleware('auth', 'can:admin.aulas.edit');
Route::put('/admin/aulas/{aula}', [AulaController::class,'update'])->name('admin.aulas.update')->middleware('auth', 'can:admin.aulas.update');
Route::delete('/admin/aulas/{aula}', [AulaController::class,'destroy'])->name('admin.aulas.destroy')->middleware('auth', 'can:admin.aulas.destroy');

// Rutas para Horarios
Route::get('/admin/horarios', [HorarioController::class,'index'])->name('admin.horarios.index')->middleware('auth', 'can:admin.horarios.index');
Route::get('/admin/horarios/create', [HorarioController::class,'create'])->name('admin.horarios.create')->middleware('auth', 'can:admin.horarios.create');
Route::post('/admin/horarios', [HorarioController::class,'store'])->name('admin.horarios.store')->middleware('auth', 'can:admin.horarios.store');
Route::get('/admin/horarios/{horario}', [HorarioController::class,'show'])->name('admin.horarios.show')->middleware('auth', 'can:admin.horarios.show');
Route::get('/admin/horarios/{horario}/edit', [HorarioController::class,'edit'])->name('admin.horarios.edit')->middleware('auth', 'can:admin.horarios.edit');
Route::put('/admin/horarios/{horario}', [HorarioController::class,'update'])->name('admin.horarios.update')->middleware('auth', 'can:admin.horarios.update');
Route::delete('/admin/horarios/{horario}', [HorarioController::class,'destroy'])->name('admin.horarios.destroy')->middleware('auth', 'can:admin.horarios.destroy');

// Rutas para Roles
Route::get('/admin/roles', [RolController::class,'index'])->name('admin.roles.index')->middleware('auth', 'can:admin.roles.index');
Route::get('/admin/roles/create', [RolController::class,'create'])->name('admin.roles.create')->middleware('auth', 'can:admin.roles.create');
Route::post('/admin/roles', [RolController::class,'store'])->name('admin.roles.store')->middleware('auth', 'can:admin.roles.store');
Route::get('/admin/roles/{roles}', [RolController::class,'show'])->name('admin.roles.show')->middleware('auth', 'can:admin.roles.show');
Route::get('/admin/roles/{roles}/permisos', [RolController::class,'permisos'])->name('admin.roles.permisos')->middleware('auth', 'can:admin.roles.permisos');
Route::put('/admin/roles/{roles}/permisos', [RolController::class,'actualizarPermisos'])->name('admin.roles.permisos.actualizar')->middleware('auth', 'can:admin.roles.permisos');
Route::get('/admin/roles/{roles}/edit', [RolController::class,'edit'])->name('admin.roles.edit')->middleware('auth', 'can:admin.roles.edit');
Route::put('/admin/roles/{roles}', [RolController::class,'update'])->name('admin.roles.update')->middleware('auth', 'can:admin.roles.update');
Route::delete('/admin/roles/{roles}', [RolController::class,'destroy'])->name('admin.roles.destroy')->middleware('auth', 'can:admin.roles.destroy');  

// Rutas para Administrativos
Route::get('/admin/administrativos', [AdministrativoController::class,'index'])->name('admin.administrativos.index')->middleware('auth', 'can:admin.administrativos.index');
Route::get('/admin/administrativos/create', [AdministrativoController::class,'create'])->name('admin.administrativos.create')->middleware('auth', 'can:admin.administrativos.create');
Route::post('/admin/administrativos', [AdministrativoController::class,'store'])->name('admin.administrativos.store')->middleware('auth', 'can:admin.administrativos.store');
Route::get('/admin/administrativos/{administrativo}', [AdministrativoController::class,'show'])->name('admin.administrativos.show')->middleware('auth', 'can:admin.administrativos.show');
Route::get('/admin/administrativos/{administrativo}/edit', [AdministrativoController::class,'edit'])->name('admin.administrativos.edit')->middleware('auth', 'can:admin.administrativos.edit');
Route::put('/admin/administrativos/{administrativo}', [AdministrativoController::class,'update'])->name('admin.administrativos.update')->middleware('auth', 'can:admin.administrativos.update');
Route::delete('/admin/administrativos/{administrativo}', [AdministrativoController::class,'destroy'])->name('admin.administrativos.destroy')->middleware('auth', 'can:admin.administrativos.destroy');


// Rutas para Docentes
Route::get('/admin/docentes', [DocenteController::class,'index'])->name('admin.docentes.index')->middleware('auth', 'can:admin.docentes.index');
Route::get('/admin/docentes/create', [DocenteController::class,'create'])->name('admin.docentes.create')->middleware('auth', 'can:admin.docentes.create');
Route::post('/admin/docentes', [DocenteController::class,'store'])->name('admin.docentes.store')->middleware('auth', 'can:admin.docentes.store');
Route::get('/admin/docentes/{docente}', [DocenteController::class,'show'])->name('admin.docentes.show')->middleware('auth', 'can:admin.docentes.show');
Route::get('/admin/docentes/{docente}/edit', [DocenteController::class,'edit'])->name('admin.docentes.edit')->middleware('auth', 'can:admin.docentes.edit');
Route::put('/admin/docentes/{docente}', [DocenteController::class,'update'])->name('admin.docentes.update')->middleware('auth', 'can:admin.docentes.update');
Route::delete('/admin/docentes/{docente}', [DocenteController::class,'destroy'])->name('admin.docentes.destroy')->middleware('auth', 'can:admin.docentes.destroy');


// Rutas para Requisitos Docente
Route::get('/admin/requisitos_docente/{docente}/create', [RequisitoDocenteController::class,'create'])->name('admin.requisitos_docente.create');
Route::post('/admin/requisitos_docente/{docente}', [RequisitoDocenteController::class,'store'])->name('admin.requisitos_docente.store');


// Rutas para Asignación de Materias a Docente
Route::get('/admin/docente_materia/{docente}/create', [DocenteMateriaController::class,'create'])->name('admin.docente_materia.create');
Route::post('/admin/docente_materia/{docente}', [DocenteMateriaController::class,'store'])->name('admin.docente_materia.store');
Route::delete('/admin/docente_materia/{id}', [DocenteMateriaController::class,'destroy'])->name('admin.docente_materia.destroy');

//Rutas para cambiar Contraseña
Route::get('/password/change', [PasswordController::class, 'edit'])->name('password.change')->middleware('auth', 'can:password.change');
Route::post('/password/update', [PasswordController::class, 'update'])->name('password.update')->middleware('auth', 'can:password.update');

//Rutas para bitacora
Route::get('/admin/bitacora', [BitacoraController::class,'index'])->name('admin.bitacora.index')->middleware('auth', 'can:admin.bitacora.index');

/*
// Ruta para el listado de Registrar Usuarios (menu "Registrar Usuario")
Route::get('/admin/registrarusuario', [UserController::class, 'index'])->name('admin.registrarusuario')->middleware('auth');

Route::middleware('auth')->prefix('admin/users')->name('admin.users.')->group(function () {
    Route::get('/', [UserController::class, 'index'])->name('index');
    Route::get('/create', [UserController::class, 'create'])->name('create');
    Route::post('/', [UserController::class, 'store'])->name('store');
    Route::get('/{user}', [UserController::class, 'show'])->name('show');
    Route::get('/{user}/edit', [UserController::class, 'edit'])->name('edit');
    Route::put('/{user}', [UserController::class, 'update'])->name('update');
    Route::delete('/{user}', [UserController::class, 'destroy'])->name('destroy');
});



*/

