@extends('adminlte::page')

@section('content_header')
    <h1><b>Materias / Registro de una nueva materia</b></h1>
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

                    <form action="{{ url('admin/materias') }}" method="post">
                        @csrf

                        {{-- Nombre --}}
                        <div class="form-group">
                            <label for="nombre">Nombre de la materia: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                </div>
                                <input type="text" class="form-control" name="nombre"
                                       value="{{ old('nombre') }}" placeholder="Ingrese el nombre de la materia" required>
                            </div>
                            @error('nombre')
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

                        {{-- Botones de acción --}}
                        <div class="form-group">
                            <a href="{{ url('/admin/materias') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Registrar</button>
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
