@extends('adminlte::page')

@section('content_header')
    <h1><b>Aulas / Registro de una nueva aula</b></h1>
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
                    <form action="{{ url('admin/aulas') }}" method="post">
                        @csrf

                        {{-- Número de Aula --}}
                        <div class="form-group">
                            <label for="nro_aula">Número de Aula: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-door-closed"></i></span>
                                </div>
                                <input type="number" class="form-control" name="nro_aula"
                                       value="{{ old('nro_aula') }}" placeholder="Ej: 101" required>
                            </div>
                            @error('nro_aula')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Módulo --}}
                        <div class="form-group">
                            <label for="modulo">Módulo: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-building"></i></span>
                                </div>
                                <input type="text" class="form-control" name="modulo"
                                       value="{{ old('modulo') }}" placeholder="Ej: Bloque A" required>
                            </div>
                            @error('modulo')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Piso --}}
                        <div class="form-group">
                            <label for="piso">Piso: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-layer-group"></i></span>
                                </div>
                                <input type="number" class="form-control" name="piso"
                                       value="{{ old('piso') }}" placeholder="Ej: 1" min="0" required>
                            </div>
                            @error('piso')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Botones de acción --}}
                        <div class="form-group">
                            <a href="{{ url('/admin/aulas') }}" class="btn btn-secondary">CANCELAR</a>
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
