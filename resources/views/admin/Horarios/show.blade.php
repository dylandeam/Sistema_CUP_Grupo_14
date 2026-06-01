@extends('adminlte::page')

@section('content_header')
    <h1>
        <b>Horarios / Detalle del Horario</b>
    </h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <b>Horario: {{ $horario->hora_inicio }} - {{ $horario->hora_fin }}</b>
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-12">
                        <h5><strong>Información del Horario</strong></h5>
                        <p>
                            <strong>Hora de inicio:</strong> {{ $horario->hora_inicio }}<br>
                            <strong>Hora de fin:</strong> {{ $horario->hora_fin }}<br>
                        </p>
                    </div>
                </div>
                <hr>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.horarios.index') }}" class="btn btn-secondary">
                    Volver
                </a>
                <a href="{{ route('admin.horarios.edit', $horario) }}" class="btn btn-primary">
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
