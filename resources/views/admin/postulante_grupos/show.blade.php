@extends('adminlte::page')

@section('content_header')
    <h1><b>Postulantes del Grupo {{ $grupo->nombre }}</b></h1>
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
                            <strong>Gestión:</strong> {{ $gestionActiva->semestre }}/{{ $gestionActiva->anio }}
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
                            <strong>Total Inscritos:</strong> <span class="badge badge-info">{{ $inscritos->count() }}</span>
                            
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Listado de Postulantes --}}
    <div class="col-md-12">
        <div class="card card-outline card-success">
            <div class="card-header bg-success">
                <h3 class="card-title"><b>Postulantes Asignados</b></h3>
            </div>
            <div class="card-body">
                @if($inscritos->isEmpty())
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
                                    <th style="width: 20%;">Código Postulante</th>
                                    <th style="width: 50%;">Nombre Completo</th>
                                    <th style="width: 25%; text-align: center;">Acciones</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($inscritos as $inscripcion)
                                    <tr>
                                        <td style="text-align: center;">{{ $loop->iteration }}</td>
                                        <td>
                                            <code>{{ $inscripcion->postulante_codigo }}</code>
                                        </td>
                                        <td>
                                            <strong>{{ $inscripcion->postulante->nombre ?? 'N/A' }} {{ $inscripcion->postulante->apellidos ?? '' }}</strong>
                                        </td>
                                        <td style="text-align: center;">
                                            <a href="{{ route('admin.postulantes.show', $inscripcion->postulante) }}" 
                                               class="btn btn-xs btn-info" 
                                               title="Ver detalle del postulante">
                                                <i class="fas fa-eye"></i> Ver
                                            </a>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Botón Volver --}}
    <div class="col-md-12">
        <a href="{{ route('admin.postulante_grupos.index') }}" class="btn btn-secondary">VOLVER</a>
    </div>
</div>
@stop
