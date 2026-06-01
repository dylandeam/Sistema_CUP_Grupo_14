@extends('adminlte::page')

@section('content_header')
    <h1>
        <b>Docentes / Detalle del Docente</b>
    </h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <b>{{ $docente->nombre }} {{ $docente->apellido }} ({{ $docente->codigo }})</b>
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    {{-- Foto --}}
                    <div class="col-md-3 text-center">
                        @if($docente->foto && Storage::disk('public')->exists($docente->foto))
                            <img src="{{ asset('storage/' . $docente->foto) }}" 
                                 class="img-thumbnail" style="max-width: 200px;">
                        @else
                            <img src="{{ asset('images/default-user.png') }}" 
                                 class="img-thumbnail" style="max-width: 200px;" alt="Sin foto">
                            <p><small>No se ha cargado una foto.</small></p>
                        @endif
                    </div>

                    {{-- Datos del Docente --}}
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><strong>Información Personal</strong></h5>
                                <p>
                                    <strong>Nombres:</strong> {{ $docente->nombre }}<br>
                                    <strong>Apellidos:</strong> {{ $docente->apellido }}<br>
                                    <strong>CI:</strong> {{ $docente->ci }}<br>
                                    <strong>Email:</strong> {{ optional($docente->user)->email }}<br>
                                    <strong>Fecha de Nacimiento:</strong> {{ $docente->fecha_nacimiento }}<br>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5><strong>Contacto</strong></h5>
                                <p>
                                    <strong>Teléfono:</strong> {{ $docente->telefono }}<br>
                                    <strong>Dirección:</strong> {{ $docente->direccion }}<br>
                                    <strong>Estado:</strong> 
                                    @if($docente->estado == 'activo')
                                        <span class="badge badge-success">Activo</span>
                                    @else
                                        <span class="badge badge-danger">Baja</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Materias contratadas --}}
                <div class="row">
                    <div class="col-md-12">
                        <h5><strong>Materias Contratadas</strong></h5>
                        <p>
                            @if($docente->materias->isNotEmpty())
                                {{ $docente->materias->pluck('nombre')->implode(', ') }}
                            @else
                                <span class="text-muted">No tiene materias asignadas</span>
                            @endif
                        </p>
                    </div>
                </div>

                <hr>

                {{-- Rol del Docente --}}
                <div class="row">
                    <div class="col-md-12">
                        <h5><strong>Información de Acceso</strong></h5>
                        <p>
                            <strong>Rol:</strong> 
                            {{ optional($docente->user)->roles->pluck('name')->implode(', ') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">
                    Volver
                </a>
                <a href="{{ route('admin.docentes.edit', $docente) }}" class="btn btn-primary">
                    Editar
                </a>
            </div>
        </div>
    </div>
</div>
@stop
