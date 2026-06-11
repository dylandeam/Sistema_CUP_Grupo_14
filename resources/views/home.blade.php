@extends('adminlte::page')

@section('content_header')
    <h1>Bienvenid@ <b>{{ Auth::user()->name }}</b></h1>
@stop

@section('content')
    @if ($isAdmin)
        <!-- Dashboard para Administrativos y Administrador -->
        <div class="row">
            <!-- Total Inscritos -->
            <div class="col-lg-3 col-md-6">
                <div class="small-box bg-info">
                    <div class="inner">
                        <h3>{{ $statistics['total_inscritos'] }}</h3>
                        <p>Total Inscritos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-users"></i>
                    </div>
                    <a href="{{ route('admin.inscripciones.index') }}" class="small-box-footer">
                        Más información <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Total Aprobados -->
            <div class="col-lg-3 col-md-6">
                <div class="small-box bg-success">
                    <div class="inner">
                        <h3>{{ $statistics['total_aprobados'] }}</h3>
                        <p>Total Aprobados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-check-circle"></i>
                    </div>
                    <a href="{{ route('admin.resultados.admitidos') }}" class="small-box-footer">
                        Más información <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Total Reprobados -->
            <div class="col-lg-3 col-md-6">
                <div class="small-box bg-danger">
                    <div class="inner">
                        <h3>{{ $statistics['total_reprobados'] }}</h3>
                        <p>Total Reprobados</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-times-circle"></i>
                    </div>
                    <a href="{{ route('admin.resultados.no_admitidos') }}" class="small-box-footer">
                        Más información <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <!-- Total Grupos Habilitados -->
            <div class="col-lg-3 col-md-6">
                <div class="small-box bg-warning">
                    <div class="inner">
                        <h3>{{ $statistics['total_grupos'] }}</h3>
                        <p>Total Grupos</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-layer-group"></i>
                    </div>
                    <a href="{{ route('admin.grupos.index') }}" class="small-box-footer">
                        Más información <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>
        </div>
    @else
        <!-- Mensaje simple para otros usuarios -->
        <div class="row">
            <div class="col-md-12">
                <div class="alert alert-info">
                    <i class="fas fa-info-circle mr-2"></i>
                    <b>¡Bienvenido!</b> Usuario: <b>{{ Auth::user()->name }}</b>
                </div>
            </div>
        </div>
    @endif
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')
    <script> console.log("Hi, I'm using the Laravel-AdminLTE package!"); </script>
@stop