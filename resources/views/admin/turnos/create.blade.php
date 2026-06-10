@extends('adminlte::page')

@section('content_header')
    <h1><b>Turnos / Registro de un nuevo turno</b></h1>
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
                    <form action="{{ url('admin/turnos') }}" method="post">
                        @csrf

                        {{-- Nombre del turno --}}
                        <div class="form-group">
                            <label for="nombre">Nombre del turno: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                </div>
                                <input type="text" class="form-control" name="nombre"
                                       value="{{ old('nombre') }}" placeholder="Ej: Mañana, Tarde, Noche" required>
                            </div>
                            @error('nombre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Botones de acción --}}
                        <div class="form-group">
                            <a href="{{ url('/admin/turnos') }}" class="btn btn-secondary">CANCELAR</a>
                            <button type="submit" class="btn btn-primary">REGISTRAR</button>
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
