@extends('adminlte::page')

@section('content_header')
    <h1>
        <b>Gestiones Académicas / Detalle de la Gestión</b>
    </h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <b>Gestión {{ $gestion->semestre }} - {{ $gestion->año }}</b>
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5><strong>Información de la Gestión</strong></h5>
                        <p>
                            <strong>Semestre:</strong> {{ $gestion->semestre }}<br>
                            <strong>Año:</strong> {{ $gestion->año }}<br>
                            <strong>Estado:</strong> {{ $gestion->estado }}<br>
                        </p>
                    </div>
                </div>
                <hr>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.gestiones.index') }}" class="btn btn-secondary">
                    Volver
                </a>
                <a href="{{ route('admin.gestiones.edit', $gestion) }}" class="btn btn-primary">
                    Editar
                </a>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
@stop

@section('js')
@stop