@extends('adminlte::page')

@section('content_header')
    <h1><b>Carreras / Registro de una nueva carrera</b></h1>
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
                    <form action="{{ url('admin/carreras') }}" method="post">
                        @csrf

                        {{-- Sigla --}}
                        <div class="form-group">
                            <label for="sigla">Sigla: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-tag"></i></span>
                                </div>
                                <input type="text" class="form-control" name="sigla"
                                       value="{{ old('sigla') }}" placeholder="Ej: ING-SIS" required>
                            </div>
                            @error('sigla')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Nombre --}}
                        <div class="form-group">
                            <label for="nombre">Nombre de la carrera: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                </div>
                                <input type="text" class="form-control" name="nombre"
                                       value="{{ old('nombre') }}" placeholder="Ej: Ingeniería de Sistemas" required>
                            </div>
                            @error('nombre')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Cupos disponibles --}}
                        <div class="form-group">
                            <label for="cupos_disponibles">Cupos disponibles: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-users"></i></span>
                                </div>
                                <input type="number" class="form-control" name="cupos_disponibles"
                                       value="{{ old('cupos_disponibles') }}" placeholder="Ej: 50" min="0" required>
                            </div>
                            @error('cupos_disponibles')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Botones de acción --}}
                        <div class="form-group">
                            <a href="{{ url('/admin/carreras') }}" class="btn btn-secondary">CANCELAR</a>
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
