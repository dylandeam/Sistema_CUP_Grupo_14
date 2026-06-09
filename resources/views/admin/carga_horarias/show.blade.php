@extends('adminlte::page')

@section('content_header')
    <h1><b>Carga Horaria / Detalle</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-body">
                <dl class="row">
                    <dt class="col-sm-3">Docente</dt>
                    <dd class="col-sm-9">{{ $cargaHoraria->docente->nombre ?? '-' }} {{ $cargaHoraria->docente->apellido ?? '' }} ({{ $cargaHoraria->docente_codigo }})</dd>

                    <dt class="col-sm-3">Materia</dt>
                    <dd class="col-sm-9">{{ $cargaHoraria->materia->nombre ?? '-' }}</dd>

                    <dt class="col-sm-3">Grupo</dt>
                    <dd class="col-sm-9">{{ $cargaHoraria->grupo->nombre ?? '-' }}</dd>

                    <dt class="col-sm-3">Horario</dt>
                    <dd class="col-sm-9">{{ $cargaHoraria->horario->descripcion ?? ($cargaHoraria->horario->hora_inicio ?? '') . ' - ' . ($cargaHoraria->horario->hora_fin ?? '') }}</dd>

                    <dt class="col-sm-3">Aula</dt>
                    <dd class="col-sm-9">{{ $cargaHoraria->aula->nro_aula ?? 'Virtual / No asignada' }}</dd>

                    <dt class="col-sm-3">Gestión</dt>
                    <dd class="col-sm-9">{{ $cargaHoraria->gestion->semestre ?? '-' }} - {{ $cargaHoraria->gestion->año ?? '' }}</dd>
                </dl>

                <a href="{{ route('admin.carga_horaria.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>
@stop
