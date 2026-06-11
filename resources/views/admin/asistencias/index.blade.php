@extends('adminlte::page')

@section('content_header')
    <h1><b>Registrar Asistencias</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    {{-- Mensaje de error si no hay gestión activa --}}
    @if(!$gestionActiva)
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Atención:</strong> {{ $mensaje ?? 'No hay gestiones activas. No se pueden registrar asistencias hasta que exista una gestión activa.' }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @elseif(!empty($mensaje))
        <div class="col-md-12">
            <div class="alert alert-info alert-dismissible fade show" role="alert">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Información:</strong> {{ $mensaje }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @endif

    @if($gestionActiva && isset($docente) && count($grupos) > 0)
        {{-- Botón para administrador y docente: Marcar todos presentes --}}
        @if(auth()->user()->hasRole('ADMINISTRADOR') || auth()->user()->hasRole('DOCENTE'))
        <div class="col-md-12 mb-3">
            <div class="card card-outline card-warning">
                <div class="card-header bg-warning">
                    <h3 class="card-title"><b>Herramientas Rápidas</b></h3>
                </div>
                <div class="card-body">
                    <button type="button" class="btn btn-warning" onclick="marcarTodosPresentes()">
                        <i class="fas fa-check-circle mr-2"></i>Marcar Todos Como Presentes
                    </button>
                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle mr-1"></i>
                        Marca automáticamente a todos los inscritos como presentes en todos los grupos.
                    </small>
                </div>
            </div>
        </div>
        @endif

        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><b>Seleccionar Grupo</b></h3>
                </div>
                <div class="card-body">
                    <form id="formFiltros">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="grupo_id">Grupo - Modalidad </label>
                                    <select id="grupo_id" name="grupo_id" class="form-control" required>
                                        <option value="">-- Seleccione un grupo --</option>
                                        @foreach($grupos as $grupo)
                                            <option value="{{ $grupo->id }}" data-modalidad="{{ $grupo->modalidad->nombre ?? 'N/A' }}">
                                                {{ $grupo->nombre }} - {{ $grupo->modalidad->nombre ?? 'N/A' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="materia">Materia</label>
                                    <input type="text" id="materia" class="form-control" 
                                           value="{{ $materiaDocente?->nombre ?? 'No asignada' }}" 
                                           readonly 
                                           style="background-color: #f5f5f5;">
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="button" id="btnCargar" class="btn btn-primary" onclick="cargarPostulantes()">
                                    <i class="fas fa-sync-alt mr-2"></i>Cargar Postulantes
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Formulario de asistencias --}}
        <div class="col-md-12">
            <div class="card card-outline card-success" id="cardAsistencias" style="display: none;">
                <div class="card-header bg-success">
                    <h3 class="card-title"><b>Registro de Asistencias</b></h3>
                </div>
                <div class="card-body">
                    <form id="formAsistencias" method="POST" action="{{ route('admin.asistencias.store') }}">
                        @csrf
                        <input type="hidden" id="grupo_id_form" name="grupo_id">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm" id="tablaAsistencias">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 5%; text-align: center;">Nro</th>
                                        <th>Nombre del Estudiante</th>
                                        <th style="width: 20%; text-align: center;">Asistencia</th>
                                        <th style="width: 10%; text-align: center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyAsistencias">
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <a href="{{ route('home') }}" class="btn btn-secondary">CANCELAR</a>
                                <button type="submit" class="btn btn-success">GUARDAR</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @elseif($gestionActiva && isset($docente) && count($grupos) == 0)
        <div class="col-md-12">
            <div class="alert alert-warning">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Información:</strong> No tiene grupos asignados en la gestión activa.
            </div>
        </div>
    @endif
</div>

@stop

@section('js')
<script>
function cargarPostulantes() {
    const grupoId = document.getElementById('grupo_id').value;

    if (!grupoId) {
        alert('Por favor seleccione un grupo');
        return;
    }

    // Mostrar spinner de carga
    document.getElementById('btnCargar').disabled = true;
    document.getElementById('btnCargar').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Cargando...';

    const url = '{{ route("admin.asistencias.postulantes") }}?grupo_id=' + grupoId;

    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
        } else if (!data.inscritos || data.inscritos.length === 0) {
            alert('No se encontraron postulantes para este grupo');
        } else {
            llenarTablaAsistencias(data.inscritos);
            document.getElementById('grupo_id_form').value = grupoId;
            document.getElementById('cardAsistencias').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al cargar postulantes: ' + error.message);
    })
    .finally(() => {
        document.getElementById('btnCargar').disabled = false;
        document.getElementById('btnCargar').innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Cargar Postulantes';
    });
}

function llenarTablaAsistencias(inscritos) {
    const tbody = document.getElementById('tbodyAsistencias');
    tbody.innerHTML = '';

    inscritos.forEach((inscrito, index) => {
        const fila = document.createElement('tr');
        
        fila.innerHTML = `
            <td style="text-align: center;">${index + 1}</td>
            <td>${inscrito.nombre}</td>
            <td style="text-align: center;">
                <select name="asistencias[${index}][estado]" class="form-control form-control-sm" required>
                    <option value="Presente" ${inscrito.estado === 'Presente' ? 'selected' : ''}>Presente</option>
                    <option value="Falta" ${inscrito.estado === 'Falta' ? 'selected' : ''}>Falta</option>
                    <option value="Licencia" ${inscrito.estado === 'Licencia' ? 'selected' : ''}>Licencia</option>
                </select>
                <input type="hidden" name="asistencias[${index}][codigo_postulante]" value="${inscrito.codigo_postulante}">
            </td>
            <td style="text-align: center;">
                <button type="button" class="btn btn-xs btn-info" onclick="editarAsistencia(this)">
                    <i class="fas fa-edit"></i>
                </button>
            </td>
        `;

        tbody.appendChild(fila);
    });
}

function editarAsistencia(boton) {
    // Obtener la fila del estudiante
    const fila = boton.closest('tr');
    const select = fila.querySelector('select');
    
    // Enfocar y abrir el select para editar
    select.focus();
}

function marcarTodosPresentes() {
    if (!confirm('¿Estás seguro de que deseas marcar a todos los inscritos como presentes en todos los grupos? Esta acción no se puede deshacer.')) {
        return;
    }

    const btnMarcar = event.target;
    btnMarcar.disabled = true;
    btnMarcar.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Marcando presentes...';

    const url = '{{ route("admin.asistencias.marcar_presentes") }}';

    fetch(url, {
        method: 'POST',
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.error) {
            alert('Error: ' + data.error);
        } else if (data.success) {
            const mensaje = `✓ Asistencias registradas exitosamente!\n\n` +
                           `Inscritos: ${data.conteo.inscritos}\n` +
                           `Grupos: ${data.conteo.grupos}\n` +
                           `Total de asistencias: ${data.conteo.asistencias_totales}\n\n` +
                           `${data.message}`;
            alert(mensaje);
            // Recargar la página después de 2 segundos
            setTimeout(() => {
                location.reload();
            }, 2000);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('Error al marcar presentes: ' + error.message);
    })
    .finally(() => {
        btnMarcar.disabled = false;
        btnMarcar.innerHTML = '<i class="fas fa-check-circle mr-2"></i>Marcar Todos Como Presentes';
    });
}
</script>
@stop
