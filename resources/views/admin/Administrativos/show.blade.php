@extends('adminlte::page')

@section('content_header')
    <h1>
        <b>Administrativos / Detalle del Administrativo</b>
    </h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <b>{{ $administrativo->nombre }} {{ $administrativo->apellido }} ({{ $administrativo->codigo }})</b>
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    {{-- Foto --}}
                    <div class="col-md-3 text-center">
                        @if($administrativo->foto && Storage::disk('public')->exists($administrativo->foto))
                            <img src="{{ asset('storage/' . $administrativo->foto) }}" 
                                 class="img-thumbnail" style="max-width: 200px;">
                        @else
                            <img src="{{ asset('images/default-user.png') }}" 
                                 class="img-thumbnail" style="max-width: 200px;" alt="Sin foto">
                            <p><small>No se ha cargado una foto.</small></p>
                        @endif
                    </div>

                    {{-- Datos del Administrativo --}}
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><strong>Información Personal</strong></h5>
                                <p>
                                    <strong>Nombres:</strong> {{ $administrativo->nombre }}<br>
                                    <strong>Apellidos:</strong> {{ $administrativo->apellido }}<br>
                                    <strong>CI:</strong> {{ $administrativo->ci }}<br>
                                    <strong>Email:</strong> {{ optional($administrativo->user)->email }}<br>
                                    <strong>Fecha de Nacimiento:</strong> {{ $administrativo->fecha_nacimiento }}<br>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5><strong>Contacto</strong></h5>
                                <p>
                                    <strong>Teléfono:</strong> {{ $administrativo->telefono }}<br>
                                    <strong>Dirección:</strong> {{ $administrativo->direccion }}<br>
                                    <strong>Cargo:</strong> {{ $administrativo->cargo }}<br>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Rol del Administrativo --}}
                <div class="row">
                    <div class="col-md-12">
                        <h5><strong>Información de Acceso</strong></h5>
                        <p>
                            <strong>Rol:</strong> 
                            {{ optional($administrativo->user)->roles->pluck('name')->implode(', ') }}
                        </p>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.administrativos.index') }}" class="btn btn-secondary">
                    Volver
                </a>
                <a href="{{ route('admin.administrativos.edit', $administrativo) }}" class="btn btn-primary">
                    Editar
                </a>
            </div>
        </div>
    </div>
</div>
@stop
