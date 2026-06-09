@extends('adminlte::page')

@section('content_header')
    <h1><b>Carreras / Editar Carrera</b></h1>
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
                    
                    <form action="{{ url('/admin/carreras/'.$carrera->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        {{-- Sigla --}}
                        <div class="form-group">
                            <label for="sigla">Sigla: </label>
                            <input type="text" class="form-control" id="sigla" name="sigla" 
                                   value="{{ old('sigla', $carrera->sigla) }}" required>
                            @error('sigla')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Nombre --}}
                        <div class="form-group">
                            <label for="nombre">Nombre de la carrera: </label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="{{ old('nombre', $carrera->nombre) }}" required>
                            @error('nombre')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Cupos disponibles --}}
                        <div class="form-group">
                            <label for="cupos_disponibles">Cupos disponibles: </label>
                            <input type="number" class="form-control" id="cupos_disponibles" name="cupos_disponibles" 
                                   value="{{ old('cupos_disponibles', $carrera->cupos_disponibles) }}" min="0" required>
                            @error('cupos_disponibles')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Botones de acción --}}
                        <div class="form-group">
                            <a href="{{ url('admin/carreras') }}" class="btn btn-secondary">CANCELAR</a>
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
