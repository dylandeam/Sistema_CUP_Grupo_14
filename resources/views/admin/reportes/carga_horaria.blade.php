@extends('adminlte::page')

@section('content_header')
    <div class="row">
        <div class="col-md-11">
            <h1><b>📅 Reporte de Carga Horaria de Docentes</b></h1>
        </div>
        <div class="col-md-1 text-right">
            <a href="{{ route('admin.reportes.index') }}" class="btn btn-secondary btn-sm">
                <i class="fas fa-arrow-left"></i> Volver
            </a>
        </div>
    </div>
    <hr>
@stop

@section('content')
<div class="row">
    @if(!$gestionActiva)
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Atención:</strong> No hay gestiones activas. No se puede generar reporte de carga horaria.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @else
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Filtros de Búsqueda</h3>
                </div>
                <div class="card-body">
                    <form method="GET" action="{{ route('admin.reportes.carga_horaria') }}" id="filtroForm">
                        <div class="row">
                            <!-- Tipo de Vista -->
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipo"><b>Tipo de Vista</b></label>
                                    <select name="tipo" id="tipo" class="form-control form-control-sm" onchange="actualizarVista()">
                                        <option value="general" {{ $tipoVista === 'general' ? 'selected' : '' }}>
                                            📊 General (Todos los Docentes)
                                        </option>
                                        <option value="semanal" {{ $tipoVista === 'semanal' ? 'selected' : '' }}>
                                            📆 Semanal (por Docente)
                                        </option>
                                        <option value="diaria" {{ $tipoVista === 'diaria' ? 'selected' : '' }}>
                                            📋 Diaria (por Docente)
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <!-- Seleccionar Docente -->
                            <div class="col-md-4" id="contenedor-docente">
                                <div class="form-group">
                                    <label for="docente"><b>Docente</b>
                                        <span class="text-danger" id="requerido-docente" style="display: {{ $tipoVista === 'general' ? 'none' : 'inline' }}">(*)</span>
                                    </label>
                                    <select name="docente" id="docente" class="form-control form-control-sm">
                                        <option value="">-- Selecciona un docente --</option>
                                        @foreach ($docentes as $doc)
                                            <option value="{{ $doc->codigo }}" 
                                                {{ $docente_codigo === $doc->codigo ? 'selected' : '' }}>
                                                {{ $doc->nombre }} {{ $doc->apellido }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <!-- Seleccionar Fecha -->
                            <div class="col-md-4" id="contenedor-fecha" style="display: {{ in_array($tipoVista, ['semanal', 'diaria']) ? 'block' : 'none' }}">
                                <div class="form-group">
                                    <label for="fecha"><b>Fecha</b></label>
                                    <input type="date" name="fecha" id="fecha" class="form-control form-control-sm" 
                                        value="{{ $fecha }}">
                                </div>
                            </div>

                            <!-- Botón Buscar -->
                            <div class="col-md-12" style="margin-top: 10px;">
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-search"></i> Generar Reporte
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Mostrar errores -->
        @if($error)
            <div class="col-md-12 mt-3">
                <div class="alert alert-warning alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle"></i> {{ $error }}
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif

        <!-- VISTA GENERAL -->
        @if ($tipoVista === 'general' && $cargasHorarias->count() > 0)
            <div class="col-md-12 mt-3">
                <div class="card card-outline card-success">
                    <div class="card-header">
                        <h3 class="card-title">📊 Vista General - Carga Horaria por Docente</h3>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th>Docente</th>
                                        <th>Materia</th>
                                        <th>Grupo</th>
                                        <th>Turno</th>
                                        <th>Horario</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($cargasHorarias as $codigo => $cargas)
                                        @php $docente = $cargas->first()->docente; @endphp
                                        @foreach ($cargas as $index => $carga)
                                            <tr>
                                                @if ($index === 0)
                                                    <td rowspan="{{ $cargas->count() }}" class="font-weight-bold align-middle">
                                                        {{ $docente->nombre }} {{ $docente->apellido }}
                                                    </td>
                                                @endif
                                                <td>{{ $carga->materia?->nombre ?? 'N/A' }}</td>
                                                <td>{{ $carga->grupo?->nombre ?? 'N/A' }}</td>
                                                <td>{{ $carga->grupo?->turno?->nombre ?? 'N/A' }}</td>
                                                <td>
                                                    {{ substr($carga->horario?->hora_inicio ?? 'N/A', 0, 5) }} - 
                                                    {{ substr($carga->horario?->hora_fin ?? 'N/A', 0, 5) }}
                                                </td>
                                            </tr>
                                        @endforeach
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <!-- Estadísticas -->
                        <div class="row mt-3">
                            <div class="col-md-3">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-number">{{ $cargasHorarias->count() }}</span>
                                        <span class="info-box-text">Docentes Activos</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="info-box bg-light">
                                    <div class="info-box-content">
                                        <span class="info-box-number">{{ $cargasHorarias->sum(function($c) { return $c->count(); }) }}</span>
                                        <span class="info-box-text">Asignaciones Totales</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- VISTA SEMANAL -->
        @if ($tipoVista === 'semanal' && $turnosPorDia->count() > 0)
            <div class="col-md-12 mt-3">
                <div class="card card-outline card-info">
                    <div class="card-header">
                        <h3 class="card-title">
                            📆 Vista Semanal - {{ $docentes->firstWhere('codigo', $docente_codigo)?->nombre ?? 'Docente' }}
                            {{ $docentes->firstWhere('codigo', $docente_codigo)?->apellido ?? '' }}
                        </h3>
                    </div>
                    <div class="card-body">
                        <!-- Horas Trabajadas -->
                        <div class="alert alert-info mb-3">
                            <i class="fas fa-clock"></i> <strong>Total de Horas Trabajadas:</strong> {{ $horasTrabajadas }} horas
                        </div>

                        <div class="table-responsive">
                            <table class="table table-sm table-bordered table-hover">
                                <thead class="thead-light">
                                    <tr>
                                        <th style="width: 120px;">Horario</th>
                                        <th>Lunes</th>
                                        <th>Martes</th>
                                        <th>Miércoles</th>
                                        <th>Jueves</th>
                                        <th>Viernes</th>
                                        <th>Sábado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($turnosPorDia as $fila)
                                        <tr>
                                            <td class="font-weight-bold">{{ $fila['hora_display'] }}</td>
                                            @foreach (['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado'] as $dia)
                                                <td>
                                                    @if ($fila[$dia])
                                                        @foreach ($fila[$dia] as $item)
                                                            <div style="background-color: #e3f2fd; padding: 8px; border-radius: 4px; margin-bottom: 5px;">
                                                                <strong style="color: #1976d2;">{{ $item['materia'] ?? 'N/A' }}</strong><br>
                                                                <small style="color: #555;">{{ $item['grupo'] ?? 'N/A' }}</small>
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">--</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        @endif

        <!-- VISTA DIARIA -->
        @if ($tipoVista === 'diaria')
            <div class="col-md-12 mt-3">
                <div class="card card-outline card-warning">
                    <div class="card-header">
                        <h3 class="card-title">
                            📋 Vista Diaria - {{ $docentes->firstWhere('codigo', $docente_codigo)?->nombre ?? 'Docente' }}
                            {{ $docentes->firstWhere('codigo', $docente_codigo)?->apellido ?? '' }}
                            <br><small>({{ \Carbon\Carbon::parse($fecha)->format('d/m/Y - dddd') }})</small>
                        </h3>
                    </div>
                    <div class="card-body">
                        @if ($horariosOrdenados->count() > 0)
                            <!-- Horas Trabajadas -->
                            <div class="alert alert-info mb-3">
                                <i class="fas fa-clock"></i> <strong>Total de Horas Trabajadas:</strong> {{ $horasTrabajadas }} horas
                            </div>

                            <div class="table-responsive">
                                <table class="table table-sm table-bordered table-hover">
                                    <thead class="thead-light">
                                        <tr>
                                            <th style="width: 120px;">Horario</th>
                                            <th>Materia(s)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($horariosOrdenados as $horario)
                                            <tr class="{{ count($horario['materias']) > 0 ? 'table-info' : '' }}">
                                                <td class="font-weight-bold">{{ $horario['hora_display'] }}</td>
                                                <td>
                                                    @if (count($horario['materias']) > 0)
                                                        @foreach ($horario['materias'] as $item)
                                                            <div style="margin-bottom: 8px;">
                                                                <strong>{{ $item['materia'] ?? 'N/A' }}</strong> ({{ $item['grupo'] ?? 'N/A' }})
                                                            </div>
                                                        @endforeach
                                                    @else
                                                        <span class="text-muted">---</span>
                                                    @endif
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>

                            <!-- Resumen del día -->
                            <div class="row mt-3">
                                <div class="col-md-3">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-number">
                                                {{ collect($horariosOrdenados)->where('materias', '!=', [])->count() }}
                                            </span>
                                            <span class="info-box-text">Horarios con Clases</span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-3">
                                    <div class="info-box bg-light">
                                        <div class="info-box-content">
                                            <span class="info-box-number">
                                                {{ collect($horariosOrdenados)->where('materias', '=', [])->count() }}
                                            </span>
                                            <span class="info-box-text">Horarios Libres</span>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @else
                            <div class="alert alert-info">
                                <i class="fas fa-info-circle"></i> No hay clases programadas para este docente en esta fecha.
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        @endif

        <!-- Mensaje cuando no hay datos y se buscó algo -->
        @if (!$error && (($tipoVista === 'general' && $cargasHorarias->isEmpty()) || 
            (in_array($tipoVista, ['semanal', 'diaria']) && $turnosPorDia->isEmpty() && $horariosOrdenados->isEmpty() && $docente_codigo)))
            <div class="col-md-12 mt-3">
                <div class="alert alert-info alert-dismissible fade show" role="alert">
                    <i class="fas fa-info-circle"></i>
                    <strong>Sin Datos:</strong> No se encontraron registros de carga horaria para los criterios seleccionados.
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
            </div>
        @endif
    @endif
</div>
@stop

@section('js')
<script>
    function actualizarVista() {
        const tipo = document.getElementById('tipo').value;
        const contenedorFecha = document.getElementById('contenedor-fecha');
        const requeridoDocente = document.getElementById('requerido-docente');
        const selectDocente = document.getElementById('docente');

        // Mostrar/ocultar fecha según vista
        if (tipo === 'general') {
            contenedorFecha.style.display = 'none';
            requeridoDocente.style.display = 'none';
            selectDocente.removeAttribute('required');
        } else {
            contenedorFecha.style.display = 'block';
            requeridoDocente.style.display = 'inline';
            selectDocente.setAttribute('required', 'required');
        }
    }

    // Inicializar en carga
    document.addEventListener('DOMContentLoaded', function() {
        actualizarVista();
    });

    // Enviar formulario al cambiar tipo de vista
    document.getElementById('tipo').addEventListener('change', function() {
        document.getElementById('filtroForm').submit();
    });
</script>
@stop

