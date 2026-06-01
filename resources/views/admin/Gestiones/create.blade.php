@extends('adminlte::page')

@section('content_header')
    <h1><b>Gestiones / Registro de una nueva gestión</b></h1>
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
                    <form action="{{ url('admin/gestiones') }}" method="post">
                        @csrf

                        {{-- Semestre --}}
                        <div class="form-group">
                            <label for="semestre">Semestre: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-book"></i></span>
                                </div>
                                <input type="text" class="form-control" name="semestre"
                                       value="{{ old('semestre') }}" placeholder="Ingrese el semestre (Ej: 1 o 2)" required>
                            </div>
                            @error('semestre')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Año --}}
                        <div class="form-group">
                            <label for="año">Año: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                </div>
                                <input type="text" class="form-control" name="año"
                                       value="{{ old('año') }}" placeholder="Ingrese el año (Ej: 2024)" required>
                            </div>
                            @error('año')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Estado --}}
                        <div class="form-group">
                            <label for="estado">Estado: </label>
                            <div class="mb-3">
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="estado" id="estadoActiva"
                                           value="Activa" {{ old('estado', 'Activa') == 'Activa' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="estadoActiva">Activa</label>
                                </div>
                                <div class="form-check form-check-inline">
                                    <input class="form-check-input" type="radio" name="estado" id="estadoCerrada"
                                           value="Cerrada" {{ old('estado') == 'Cerrada' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="estadoCerrada">Cerrada</label>
                                </div>
                            </div>
                            @error('estado')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Botones de acción --}}
                        <div class="form-group">
                            <a href="{{ url('/admin/gestiones') }}" class="btn btn-secondary">Cancelar</a>
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
