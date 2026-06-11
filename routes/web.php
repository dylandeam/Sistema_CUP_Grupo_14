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
use App\Http\Controllers\TurnoController;
use App\Http\Controllers\ModalidadController;
use App\Http\Controllers\PostulanteController;
use App\Http\Controllers\InscripcionController;
use App\Http\Controllers\PayPalController;
use App\Http\Controllers\PagoController;
use App\Http\Controllers\GrupoController;
use App\Http\Controllers\CargaHorariaController;
use App\Http\Controllers\ExamenController;
use App\Http\Controllers\NotaExamenController;
use App\Http\Controllers\AsistenciaController;
use App\Http\Controllers\PostulanteGrupoController;
use App\Http\Controllers\PromedioExamenController;
use App\Http\Controllers\ResultadoController;
use App\Http\Controllers\ReporteController;



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
Route::get('/admin/materias/{materia}/edit', [MateriaController::class,'edit'])->name('admin.materias.edit')->middleware('auth', 'can:admin.materias.edit');
Route::put('/admin/materias/{materia}', [MateriaController::class,'update'])->name('admin.materias.update')->middleware('auth', 'can:admin.materias.update');
Route::delete('/admin/materias/{materia}', [MateriaController::class,'destroy'])->name('admin.materias.destroy')->middleware('auth', 'can:admin.materias.destroy');

// Rutas para Aulas
Route::get('/admin/aulas', [AulaController::class,'index'])->name('admin.aulas.index')->middleware('auth', 'can:admin.aulas.index');
Route::get('/admin/aulas/create', [AulaController::class,'create'])->name('admin.aulas.create')->middleware('auth', 'can:admin.aulas.create');
Route::post('/admin/aulas', [AulaController::class,'store'])->name('admin.aulas.store')->middleware('auth', 'can:admin.aulas.store');
Route::get('/admin/aulas/{aula}/edit', [AulaController::class,'edit'])->name('admin.aulas.edit')->middleware('auth', 'can:admin.aulas.edit');
Route::put('/admin/aulas/{aula}', [AulaController::class,'update'])->name('admin.aulas.update')->middleware('auth', 'can:admin.aulas.update');
Route::delete('/admin/aulas/{aula}', [AulaController::class,'destroy'])->name('admin.aulas.destroy')->middleware('auth', 'can:admin.aulas.destroy');

// Rutas para Horarios
Route::get('/admin/horarios', [HorarioController::class,'index'])->name('admin.horarios.index')->middleware('auth', 'can:admin.horarios.index');
Route::get('/admin/horarios/create', [HorarioController::class,'create'])->name('admin.horarios.create')->middleware('auth', 'can:admin.horarios.create');
Route::post('/admin/horarios', [HorarioController::class,'store'])->name('admin.horarios.store')->middleware('auth', 'can:admin.horarios.store');
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
      // Importación masiva
Route::get('/admin/administrativos/import/form', [AdministrativoController::class,'showImportForm'])->name('admin.administrativos.import.form')->middleware('auth', 'can:admin.administrativos.import');
Route::post('/admin/administrativos/import', [AdministrativoController::class,'import'])->name('admin.administrativos.import')->middleware('auth', 'can:admin.administrativos.import');


// Rutas para Docentes
Route::get('/admin/docentes', [DocenteController::class,'index'])->name('admin.docentes.index')->middleware('auth', 'can:admin.docentes.index');
Route::get('/admin/docentes/create', [DocenteController::class,'create'])->name('admin.docentes.create')->middleware('auth', 'can:admin.docentes.create');
Route::post('/admin/docentes', [DocenteController::class,'store'])->name('admin.docentes.store')->middleware('auth', 'can:admin.docentes.store');
Route::get('/admin/docentes/{docente}', [DocenteController::class,'show'])->name('admin.docentes.show')->middleware('auth', 'can:admin.docentes.show');
Route::get('/admin/docentes/{docente}/edit', [DocenteController::class,'edit'])->name('admin.docentes.edit')->middleware('auth', 'can:admin.docentes.edit');
Route::put('/admin/docentes/{docente}', [DocenteController::class,'update'])->name('admin.docentes.update')->middleware('auth', 'can:admin.docentes.update');
Route::delete('/admin/docentes/{docente}', [DocenteController::class,'destroy'])->name('admin.docentes.destroy')->middleware('auth', 'can:admin.docentes.destroy');
    // Importación masiva 
