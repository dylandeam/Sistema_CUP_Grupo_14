@extends('adminlte::page')

@section('content_header')
    <h1>
        <b>Materias / Detalle de la Materia</b>
    </h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <b>Materia {{ $materia->nombre }}</b>
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5><strong>Información de la Materia</strong></h5>
                        <p>
                            <strong>ID:</strong> {{ $materia->id }}<br>
                            <strong>Nombre:</strong> {{ $materia->nombre }}<br>
                            <strong>Ponderación:</strong> {{ $materia->ponderacion }}<br>
                        </p>
                    </div>
                </div>
                <hr>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.materias.index') }}" class="btn btn-secondary">
                    Volver
                </a>
                <a href="{{ route('admin.materias.edit', $materia) }}" class="btn btn-primary">
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