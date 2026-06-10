@extends('adminlte::page')

@section('content_header')
    <h1><b>Registrar Notas de Examen</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    {{-- Mensaje de error si no hay gestión activa --}}
    @if(!$gestionActiva)
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Atención:</strong> {{ $mensaje ?? 'No hay gestiones activas. No se pueden registrar notas hasta que exista una gestión activa.' }}
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

    @if($gestionActiva && isset($docente) && count($cargasHorarias) > 0)
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header bg-primary">
                    <h3 class="card-title"><b>Seleccionar Examen, Materia y Grupo</b></h3>
                </div>
                <div class="card-body">
                    <form id="formFiltros">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="examen_id">Nro. de Examen <span style="color: red;">*</span></label>
                                    <select id="examen_id" name="examen_id" class="form-control" required>
                                        <option value="">-- Seleccione un examen --</option>
                                        @foreach($examenes as $examen)
                                            <option value="{{ $examen->id }}">
                                                Examen {{ $examen->nro_examen }} - {{ $examen->fecha }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="materia_grupo_id">Materia - Grupo <span style="color: red;">*</span></label>
                                    <select id="materia_grupo_id" name="materia_grupo_id" class="form-control" required>
                                        <option value="">-- Seleccione una materia y grupo --</option>
                                        @foreach($cargasHorarias as $carga)
                                            <option value="{{ $carga->id }}" 
                                                    data-materia-id="{{ $carga->materia->id }}" 
                                                    data-grupo-id="{{ $carga->grupo->id }}">
                                                {{ $carga->materia->nombre }} - {{ $carga->grupo->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label>&nbsp;</label>
                                    <button type="button" id="btnCargar" class="btn btn-primary btn-block" onclick="cargarInscritos()">
                                        <i class="fas fa-sync-alt mr-2"></i>Cargar Inscritos
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- Formulario de notas --}}
        <div class="col-md-12">
            <div class="card card-outline card-success" id="cardNotas" style="display: none;">
                <div class="card-header bg-success">
                    <h3 class="card-title"><b>Notas de Inscritos</b></h3>
                </div>
                <div class="card-body">
                    <form id="formNotas" method="POST" action="{{ route('admin.notas_examen.store') }}">
                        @csrf
                        <input type="hidden" id="examen_id_form" name="examen_id">
                        <input type="hidden" id="materia_id_form" name="materia_id">
                        <input type="hidden" id="grupo_id_form" name="grupo_id">

                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-sm" id="tablaNotas">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 5%; text-align: center;">Nro</th>
                                        <th>Nombre del Estudiante</th>
                                        <th style="width: 15%; text-align: center;">ID Inscripción</th>
                                        <th style="width: 15%; text-align: center;">Nota Materia</th>
                                        <th style="width: 15%; text-align: center;">Nota Ponderada</th>
                                    </tr>
                                </thead>
                                <tbody id="tbodyNotas">
                                </tbody>
                            </table>
                        </div>

                        <div class="row mt-3">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-save mr-2"></i>Guardar Notas
                                </button>
                                <a href="{{ route('home') }}" class="btn btn-secondary">
                                    <i class="fas fa-times mr-2"></i>Cancelar
                                </a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @elseif($gestionActiva && isset($docente) && count($cargasHorarias) == 0)
        <div class="col-md-12">
            <div class="alert alert-warning">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Información:</strong> No tiene materias asignadas en la gestión activa.
            </div>
        </div>
    @endif
</div>

@stop

@section('js')
<script>
function cargarInscritos() {
    const examenId = document.getElementById('examen_id').value;
    const materiaGrupoId = document.getElementById('materia_grupo_id').value;

    if (!examenId || !materiaGrupoId) {
        alert('Por favor seleccione examen y materia-grupo');
        return;
    }

    const select = document.getElementById('materia_grupo_id');
    const selectedOption = select.options[select.selectedIndex];
    const materiaId = selectedOption.getAttribute('data-materia-id');
    const grupoId = selectedOption.getAttribute('data-grupo-id');

    console.log('Parámetros:', { examenId, materiaId, grupoId });

    if (!materiaId || !grupoId) {
        alert('Error: No se pudieron obtener los datos de la materia y grupo');
        console.error('Datos no encontrados - materiaId:', materiaId, 'grupoId:', grupoId);
        return;
    }

    // Mostrar spinner de carga
    document.getElementById('btnCargar').disabled = true;
    document.getElementById('btnCargar').innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i>Cargando...';

    const url = '{{ route("admin.notas_examen.inscritos") }}?examen_id=' + examenId + '&materia_id=' + materiaId + '&grupo_id=' + grupoId;
    console.log('URL de petición:', url);

    fetch(url, {
        headers: {
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
        }
    })
    .then(response => {
        console.log('Response status:', response.status);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        console.log('Datos recibidos:', data);
        if (data.error) {
            alert('Error: ' + data.error);
        } else if (!data.inscritos || data.inscritos.length === 0) {
            alert('No se encontraron inscritos para esta selección');
        } else {
            llenarTablaNotas(data.inscritos, materiaId);
            document.getElementById('examen_id_form').value = examenId;
            document.getElementById('materia_id_form').value = materiaId;
            document.getElementById('grupo_id_form').value = grupoId;
            document.getElementById('cardNotas').style.display = 'block';
        }
    })
    .catch(error => {
        console.error('Error completo:', error);
        alert('Error al cargar inscritos: ' + error.message);
    })
    .finally(() => {
        document.getElementById('btnCargar').disabled = false;
        document.getElementById('btnCargar').innerHTML = '<i class="fas fa-sync-alt mr-2"></i>Cargar Inscritos';
    });
}

function llenarTablaNotas(inscritos, materiaId) {
    const tbody = document.getElementById('tbodyNotas');
    tbody.innerHTML = '';

    inscritos.forEach((inscrito, index) => {
        const fila = document.createElement('tr');
        const notaPonderadaCalculada = inscrito.nota_materia 
            ? (inscrito.nota_materia * inscrito.ponderacion).toFixed(2)
            : '';

        fila.innerHTML = `
            <td style="text-align: center;">${index + 1}</td>
            <td>${inscrito.nombre}</td>
            <td style="text-align: center;">${inscrito.id_inscripcion}</td>
            <td>
                <input type="number" 
                       class="form-control form-control-sm notaMateria" 
                       name="notas[${index}][nota_materia]" 
                       value="${inscrito.nota_materia}" 
                       step="0.01" 
                       min="0" 
                       max="100"
                       data-ponderacion="${inscrito.ponderacion}"
                       onchange="calcularNotaPonderada(this)">
                <input type="hidden" name="notas[${index}][id_inscripcion]" value="${inscrito.id_inscripcion}">
            </td>
            <td>
                <input type="text" 
                       class="form-control form-control-sm notaPonderada" 
                       value="${notaPonderadaCalculada}" 
                       readonly 
                       style="background-color: #f5f5f5;">
            </td>
        `;

        tbody.appendChild(fila);
    });
}

function calcularNotaPonderada(input) {
    const fila = input.closest('tr');
    const notaMateria = parseFloat(input.value) || 0;
    const ponderacion = parseFloat(input.dataset.ponderacion) || 0;
    const notaPonderada = (notaMateria * ponderacion).toFixed(2);
    
    const inputPonderada = fila.querySelector('.notaPonderada');
    inputPonderada.value = notaPonderada;
}

// Permitir Enter en el select para cargar inscritos
document.getElementById('materia_grupo_id')?.addEventListener('keypress', function(e) {
    if (e.key === 'Enter') {
        e.preventDefault();
        cargarInscritos();
    }
});
</script>
@stop

@section('css')
<style>
    .form-control-sm {
        font-size: 0.875rem;
    }
    
    .table-sm td {
        padding: 0.5rem;
    }
    
    @media print {
        .card-tools, .btn-group, .btn, .alert, .form-group {
            display: none !important;
        }
    }
</style>
@stop
