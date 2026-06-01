@extends('adminlte::page')

@section('content_header')
    <h1><b>Listado de Roles</b></h1> 
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Rellene los datos del formulario</h3>
            </div>
            <div class="card-body">

                {{-- Formulario para crear un nuevo rol --}}
                <form action="{{ route('admin.roles.store') }}" method="POST">
                    @csrf 
                    <div class="row">
                        <div class="col-md-12">
                            {{-- Nombre del Rol --}}
                            <div class="form-group">
                                <label for="name">Nombre del Rol</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="name" 
                                           value="{{ old('name') }}" 
                                           placeholder="Escriba aquí..." required>
                                </div>
                                @error('name')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                </div>
                <hr>

                {{-- Botones de acción --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="form-group">
                            <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>  
    </div>   
@stop        

@section('css')
@stop

@section('js')
@stop
