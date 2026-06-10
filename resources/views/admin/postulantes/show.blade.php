@extends('adminlte::page')

@section('content_header')
    <h1><b>Postulantes / Detalle del Postulante</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <b>{{ $postulante->nombre }} {{ $postulante->apellidos }} ({{ $postulante->codigo }})</b>
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    
                    {{-- Foto --}}
                    <div class="col-md-3 text-center">
                        @php $foto = $postulante->foto; @endphp
                        @if($foto && Storage::disk('public')->exists($foto))
                            <img src="{{ asset('storage/' . $foto) }}"
                                 class="img-thumbnail" style="max-width: 200px;">
                        @else
                            <img src="{{ asset('images/default-user.png') }}"
                                 class="img-thumbnail" style="max-width: 200px;" alt="Sin foto">
                            <p><small>No se ha cargado una foto.</small></p>
                        @endif
                    </div>

                    {{-- Datos del Postulante --}}
                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><strong>Información Personal</strong></h5>
                                <p>
                                    <strong>Nombres:</strong> {{ $postulante->nombre }}<br>
                                    <strong>Apellidos:</strong> {{ $postulante->apellidos }}<br>
                                    <strong>CI:</strong> {{ $postulante->ci }}<br>
                                    <strong>Email:</strong> {{ $postulante->user->email ?? '—' }}<br>
                                    <strong>Fecha de Nacimiento:</strong> {{ $postulante->fecha_nacimiento ? \Carbon\Carbon::parse($postulante->fecha_nacimiento)->format('d/m/Y') : '—' }}<br>
                                    <strong>Sexo:</strong> {{ $postulante->sexo === 'M' ? 'Masculino' : ($postulante->sexo === 'F' ? 'Femenino' : 'Otro') }}<br>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5><strong>Contacto y Procedencia</strong></h5>
                                <p>
                                    <strong>Teléfono:</strong> {{ $postulante->telefono ?: '—' }}<br>
                                    <strong>Dirección:</strong> {{ $postulante->direccion ?: '—' }}<br>
                                    <strong>Ciudad:</strong> {{ $postulante->ciudad ?: '—' }}<br>
                                    <strong>Colegio:</strong> {{ $postulante->colegio ?: '—' }}<br>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="row">
                    {{-- Información de Acceso --}}
                    <div class="col-md-4">
                        <div class="card card-outline card-secondary">
                            <div class="card-header bg-secondary">
                                <h5 class="card-title mb-0"><strong>Información de Acceso</strong></h5>
                            </div>
                            <div class="card-body">
                                <p>
                                    <strong>Rol:</strong>
                                    {{ $postulante->user->roles->pluck('name')->first() ?? '—' }}
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- Inscripciones --}}
                    <div class="col-md-8">
                        <div class="card card-outline card-info">
                            <div class="card-header bg-info">
                                <h5 class="card-title mb-0"><strong>Inscripciones</strong></h5>
                            </div>
                            <div class="card-body">
                                @if($inscripciones->isEmpty())
                                    <p class="text-muted mb-0">No hay inscripciones registradas para este postulante.</p>
                                @else
                                    <div class="table-responsive">
                                        <table class="table table-bordered table-sm mb-0">
                                            <thead>
                                                <tr>
                                                    <th>#</th>
                                                    <th>Gestión</th>
                                                    <th>Fecha</th>
                                                    <th>Estado</th>
                                                    <th>Acción</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($inscripciones as $index => $inscripcion)
                                                    <tr>
                                                        <td>{{ $index + 1 }}</td>
                                                        <td>{{ $inscripcion->gestion->semestre ?? '—' }} - {{ $inscripcion->gestion->año ?? '' }}</td>
                                                        <td>{{ $inscripcion->fecha_insc ? \Carbon\Carbon::parse($inscripcion->fecha_insc)->format('d/m/Y') : '—' }}</td>
                                                        <td>
                                                            @if($inscripcion->estado === 'INSCRITO')
                                                                <span class="badge badge-success">Inscrito</span>
                                                            @else
                                                                <span class="badge badge-warning text-dark">Pendiente</span>
                                                            @endif
                                                        </td>
                                                        <td>
                                                            <a href="{{ route('admin.inscripciones.show', $inscripcion->id) }}" class="btn btn-sm btn-info">
                                                                <i class="fas fa-eye mr-1"></i> Ver
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-footer">
                <a href="{{ route('admin.postulantes.index') }}" class="btn btn-secondary">VOLVER</a>
                <a href="{{ route('admin.postulantes.edit', $postulante->codigo) }}" class="btn btn-success">EDITAR</a>
            </div>
        </div>
    </div>
</div>
@stop