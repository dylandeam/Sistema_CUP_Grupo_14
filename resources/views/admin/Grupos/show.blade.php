@extends('adminlte::page')

@section('content_header')
    <h1><b>Grupos / Detalle del Grupo</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    {{-- Formulario principal del Grupo --}}
    <div class="col-md-6">
        <div class="card card-outline card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title mb-0">
                    <b>{{ $grupo->nombre }} (Gestión {{ $grupo->gestion->semestre }} - {{ $grupo->gestion->año }})</b>
                </h3>
            </div>
            <div class="card-body">
                <h5><strong>Información del Grupo</strong></h5>
                <p><strong>Turno:</strong> {{ $grupo->turno->nombre }}</p>
                <p><strong>Modalidad:</strong> {{ $grupo->modalidad->nombre }}</p>
                <p><strong>Aula:</strong> 
                    @if(strtolower($grupo->modalidad->nombre) === 'virtual')
                        -
                    @else
                        {{ $grupo->aula->nro_aula ?? '---' }}
                    @endif
                </p>
                <p><strong>Gestión:</strong> {{ $grupo->gestion->semestre }} - {{ $grupo->gestion->año }}</p>
            </div>
        </div>
    </div>

    {{-- Formulario de Cupos --}}
    <div class="col-md-6">
        <div class="card card-outline card-success">
            <div class="card-header bg-success">
                <h3 class="card-title mb-0"><b>Información de Cupos</b></h3>
            </div>
            <div class="card-body">
                <p><strong>Cupos Totales:</strong> {{ $grupo->cupos }}</p>
                <p><strong>Cupos Ocupados:</strong> {{ $ocupados }}</p>
                
                {{-- Cupos disponibles con color dinámico --}}
                @php
                    $disponibles = $grupo->cupos - $ocupados;
                @endphp

                <p>
                    <strong>Cupos Disponibles:</strong>
                    @if($disponibles <= 0)
                        <span style="color: red; font-weight: bold;">0</span>
                        <br>
                        <span class="badge badge-danger">Grupo lleno</span>
                    @elseif($disponibles <= 5)
                        <span style="color: red; font-weight: bold;">{{ $disponibles }}</span>
                    @else
                        <span style="color: green; font-weight: bold;">{{ $disponibles }}</span>
                    @endif
                </p>
            </div>
        </div>
    </div>
</div>

{{-- Botones de Acción --}}
<div class="row mt-3">
    <div class="col-md-12">
        <a href="{{ route('admin.grupos.index') }}" class="btn btn-secondary">VOLVER</a>
        <a href="{{ route('admin.grupos.edit', $grupo) }}" class="btn btn-success">EDITAR</a>
    </div>
</div>
@stop
