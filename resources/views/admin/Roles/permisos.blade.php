@extends('adminlte::page')

@section('plugins.Datatables', true)

@section('content_header')
    <h1><b>Permisos del Rol {{ $rol->name }}</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header bg-white">
                <h3 class="card-title text-dark">Permisos Registrados</h3>
            </div>

            <form action="{{ route('admin.roles.permisos.actualizar', $rol->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="card-body">
                    <div class="row">
                        @foreach ($permisos as $modulo => $grupoPermisos)
                            <div class="col-md-3">
                                <h4 class="mt-3 text-primary">{{ $modulo }}</h4>
                                @foreach ($grupoPermisos as $permiso)
                                    <div class="form-check">
                                        <input type="checkbox" 
                                               class="form-check-input" 
                                               name="permisos[]" 
                                               value="{{ $permiso->id }}" 
                                               {{ $rol->hasPermissionTo($permiso->name) ? 'checked' : '' }}>
                                        <label class="form-check-label">
                                            {{ $permiso->name }}
                                        </label>
                                    </div>
                                @endforeach
                                <br>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="card-footer bg-white d-flex justify-content-end">
                    <a href="{{ route('admin.roles.index') }}" class="btn btn-secondary mr-2">Volver</a>
                    <button type="submit" class="btn btn-primary">Registrar Permisos</button>
                </div>
            </form>
        </div>
    </div>
</div>
@stop

@section('css')
@stop

@section('js')
@stop
