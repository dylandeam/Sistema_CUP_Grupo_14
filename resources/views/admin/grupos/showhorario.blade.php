@extends('adminlte::page')

@section('content_header')
    <h1><b>Horarios del Grupo: {{ $grupo->nombre }}</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    {{-- Información del Grupo --}}
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title mb-0">
                    <b>Información del Grupo</b>
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <p><strong>Nombre del Grupo:</strong> {{ $grupo->nombre }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Modalidad:</strong> {{ $grupo->modalidad->nombre }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Turno:</strong> {{ $grupo->turno->nombre }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Gestión:</strong> {{ $grupo->gestion->semestre }} - {{ $grupo->gestion->año }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    {{-- Tabla de Horarios --}}
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header bg-info">
                <h3 class="card-title mb-0"><b>Horario Semanal</b></h3>
                <div class="card-tools pull-right">
                    <button onclick="window.print()" class="btn btn-sm btn-default">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if(count($horarioSemanal) > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th style="text-align: center; font-weight: bold;">Hora</th>
                                    @foreach($dias as $dia)
                                        <th style="text-align: center; font-weight: bold;">{{ $dia }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($horarioSemanal as $hora => $contenido)
                                    @php
                                        $inicio = \Carbon\Carbon::createFromFormat('H:i:s', $hora)->format('H:i');
                                        $horario = $horarios->where('hora_inicio', $hora)->first();
                                        $fin = \Carbon\Carbon::createFromFormat('H:i:s', $horario->hora_fin)->format('H:i');
                                        $isReceso = $contenido['esReceso'] ?? false;
                                    @endphp
                                    <tr @if($isReceso) class="table-warning" @endif>
                                        <td style="text-align: center; font-weight: bold;">{{ $inicio }} - {{ $fin }}</td>
                                        @if($isReceso)
                                            <td colspan="5" style="text-align: center; color: #ff9800;">
                                                <i class="fas fa-coffee"></i> Receso ({{ $contenido['duracion'] ?? '' }} min)
                                            </td>
                                        @else
                                            @foreach($dias as $dia)
                                                <td style="text-align: center; padding: 10px;">
                                                    @php
                                                        $item = $contenido[$dia] ?? null;
                                                    @endphp
                                                    @if($item && $item['materia'])
                                                        <div style="background-color: #e3f2fd; padding: 8px; border-radius: 4px;">
                                                            <strong style="color: #1976d2;">{{ $item['materia']->nombre ?? '---' }}</strong>
                                                            @if($item['docente'])
                                                                <br>
                                                                <small style="color: #555;">{{ $item['docente']->nombre ?? '' }} {{ $item['docente']->apellido ?? '' }}</small>
                                                            @endif
                                                            @if($item['aula'])
                                                                <br>
                                                                <small style="color: #888;">Aula: {{ $item['aula']->nro_aula ?? 'Virtual' }}</small>
                                                            @endif
                                                        </div>
                                                    @else
                                                        <span style="color: #999;">---</span>
                                                    @endif
                                                </td>
                                            @endforeach
                                        @endif
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle mr-2"></i>
                        No hay horarios asignados para este turno.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Botones de Acción --}}
<div class="row mt-3">
    <div class="col-md-12">
        <a href="{{ route('admin.grupos.horariosgrupos') }}" class="btn btn-secondary">VOLVER</a>
    </div>
</div>
@stop

@section('css')
    <style media="print">
        .card-tools, .btn-group, .btn {
            display: none !important;
        }
        
        body {
            padding: 0;
            margin: 0;
        }
        
        .card {
            border: 1px solid #ddd;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
    </style>
@stop
