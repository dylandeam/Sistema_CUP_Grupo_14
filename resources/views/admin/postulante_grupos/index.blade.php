@extends('adminlte::page')

@section('content_header')
    <h1><b>Postulantes en Grupos</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    {{-- Mensaje de error si no hay gestión activa --}}
    @if(!$gestionActiva)
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Atención:</strong> {{ $mensaje ?? 'No hay gestiones activas.' }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @else
        {{-- Listado de Grupos --}}
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header bg-primary">
                    <h3 class="card-title">Listado de Grupos</h3>
                </div>
                <div class="card-body">
                    @if($grupos->isEmpty())
                        <div class="alert alert-info">
                            <i class="fas fa-info-circle mr-2"></i>
                            <strong>Información:</strong> No hay grupos disponibles para esta gestión.
                        </div>
                    @else
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead class="bg-light">
                                    <tr>
                                        <th style="width: 15%; text-align: center;">Nro. Grupo</th>
                                        <th style="width: 20%;">Modalidad</th>
                                        <th style="width: 20%;">Turno</th>
                                        <th style="width: 15%; text-align: center;">Total Inscritos</th>
                                        <th style="width: 30%; text-align: center;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($grupos as $grupo)
                                        <tr>
                                            <td style="text-align: center;">
                                                <strong>{{ $grupo->nombre }}</strong>
                                            </td>
                                            <td>
                                                {{ $grupo->modalidad->nombre ?? 'N/A' }}
                                            </td>
                                            <td>
                                                {{ $grupo->turno->nombre ?? 'N/A' }}
                                            </td>
                                            <td style="text-align: center;">
                                                <span class="badge badge-info">{{ $grupo->total_inscritos }}</span>
                                            </td>
                                            <td style="text-align: center;">
                                                <a href="{{ route('admin.postulante_grupos.show', $grupo->id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Ver postulantes de este grupo">
                                                    <i class="fas fa-eye mr-1"></i>Ver
                                                </a>
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
    @endif
</div>
@stop
