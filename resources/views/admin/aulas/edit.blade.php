@extends('adminlte::page')

@section('content_header')
    <h1><b>Aulas / Editar Aula</b></h1>
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
                    
                    {{-- Formulario para editar un aula existente --}}
                    <form action="{{ url('/admin/aulas/'.$aula->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        {{-- Número de Aula --}}
                        <div class="form-group">
                            <label for="nro_aula">Número de Aula: </label>
                            <input type="number" class="form-control" id="nro_aula" name="nro_aula" 
                                   value="{{ old('nro_aula', $aula->nro_aula) }}" required>
                            @error('nro_aula')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Módulo --}}
                        <div class="form-group">
                            <label for="modulo">Módulo: </label>
                            <input type="text" class="form-control" id="modulo" name="modulo" 
                                   value="{{ old('modulo', $aula->modulo) }}" required>
                            @error('modulo')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Piso --}}
                        <div class="form-group">
                            <label for="piso">Piso: </label>
                            <input type="number" class="form-control" id="piso" name="piso" 
                                   value="{{ old('piso', $aula->piso) }}" min="0" required>
                            @error('piso')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Botones de acción --}}
                        <div class="form-group">
                            <a href="{{ url('admin/aulas') }}" class="btn btn-secondary">CANCELAR</a>
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
