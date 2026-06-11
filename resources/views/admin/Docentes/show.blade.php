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
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- Requisitos Académicos --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-outline card-success">
                            <div class="card-header bg-success">
                                <h5 class="card-title mb-0"><strong>Requisitos Académicos</strong></h5>
                            </div>
                            <div class="card-body">
                                @php $requisito = $docente->requisitos->first(); @endphp
                                @if($requisito)
                                    <p>
                                        <strong>Título:</strong> {{ $requisito->nombre_titulo }}<br>
                                        <strong>Maestría:</strong> {{ $requisito->nombre_maestria }}<br>
                                        <strong>Diplomado:</strong> {{ $requisito->nombre_diplomado }}
                                    </p>
                                @else
                                    <p class="text-muted">No hay requisitos académicos registrados.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Materias Contratadas --}}
                    <div class="col-md-4">
                        <div class="card card-outline card-info">
                            <div class="card-header bg-info">
                                <h5 class="card-title mb-0"><strong>Materias Contratadas</strong></h5>
                            </div>
                            <div class="card-body">
                                @if($docente->materias->isNotEmpty())
                                    <p>
                                        <strong>Materia:</strong>
                                        {{ $docente->materias->pluck('nombre')->implode(', ') }}<br>
                                        <strong>Estado:</strong>
                                        @if($docente->estado == 'activo')
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Baja</span>
                                        @endif
                                    </p>
                                @else
                                    <p class="text-muted">No tiene materias asignadas</p>
                                    <p>
                                        <strong>Estado:</strong>
                                        @if($docente->estado == 'activo')
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Baja</span>
                                        @endif
                                    </p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Información de Acceso y Grupos Asignados --}}
                    <div class="col-md-4">
                        <div class="card card-outline {{ $gruposAsignados >= 4 ? 'card-danger' : 'card-secondary' }}">
                            <div class="card-header {{ $gruposAsignados >= 4 ? 'bg-danger' : 'bg-secondary' }}">
                                <h5 class="card-title mb-0"><strong>{{ $gruposAsignados >= 4 ? 'Límite de Grupos' : 'Información de Acceso' }}</strong></h5>
                            </div>
                            <div class="card-body">
                                <p>
                                    <strong>Rol:</strong>
                                    {{ optional($docente->user)->roles->pluck('name')->implode(', ') }}
                                </p>
                                <hr>
                                <p>
                                    <strong>Grupos Asignados:</strong> 
                                    <span class="badge {{ $gruposAsignados >= 4 ? 'badge-danger' : 'badge-info' }}">
                                        {{ $gruposAsignados }}/4
                                    </span>
                                </p>
                                @if($gruposAsignados >= 4)
                                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                        <i class="fas fa-exclamation-circle mr-2"></i>
                                        <strong>¡Advertencia!</strong> Este docente ha alcanzado el límite máximo de 4 grupos asignados.
                                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                                            <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card-footer">
                <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">VOLVER</a>
                <a href="{{ route('admin.docentes.edit', $docente) }}" class="btn btn-success">EDITAR</a>
            </div>
        </div>
    </div>
</div>
@stop
