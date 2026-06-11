@extends('adminlte::page')

@section('content_header')
    <h1><b>Promedios de Exámenes - Grupo {{ $grupo->nombre }}</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    {{-- Información del Grupo --}}
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title"><b>Detalles del Grupo</b></h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <p>
                            <strong>Grupo:</strong> {{ $grupo->nombre }}<br>
                            <strong>Gestión:</strong> {{ $gestionActiva->semestre }} - {{ $gestionActiva->año }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p>
                            <strong>Modalidad:</strong> {{ $grupo->modalidad->nombre ?? 'N/A' }}<br>
                            <strong>Turno:</strong> {{ $grupo->turno->nombre ?? 'N/A' }}
                        </p>
                    </div>
                    <div class="col-md-3">
                        <p>
                            <strong>Cupos Totales:</strong> {{ $grupo->cupos ?? 'N/A' }}<br>
                            <strong>Total Inscritos:</strong> <span class="badge badge-info">{{ count($postulantesProcesados) }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Tabla de Promedios de Exámenes --}}
    <div class="col-md-12">
        <div class="card card-outline card-success">
            <div class="card-header bg-success">
                <h3 class="card-title"><b>Promedios de Exámenes por Postulante</b></h3>
            </div>
            <div class="card-body">
                @if(empty($postulantesProcesados))
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle mr-2"></i>
                        <strong>Información:</strong> No hay postulantes asignados a este grupo.
                    </div>
                @else
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-hover table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th style="width: 5%; text-align: center;">Nro</th>
                                    <th style="width: 25%;">Código Postulante</th>
                                    <th style="width: 25%;">Nombre Completo</th>
                                    @foreach($examenes as $examen)
                                        <th style="width: 12%; text-align: center;">
                                            Examen {{ $examen->nro_examen }}<br>
                                            <small>({{ $examen->ponderacion }}%)</small>
                                        </th>
                                    @endforeach
                                    <th style="width: 15%; text-align: center;"><strong>Promedio General</strong></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($postulantesProcesados as $postulante)
                                    <tr>
                                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                                        <td>
                                            <code>{{ $postulante['codigo'] }}</code>
                                        </td>
                                        <td>
                                            <strong>{{ $postulante['nombre'] }}</strong>
                                        </td>
                                        @foreach($examenes as $examen)
                                            <td style="text-align: center;">
                                                @if(isset($postulante['examenes'][$examen->nro_examen]))
                                                    <strong>{{ $postulante['examenes'][$examen->nro_examen]['suma_ponderada'] }}</strong>
                                                @else
                                                    <span class="text-danger">—</span>
                                                @endif
                                            </td>
                                        @endforeach
                                        <td style="text-align: center;">
                                            @php
                                                $promedio = $postulante['promedioGeneral'];
                                                $colorClass = $promedio >= 60 ? 'bg-success' : 'bg-danger';
                                            @endphp
                                            <span class="badge {{ $colorClass }} text-white" style="font-size: 14px; padding: 8px 12px;">
                                                {{ $promedio }}
                                            </span>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Apartado de Explicación --}}
                    <div class="alert alert-info mt-3">
                        <strong><i class="fas fa-info-circle mr-2"></i>Explicación:</strong>
                        <ul class="mb-0" style="margin-top: 10px;">
                            <li><strong>Nota por Examen:</strong> Se obtiene sumando las notas ponderadas de las materias de ese examen.</li>
                            <li><strong>Promedio General:</strong> Suma de (nota por examen × ponderación del examen). Se muestra en <span class="badge bg-success">verde</span> si es ≥60 o en <span class="badge bg-danger">rojo</span> si es <60.</li>
                        </ul>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Botón Volver --}}
    <div class="col-md-12">
        <a href="{{ route('admin.promedios_examen.index') }}" class="btn btn-secondary">VOLVER</a>
    </div>
</div>
@stop
