@extends('adminlte::page')

@section('content_header')
    <h1><b>Materias / Editar Materia</b></h1>
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
                    
                    {{-- Formulario para editar una materia existente --}}
                    <form action="{{ url('/admin/materias/'.$materia->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        {{-- Nombre --}}
                        <div class="form-group">
                            <label for="nombre">Nombre de la materia: </label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="{{ old('nombre', $materia->nombre) }}" required>
                            @error('nombre')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Ponderación --}}
                        <div class="form-group">
                            <label for="ponderacion">Ponderación: </label>
                            <input type="number" class="form-control" id="ponderacion" name="ponderacion" 
                                   value="{{ old('ponderacion', $materia->ponderacion) }}" required>
                            @error('ponderacion')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Botones de acción --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Actualizar Materia</button>
                            <a href="{{ url('admin/materias') }}" class="btn btn-secondary">Cancelar</a>
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
