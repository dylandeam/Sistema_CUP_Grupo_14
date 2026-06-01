@extends('adminlte::page')

@section('content_header')
    <h1>
        <b>Aulas / Detalle del Aula</b>
    </h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <b>Aula N° {{ $aula->nro_aula }} - {{ $aula->modulo }}</b>
                </h3>
            </div>

            <div class="card-body">
                <h5><strong>Información del Aula</strong></h5>
                <p>
                    <strong>Número de Aula:</strong> {{ $aula->nro_aula }}<br>
                    <strong>Módulo:</strong> {{ $aula->modulo }}<br>
                    <strong>Piso:</strong> {{ $aula->piso }}<br>
                </p>
                <hr>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.aulas.index') }}" class="btn btn-secondary">
                    Volver
                </a>
                <a href="{{ route('admin.aulas.edit', $aula) }}" class="btn btn-primary">
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
