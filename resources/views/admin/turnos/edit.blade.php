@extends('adminlte::page')

@section('content_header')
    <h1><b>Turnos / Editar Turno</b></h1>
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
                    
                    <form action="{{ url('/admin/turnos/'.$turno->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        {{-- Nombre del turno --}}
                        <div class="form-group">
                            <label for="nombre">Nombre del turno: </label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="{{ old('nombre', $turno->nombre) }}" placeholder="Ej: Mañana, Tarde, Noche" required>
                            @error('nombre')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Botones de acción --}}
                        <div class="form-group">
                            <a href="{{ url('admin/turnos') }}" class="btn btn-secondary">CANCELAR</a>
                            <button type="submit" class="btn btn-success">GUARDAR CAMBIOS</button>
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
