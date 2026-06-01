@extends('adminlte::page')

@section('content_header')
    <h1><b>Gestiones / Editar Gestión</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Modifique los Datos del Formulario</h3>
                </div>
                <div class="card-body">
                    
                    {{-- Formulario para editar un rol existente --}}
                    <form action="{{ url('/admin/gestiones/'.$gestion->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        {{-- Semestre --}}
                        <div class="form-group">
                            <label for="semestre">Semestre: </label>
                            <input type="text" class="form-control" id="semestre" name="semestre" value="{{ old('semestre', $gestion->semestre) }}" required>
                            @error('semestre')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Año --}}
                        <div class="form-group">
                            <label for="año">Año: </label>
                            <input type="text" class="form-control" id="año" name="año" value="{{ old('año', $gestion->año) }}" required>
                            @error('año')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Estado --}}
                        <div class="form-group">
                            <label>Estado: </label>
                            <div class="mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="estado" id="estadoActiva" value="Activa" {{ old('estado', $gestion->estado) == 'Activa' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="estadoActiva">Activa</label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="estado" id="estadoCerrada"
                                           value="Cerrada" {{ old('estado', $gestion->estado) == 'Cerrada' ? 'checked' : '' }}>
                                    <label class="form-check-label" for="estadoCerrada">Cerrada</label>
                                </div>
                            </div>
                            @error('estado')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Botones de Accion (Actualizar y Cancelar) --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Actualizar Gestión</button>
                            <a href="{{ url('admin/gestiones') }}" class="btn btn-secondary">Cancelar</a>
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
