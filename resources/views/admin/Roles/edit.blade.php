@extends('adminlte::page')

@section('content_header')
    <h1><b>Roles/Editar rol</b></h1> {{-- Título principal de la página --}}
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Modifique los Datos del formulario</h3> {{-- Encabezado del formulario --}}
            </div>
            <div class="card-body">

                {{-- Formulario para editar un rol existente --}}
                <form action="{{ url('admin/roles/'.$role->id) }}" method="POST">
                    @csrf 
                    @method('PUT') 
                    <div class="row">
                        <div class="col-md-12">

                            {{-- Rol --}}
                            <div class="form-group">
                                <label for="name">Nombre del Rol</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-check"></i></span>
                                    </div>
                                    <input type="text" class="form-control" name="name" 
                                           value="{{ old('name', $role->name) }}" 
                                           placeholder="Escriba aquí..." required>
                                </div>
                                @error('name')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>
                    <hr>
                    {{-- Botones de acción (Actualizar y Cancelar) --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <a href="{{ url('/admin/roles') }}" class="btn btn-secondary">Cancelar</a>
                                <button type="submit" class="btn btn-primary">Actualizar</button>
                            </div>
                        </div>
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