Route::get('/admin/docentes/import/form', [DocenteController::class,'showImportForm'])->name('admin.docentes.import.form')->middleware('auth', 'can:admin.docentes.import');
Route::post('/admin/docentes/import', [DocenteController::class,'import'])->name('admin.docentes.import')->middleware('auth', 'can:admin.docentes.import');


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


// Rutas para Turnos
Route::get('/admin/turnos', [TurnoController::class,'index'])->name('admin.turnos.index')->middleware('auth', 'can:admin.turnos.index');
Route::get('/admin/turnos/create', [TurnoController::class,'create'])->name('admin.turnos.create')->middleware('auth', 'can:admin.turnos.create');
Route::post('/admin/turnos', [TurnoController::class,'store'])->name('admin.turnos.store')->middleware('auth', 'can:admin.turnos.store');
Route::get('/admin/turnos/{turno}/edit', [TurnoController::class,'edit'])->name('admin.turnos.edit')->middleware('auth', 'can:admin.turnos.edit');
Route::put('/admin/turnos/{turno}', [TurnoController::class,'update'])->name('admin.turnos.update')->middleware('auth', 'can:admin.turnos.update');
Route::delete('/admin/turnos/{turno}', [TurnoController::class,'destroy'])->name('admin.turnos.destroy')->middleware('auth', 'can:admin.turnos.destroy');

// Rutas para Modalidades
Route::get('/admin/modalidades', [ModalidadController::class,'index'])->name('admin.modalidades.index')->middleware('auth', 'can:admin.modalidades.index');
Route::get('/admin/modalidades/create', [ModalidadController::class,'create'])->name('admin.modalidades.create')->middleware('auth', 'can:admin.modalidades.create');
Route::post('/admin/modalidades', [ModalidadController::class,'store'])->name('admin.modalidades.store')->middleware('auth', 'can:admin.modalidades.store');
Route::get('/admin/modalidades/{modalidad}/edit', [ModalidadController::class,'edit'])->name('admin.modalidades.edit')->middleware('auth', 'can:admin.modalidades.edit');
Route::put('/admin/modalidades/{modalidad}', [ModalidadController::class,'update'])->name('admin.modalidades.update')->middleware('auth', 'can:admin.modalidades.update');
Route::delete('/admin/modalidades/{modalidad}', [ModalidadController::class,'destroy'])->name('admin.modalidades.destroy')->middleware('auth', 'can:admin.modalidades.destroy');


// Rutas para Postulantes
Route::get('/admin/postulantes', [PostulanteController::class,'index'])->name('admin.postulantes.index')->middleware('auth', 'can:admin.postulantes.index');
Route::get('/admin/postulantes/create', [PostulanteController::class,'create'])->name('admin.postulantes.create')->middleware('auth', 'can:admin.postulantes.create');
Route::post('/admin/postulantes', [PostulanteController::class,'store'])->name('admin.postulantes.store')->middleware('auth', 'can:admin.postulantes.store');
Route::get('/admin/postulantes/{postulante}', [PostulanteController::class,'show'])->name('admin.postulantes.show')->middleware('auth', 'can:admin.postulantes.show');
Route::get('/admin/postulantes/{postulante}/edit', [PostulanteController::class,'edit'])->name('admin.postulantes.edit')->middleware('auth', 'can:admin.postulantes.edit');
Route::put('/admin/postulantes/{postulante}', [PostulanteController::class,'update'])->name('admin.postulantes.update')->middleware('auth', 'can:admin.postulantes.update');
Route::delete('/admin/postulantes/{postulante}', [PostulanteController::class,'destroy'])->name('admin.postulantes.destroy')->middleware('auth', 'can:admin.postulantes.destroy');
    // Importación masiva 
Route::get('/admin/postulantes/import/form', [PostulanteController::class,'showImportForm'])->name('admin.postulantes.import.form')->middleware('auth', 'can:admin.postulantes.import');
Route::post('/admin/postulantes/import', [PostulanteController::class,'import'])->name('admin.postulantes.import')->middleware('auth', 'can:admin.postulantes.import');

