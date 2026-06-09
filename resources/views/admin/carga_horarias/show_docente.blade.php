@extends('adminlte::page')

@section('content_header')
    <h1><b>Carga Horaria: {{ $docente->nombre }} {{ $docente->apellido }}</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    {{-- Información del Docente --}}
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header bg-primary">
                <h3 class="card-title mb-0">
                    <b>Información del Docente</b>
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-3">
                        <p><strong>Código:</strong> {{ $docente->codigo }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Nombre:</strong> {{ $docente->nombre }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>Apellido:</strong> {{ $docente->apellido }}</p>
                    </div>
                    <div class="col-md-3">
                        <p><strong>CI:</strong> {{ $docente->ci ?? '---' }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-3">
    {{-- Tabla de Carga Horaria --}}
    <div class="col-md-12">
        <div class="card card-outline card-info">
            <div class="card-header bg-info">
                <h3 class="card-title mb-0"><b>Materias Asignadas</b></h3>
                <div class="card-tools pull-right">
                    <button onclick="window.print()" class="btn btn-sm btn-default">
                        <i class="fas fa-print"></i> Imprimir
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($cargaHoraria->count() > 0)
                    <div class="table-responsive">
                        <table class="table table-bordered table-striped table-sm">
                            <thead class="bg-light">
                                <tr>
                                    <th style="text-align: center">Nro</th>
                                    <th style="text-align: center">Materia</th>
                                    <th style="text-align: center">Grupo</th>
                                    <th style="text-align: center">Modalidad</th>
                                    <th style="text-align: center">Hora Inicio</th>
                                    <th style="text-align: center">Hora Fin</th>
                                    <th style="text-align: center">Aula</th>
                                    <th style="text-align: center">Gestión</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php $contador = 1; @endphp
                                @foreach($cargaHoraria as $carga)
                                    @php
                                        $inicio = $carga->horario ? \Carbon\Carbon::createFromFormat('H:i:s', $carga->horario->hora_inicio)->format('H:i') : '---';
                                        $fin = $carga->horario ? \Carbon\Carbon::createFromFormat('H:i:s', $carga->horario->hora_fin)->format('H:i') : '---';
                                        $duracion = $carga->horario ? \Carbon\Carbon::createFromFormat('H:i:s', $carga->horario->hora_inicio)->diffInMinutes(\Carbon\Carbon::createFromFormat('H:i:s', $carga->horario->hora_fin)) : 0;
                                        $isReceso = $duracion < 20;
                                    @endphp
                                    <tr @if($isReceso) class="table-warning" @endif>
                                        <td style="text-align: center">{{ $contador++ }}</td>
                                        <td style="text-align: center">{{ $carga->materia->nombre ?? '---' }}</td>
                                        <td style="text-align: center">{{ $carga->grupo->nombre ?? '---' }}</td>
                                        <td style="text-align: center">{{ $carga->grupo->modalidad->nombre ?? '---' }}</td>
                                        <td style="text-align: center">{{ $inicio }}</td>
                                        <td style="text-align: center">{{ $fin }}</td>
                                        <td style="text-align: center">
                                            @if(strtolower($carga->grupo->modalidad->nombre ?? '') === 'virtual')
                                                Virtual
                                            @else
                                                {{ $carga->aula->nro_aula ?? '---' }}
                                            @endif
                                        </td>
                                        <td style="text-align: center">{{ $carga->gestion->semestre ?? '' }} - {{ $carga->gestion->año ?? '' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Resumen de Horas --}}
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="info-box bg-light-primary border-left-primary">
                                <span class="info-box-icon bg-primary"><i class="fas fa-clock"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Horas Trabajadas</span>
                                    <span class="info-box-number" style="font-size: 24px; color: #007bff;">{{ number_format($horasTrabajadas, 2) }} h</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box bg-light-warning border-left-warning">
                                <span class="info-box-icon bg-warning"><i class="fas fa-tasks"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Horas Requeridas</span>
                                    <span class="info-box-number" style="font-size: 24px; color: #ffc107;">{{ number_format($horasRequeridas, 2) }} h</span>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="info-box bg-light-success border-left-success">
                                <span class="info-box-icon bg-success"><i class="fas fa-check-circle"></i></span>
                                <div class="info-box-content">
                                    <span class="info-box-text">Diferencia</span>
                                    @php $diferencia = $horasTrabajadas - $horasRequeridas; @endphp
                                    <span class="info-box-number" style="font-size: 24px; color: @if($diferencia >= 0) #28a745 @else #dc3545 @endif;">{{ number_format($diferencia, 2) }} h</span>
                                </div>
                            </div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-warning">
                        <i class="fas fa-info-circle mr-2"></i>
                        Este docente no tiene materias asignadas.
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

{{-- Botones de Acción --}}
<div class="row mt-3">
    <div class="col-md-12">
        <a href="{{ route('admin.carga_horaria.index') }}" class="btn btn-secondary">VOLVER</a>
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
            text-align: left;
        }
        
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }

        .info-box {
            display: block;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 4px;
            background-color: #f7f7f7;
        }

        .bg-light-primary {
            background-color: #e7f3ff;
        }

        .bg-light-warning {
            background-color: #fff9e6;
        }

        .bg-light-success {
            background-color: #e8f5e9;
        }

        .border-left-primary {
            border-left: 4px solid #007bff;
        }

        .border-left-warning {
            border-left: 4px solid #ffc107;
        }

        .border-left-success {
            border-left: 4px solid #28a745;
        }

        .info-box-icon {
            display: inline-block;
            width: 40px;
            height: 40px;
            line-height: 40px;
            text-align: center;
            color: #fff;
            border-radius: 4px;
        }

        .info-box-content {
            display: inline-block;
            margin-left: 10px;
            vertical-align: middle;
        }

        .info-box-text {
            display: block;
            font-size: 14px;
            color: #666;
        }

        .info-box-number {
            display: block;
            font-size: 20px;
            font-weight: bold;
        }
    </style>
@stop
