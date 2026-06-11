@extends('adminlte::page')

@section('content_header')
    <h1><b>Postulantes Admitidos</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    {{-- Mensaje si no hay gestión activa --}}
    @if(!$gestionActiva)
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Atención:</strong> No hay gestiones activas.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @else
        {{-- Tabla de Admitidos --}}
        <div class="col-md-12">
            <div class="card card-outline card-success">
                <div class="card-header bg-success">
                    <h3 class="card-title"><i class="fas fa-check-circle mr-2"></i>Lista de Postulantes Admitidos</h3>
                    <div class="card-tools">
                        <small class="badge badge-success">Total: {{ $admitidos->count() }}</small>
                    </div>
                </div>
                <div class="card-body">
                    @if($admitidos->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Información:</strong> No hay postulantes admitidos aún.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 5%; text-align: center;">Nro</th>
                                        <th style="width: 15%;">Código Postulante</th>
                                        <th style="width: 30%;">Nombre Completo</th>
                                        <th style="width: 12%; text-align: center;">Estado</th>
                                        <th style="width: 15%; text-align: center;">Promedio General</th>
                                        <th style="width: 23%;">Carrera Asignada</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($admitidos as $resultado)
                                        <tr>
                                            <td style="text-align: center;">{{ $loop->iteration }}</td>
                                            <td>
                                                <code>{{ $resultado->inscripcion->postulante->codigo }}</code>
                                            </td>
                                            <td>
                                                <strong>{{ $resultado->inscripcion->postulante->nombre }} {{ $resultado->inscripcion->postulante->apellidos }}</strong>
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge badge-success">{{ $resultado->estado }}</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <strong>{{ $resultado->promedio_examen }}</strong>
                                            </td>
                                            <td>
                                                @if($resultado->inscripcionCarrera && $resultado->inscripcionCarrera->carrera)
                                                    {{ $resultado->inscripcionCarrera->carrera->nombre }}
                                                @else
                                                    <span class="text-muted">—</span>
                                                @endif
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
            <a href="{{ route('admin.resultados.index') }}" class="btn btn-secondary">VOLVER</a>
        </div>
    @endif
</div>
@stop