// Rutas para Inscripciones
Route::get('/admin/inscripciones', [InscripcionController::class,'index'])->name('admin.inscripciones.index')->middleware('auth', 'can:admin.inscripciones.index');
Route::get('/admin/inscripciones/create', [InscripcionController::class,'create'])->name('admin.inscripciones.create')->middleware('auth', 'can:admin.inscripciones.create');
// AJAX routes para búsqueda de postulantes
Route::get('/admin/inscripciones/ajax/buscar-postulante', [InscripcionController::class,'buscarPostulante'])->name('admin.inscripciones.buscar')->middleware('auth', 'can:admin.inscripciones.create');
Route::get('/admin/inscripciones/ajax/obtener/{id}', [InscripcionController::class,'obtenerPostulante'])->name('admin.inscripciones.obtener')->middleware('auth', 'can:admin.inscripciones.create');
Route::post('/admin/inscripciones', [InscripcionController::class,'store'])->name('admin.inscripciones.store')->middleware('auth', 'can:admin.inscripciones.store');
Route::post('/admin/inscripciones/paypal-email', [InscripcionController::class,'sendPaypalEmail'])->name('admin.inscripciones.sendPaypalEmail')->middleware('auth', 'can:admin.inscripciones.store');
Route::get('/admin/inscripciones/{inscripcion}', [InscripcionController::class,'show'])->name('admin.inscripciones.show')->middleware('auth', 'can:admin.inscripciones.show');
Route::get('/admin/inscripciones/{inscripcion}/edit', [InscripcionController::class,'edit'])->name('admin.inscripciones.edit')->middleware('auth', 'can:admin.inscripciones.edit');
Route::put('/admin/inscripciones/{inscripcion}', [InscripcionController::class,'update'])->name('admin.inscripciones.update')->middleware('auth', 'can:admin.inscripciones.update');
Route::delete('/admin/inscripciones/{inscripcion}', [InscripcionController::class,'destroy'])->name('admin.inscripciones.destroy')->middleware('auth', 'can:admin.inscripciones.destroy');

//Rutas para pago con PayPal
Route::post('/paypal/pago', [PayPalController::class, 'pago'])->name('web.paypal.pago');
Route::get('/paypal/gracias', [PayPalController::class, 'gracias'])->name('web.paypal.gracias');
Route::get('/paypal/cancelar', [PayPalController::class, 'cancelar'])->name('web.paypal.cancelar');

// Rutas para Historial de Pagos
Route::get('/admin/pagos', [PagoController::class, 'index'])->name('admin.pagos.index')->middleware('auth', 'can:admin.pagos.index');

// Rutas para Grupos
Route::get('/admin/grupos', [GrupoController::class,'index'])->name('admin.grupos.index')->middleware('auth', 'can:admin.grupos.index');
Route::post('/admin/grupos', [GrupoController::class,'store'])->name('admin.grupos.store')->middleware('auth', 'can:admin.grupos.store');
// Rutas específicas para horarios de grupos (colocadas antes de la ruta con {grupo} para evitar conflictos)
Route::get('admin/grupos/horariosgrupos', [GrupoController::class, 'horariosGrupo'])->name('admin.grupos.horariosgrupos')->middleware('auth', 'can:admin.grupos.horariosgrupos');
Route::get('/admin/grupos/{grupo}/horario', [GrupoController::class, 'showhorario'])->name('admin.grupos.showhorario')->middleware('auth', 'can:admin.grupos.showhorario');
Route::get('/admin/grupos/{grupo}', [GrupoController::class,'show'])->name('admin.grupos.show')->middleware('auth', 'can:admin.grupos.show');
Route::get('/admin/grupos/{grupo}/edit', [GrupoController::class,'edit'])->name('admin.grupos.edit')->middleware('auth', 'can:admin.grupos.edit');
Route::put('/admin/grupos/{grupo}', [GrupoController::class,'update'])->name('admin.grupos.update')->middleware('auth', 'can:admin.grupos.update');
Route::delete('/admin/grupos/{grupo}', [GrupoController::class,'destroy'])->name('admin.grupos.destroy')->middleware('auth', 'can:admin.grupos.destroy');



// Rutas para Carga Horaria
Route::get('/admin/carga_horaria', [CargaHorariaController::class,'index'])->name('admin.carga_horaria.index')->middleware('auth', 'can:admin.carga_horaria.index');
Route::get('/admin/carga_horaria/docente/{docente}', [CargaHorariaController::class,'showDocente'])->name('admin.carga_horaria.show_docente')->middleware('auth', 'can:admin.carga_horaria.show');
Route::get('/admin/carga_horaria/{carga_horaria}', [CargaHorariaController::class,'show'])->name('admin.carga_horaria.show')->middleware('auth', 'can:admin.carga_horaria.show');

