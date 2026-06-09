@extends('adminlte::page')

@section('content_header')
    <h1>
        <b>Carreras / Detalle de la Carrera</b>
    </h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <b>{{ $carrera->nombre }} ({{ $carrera->sigla }})</b>
                </h3>
            </div>

            <div class="card-body">
                <h5><strong>Información de la Carrera</strong></h5>
                <p>
                    <strong>Sigla:</strong> {{ $carrera->sigla }}<br>
                    <strong>Nombre:</strong> {{ $carrera->nombre }}<br>
                    <strong>Cupos disponibles:</strong> {{ $carrera->cupos_disponibles }}<br>
                </p>
                <hr>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.carreras.index') }}" class="btn btn-secondary">VOLVER</a>
                <a href="{{ route('admin.carreras.edit', $carrera) }}" class="btn btn-success">EDITAR</a>
            </div>
        </div>
    </div>
</div>
@stop
