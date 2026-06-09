@extends('adminlte::page')

@section('content_header')
    <h1><b>Exámenes / Registro de un nuevo examen</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Rellene los datos del Formulario</h3>
                </div>

                <div class="card-body">

                    {{-- Mostrar todos los errores en un bloque de alerta --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ url('admin/examenes') }}" method="post">
                        @csrf

                        {{-- Nro Examen --}}
                        <div class="form-group">
                            <label for="nro_examen">Número de examen: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-list-ol"></i></span>
                                </div>
                                <input type="text" class="form-control" name="nro_examen"
                                       value="{{ old('nro_examen') }}" placeholder="Ingrese el número o código del examen" required>
                            </div>
                            @error('nro_examen')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Fecha --}}
                        <div class="form-group">
                            <label for="fecha">Fecha del examen: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                                <input type="date" class="form-control" name="fecha"
                                       value="{{ old('fecha') }}" required>
                            </div>
                            @error('fecha')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Ponderación --}}
                        <div class="form-group">
                            <label for="ponderacion">Ponderación (entre 0 y 1): </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-percentage"></i></span>
                                </div>
                                <input type="number" step="0.01" min="0" max="1" class="form-control" name="ponderacion"
                                       value="{{ old('ponderacion') }}" placeholder="Ej: 0.25, 0.50, 1.00" required>
                            </div>
                            @error('ponderacion')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Gestión Activa --}}
                        <div class="form-group">
                            <label for="gestion_id">Gestión: </label>
                            @if($gestionActiva)
                                <input type="text" class="form-control" 
                                       value="{{ $gestionActiva->semestre }} - {{ $gestionActiva->año }}" readonly>
                                <input type="hidden" name="gestion_id" value="{{ $gestionActiva->id }}">
                            @else
                                <div class="alert alert-danger">
                                    <i class="fas fa-exclamation-triangle"></i>
                                    No hay gestiones activas. No se puede registrar un examen hasta que exista una gestión activa.
                                </div>
                            @endif
                        </div>

                        {{-- Botones de acción --}}
                        <div class="form-group">
                            <a href="{{ url('/admin/examenes') }}" class="btn btn-secondary">CANCELAR</a>
                            @if($gestionActiva)
                                <button type="submit" class="btn btn-primary">REGISTRAR</button>
                            @else
                                <button type="button" class="btn btn-primary" disabled>REGISTRAR</button>
                            @endif
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