// Rutas para Exámenes
Route::get('/admin/examenes', [ExamenController::class,'index'])->name('admin.examenes.index')->middleware('auth', 'can:admin.examenes.index');
Route::get('/admin/examenes/create', [ExamenController::class,'create'])->name('admin.examenes.create')->middleware('auth', 'can:admin.examenes.create');
Route::post('/admin/examenes', [ExamenController::class,'store'])->name('admin.examenes.store')->middleware('auth', 'can:admin.examenes.store');
Route::get('/admin/examenes/{examen}/edit', [ExamenController::class,'edit'])->name('admin.examenes.edit')->middleware('auth', 'can:admin.examenes.edit');
Route::put('/admin/examenes/{examen}', [ExamenController::class,'update'])->name('admin.examenes.update')->middleware('auth', 'can:admin.examenes.update');
Route::delete('/admin/examenes/{examen}', [ExamenController::class,'destroy'])->name('admin.examenes.destroy')->middleware('auth', 'can:admin.examenes.destroy');

// Rutas para Notas de Examen
Route::get('/admin/notas_examen/inscritos', [NotaExamenController::class,'getInscritosPorGrupo'])->name('admin.notas_examen.inscritos')->middleware('auth', 'can:admin.notas_examen.inscritos');
Route::get('/admin/notas_examen/create', [NotaExamenController::class,'create'])->name('admin.notas_examen.create')->middleware('auth', 'can:admin.notas_examen.create');
Route::post('/admin/notas_examen', [NotaExamenController::class,'store'])->name('admin.notas_examen.store')->middleware('auth', 'can:admin.notas_examen.store');


// Rutas para Asistencias
Route::get('/admin/asistencias', [AsistenciaController::class,'index'])->name('admin.asistencias.index')->middleware('auth', 'can:admin.asistencias.index');
Route::get('/admin/asistencias/create', [AsistenciaController::class,'index'])->name('admin.asistencias.create')->middleware('auth', 'can:admin.asistencias.create');
Route::get('/admin/asistencias/postulantes', [AsistenciaController::class,'getInscritosPorGrupo'])->name('admin.asistencias.postulantes')->middleware('auth', 'can:admin.asistencias.postulantes');
Route::post('/admin/asistencias', [AsistenciaController::class,'store'])->name('admin.asistencias.store')->middleware('auth', 'can:admin.asistencias.store');

// Rutas para Postulantes en Grupos
Route::get('/admin/postulante-grupos', [PostulanteGrupoController::class,'index'])->name('admin.postulante_grupos.index')->middleware('auth', 'can:admin.postulante_grupos.index');
Route::get('/admin/postulante-grupos/{id}', [PostulanteGrupoController::class,'show'])->name('admin.postulante_grupos.show')->middleware('auth', 'can:admin.postulante_grupos.show');

// Rutas para Promedios de Examen
Route::get('/admin/promedios_examen', [PromedioExamenController::class,'index'])->name('admin.promedios_examen.index')->middleware('auth', 'can:admin.promedios_examen.index');
Route::get('/admin/promedios_examen/{id}', [PromedioExamenController::class,'show'])->name('admin.promedios_examen.show')->middleware('auth', 'can:admin.promedios_examen.show');

// Rutas para Resultados Finales
Route::get('/admin/resultados-finales', [ResultadoController::class,'index'])->name('admin.resultados.index')->middleware('auth', 'can:admin.resultados.index');
Route::get('/admin/resultados-finales/admitidos', [ResultadoController::class,'admitidos'])->name('admin.resultados.admitidos')->middleware('auth', 'can:admin.resultados.admitidos');
Route::get('/admin/resultados-finales/no-admitidos', [ResultadoController::class,'noAdmitidos'])->name('admin.resultados.no_admitidos')->middleware('auth', 'can:admin.resultados.no_admitidos');
Route::post('/admin/resultados-finales/cerrar', [ResultadoController::class,'cerrar'])->name('admin.resultados.cerrar')->middleware('auth', 'can:admin.resultados.index');

// RUTA DE DEBUG (temporal - eliminar después de testing)
Route::get('/admin/resultados-finales/debug/{inscripcionId}', [ResultadoController::class,'debug'])->name('admin.resultados.debug')->middleware('auth', 'can:admin.resultados.index');

// Rutas para Reportes
Route::get('/admin/reportes', [ReporteController::class,'index'])->name('admin.reportes.index')->middleware('auth', 'can:admin.reportes.index');
Route::post('/admin/reportes/export', [ReporteController::class,'export'])->name('admin.reportes.export')->middleware('auth', 'can:admin.reportes.export');
