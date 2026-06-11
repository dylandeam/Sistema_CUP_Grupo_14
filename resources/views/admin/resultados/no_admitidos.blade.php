@extends('adminlte::page')

@section('content_header')
    <h1><b>Postulantes No Admitidos</b></h1>
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
        {{-- Tabla de No Admitidos --}}
        <div class="col-md-12">
            <div class="card card-outline card-danger">
                <div class="card-header bg-danger">
                    <h3 class="card-title"><i class="fas fa-times-circle mr-2"></i>Lista de Postulantes No Admitidos</h3>
                    <div class="card-tools">
                        <small class="badge badge-danger">Total: {{ $noAdmitidos->count() }}</small>
                    </div>
                </div>
                <div class="card-body">
                    @if($noAdmitidos->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Información:</strong> No hay postulantes no admitidos.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover table-sm">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 5%; text-align: center;">Nro</th>
                                        <th style="width: 20%;">Código Postulante</th>
                                        <th style="width: 50%;">Nombre Completo</th>
                                        <th style="width: 25%; text-align: center;">Estado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($noAdmitidos as $resultado)
                                        <tr>
                                            <td style="text-align: center;">{{ $loop->iteration }}</td>
                                            <td>
                                                <code>{{ $resultado->inscripcion->postulante->codigo }}</code>
                                            </td>
                                            <td>
                                                <strong>{{ $resultado->inscripcion->postulante->nombre }} {{ $resultado->inscripcion->postulante->apellidos }}</strong>
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge badge-danger">{{ $resultado->estado }}</span>
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
