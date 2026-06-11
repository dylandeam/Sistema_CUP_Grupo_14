@extends('adminlte::page')

@section('content_header')
    <h1><b>Resultados Finales de Admisión</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    @if(!$gestionActiva)
        <div class="col-md-12">
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-triangle mr-2"></i>
                <strong>Atención:</strong> No hay gestiones activas.
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
        </div>
    @else
        {{-- Card de Admitidos --}}
        <div class="col-md-6">
            <div class="card card-success">
                <div class="card-header bg-success">
                    <h3 class="card-title"><i class="fas fa-check-circle mr-2"></i>Postulantes Admitidos</h3>
                </div>
                <div class="card-body text-center">
                    <h2 style="font-size: 48px; color: #28a745; font-weight: bold;">
                        {{ $admitidosCount }}
                    </h2>
                    <p class="text-muted">Postulantes admitidos al sistema</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.resultados.admitidos') }}" class="btn btn-success btn-block">
                        <i class="fas fa-list mr-2"></i>VER LISTA
                    </a>
                </div>
            </div>
        </div>

        {{-- Card de No Admitidos --}}
        <div class="col-md-6">
            <div class="card card-danger">
                <div class="card-header bg-danger">
                    <h3 class="card-title"><i class="fas fa-times-circle mr-2"></i>Postulantes No Admitidos</h3>
                </div>
                <div class="card-body text-center">
                    <h2 style="font-size: 48px; color: #dc3545; font-weight: bold;">
                        {{ $noAdmitidosCount }}
                    </h2>
                    <p class="text-muted">Postulantes no admitidos al sistema</p>
                </div>
                <div class="card-footer">
                    <a href="{{ route('admin.resultados.no_admitidos') }}" class="btn btn-danger btn-block">
                        <i class="fas fa-list mr-2"></i>VER LISTA
                    </a>
                </div>
            </div>
        </div>

        {{-- Información adicional --}}
        <div class="col-md-12 mt-3">
            <div class="alert alert-info">
                <i class="fas fa-info-circle mr-2"></i>
                <strong>Criterios de Admisión:</strong>
                <ul class="mt-2 mb-0">
                    <li>Todas las 4 materias deben tener nota ≥ 60</li>
                    <li>El promedio general debe ser ≥ 60</li>
                    <li>Se asignan carreras según preferencia y disponibilidad de cupos</li>
                </ul>
            </div>
        </div>


    @endif
</div>

@stop
