@extends('adminlte::page')

@section('content_header')
    <h1><b>Postulantes / Detalle de Inscripción</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">
                    <b>{{ $inscripcion->postulante->nombre }} {{ $inscripcion->postulante->apellidos }} ({{ $inscripcion->postulante->codigo }})</b>
                </h3>
            </div>

            {{--Información personal --}}
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-3 text-center">
                        @php $foto = $inscripcion->postulante->foto ?? null; @endphp
                        @if($foto && Storage::disk('public')->exists($foto))
                            <img src="{{ asset('storage/' . $foto) }}" class="img-thumbnail" style="max-width: 200px;">
                        @else
                            <img src="{{ asset('images/default-user.png') }}" class="img-thumbnail" style="max-width: 200px;" alt="Sin foto">
                            <p><small class="text-muted">No se ha cargado una foto.</small></p>
                        @endif
                    </div>

                    <div class="col-md-9">
                        <div class="row">
                            <div class="col-md-6">
                                <h5><strong>Información Personal</strong></h5>
                                <p>
                                    <strong>Código:</strong> {{ $inscripcion->postulante->codigo }}<br>
                                    <strong>Nombres:</strong> {{ $inscripcion->postulante->nombre }}<br>
                                    <strong>Apellidos:</strong> {{ $inscripcion->postulante->apellidos }}<br>
                                    <strong>CI:</strong> {{ $inscripcion->postulante->ci }}<br>
                                    <strong>Email:</strong> {{ $inscripcion->postulante->user->email ?? '—' }}<br>
                                    <strong>Fecha de Nacimiento:</strong> {{ $inscripcion->postulante->fecha_nacimiento ? \Carbon\Carbon::parse($inscripcion->postulante->fecha_nacimiento)->format('d/m/Y') : '—' }}<br>
                                    <strong>Sexo:</strong>
                                    @php
                                        $sexo = $inscripcion->postulante->sexo ?? '';
                                    @endphp
                                    {{ $sexo === 'M' ? 'Masculino' : ($sexo === 'F' ? 'Femenino' : ($sexo === 'O' ? 'Otro' : '—')) }}
                                </p>
                            </div>
                            <div class="col-md-6">
                                <h5><strong>Contacto y Procedencia</strong></h5>
                                <p>
                                    <strong>Teléfono:</strong> {{ $inscripcion->postulante->telefono ?: '—' }}<br>
                                    <strong>Dirección:</strong> {{ $inscripcion->postulante->direccion ?: '—' }}<br>
                                    <strong>Ciudad:</strong> {{ $inscripcion->postulante->ciudad ?: '—' }}<br>
                                    <strong>Colegio:</strong> {{ $inscripcion->postulante->colegio ?: '—' }}<br>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <hr>

                {{-- 1. Información de la inscripción --}}
                <div class="row">
                    <div class="col-md-4">
                        <div class="card card-outline card-warning">
                            <div class="card-header bg-warning">
                                <h5 class="card-title mb-0"><strong>1. Inscripción</strong></h5>
                            </div>
                            <div class="card-body">
                                <p>
                                    <strong>Gestión:</strong>
                                    {{ $inscripcion->gestion->semestre ?? '—' }} - {{ $inscripcion->gestion->año ?? '' }}<br>

                                    <strong>Fecha de inscripción:</strong>
                                    {{ $inscripcion->fecha_insc ? \Carbon\Carbon::parse($inscripcion->fecha_insc)->format('d/m/Y') : '—' }}<br>

                                    <strong>Modalidad:</strong>
                                    {{ $inscripcion->modalidad->nombre ?? '—' }}<br>

                                    <strong>Turno:</strong>
                                    {{ $inscripcion->turno->nombre ?? '—' }}<br><strong>Costo:</strong>
                                    
                                    USD {{ number_format($inscripcion->costo ?? 0, 2) }}<br>

                                    <strong>Estado:</strong>
                                    @if($inscripcion->estado === 'INSCRITO')
                                        <span class="badge badge-success">Inscrito</span>
                                    @else
                                        <span class="badge badge-warning text-dark">Pendiente</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- 2. Opciones de Carrera --}}
                    <div class="col-md-4">
                        <div class="card card-outline card-info">
                            <div class="card-header bg-info">
                                <h5 class="card-title mb-0"><strong>2. Opciones de Carrera</strong></h5>
                            </div>
                            <div class="card-body">
                                <p>
                                    <strong>1ra opción:</strong>
                                    {{ $primeraCarrera->carrera->nombre ?? '—' }}<br>

                                    <strong>2da opción:</strong>
                                    {{ $segundaCarrera->carrera->nombre ?? '—' }}<br>
                                </p>
                            </div>
                        </div>
                    </div>

                    {{-- 3. Requisitos --}}
                    <div class="col-md-4">
                        <div class="card card-outline card-success">
                            <div class="card-header bg-success">
                                <h5 class="card-title mb-0"><strong>3. Requisitos</strong></h5>
                            </div>
                            <div class="card-body">
                                <ul class="list-unstyled mb-0">
                                    <li>
                                        @if($requisitos?->fotocopia_ci)
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger mr-1"></i>
                                        @endif
                                        Fotocopia de CI
                                    </li>
                                    <li>
                                        @if($requisitos?->certificado_nacimiento)
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger mr-1"></i>
                                        @endif
                                        Certificado de nacimiento
                                    </li>
                                    <li>
                                        @if($requisitos?->titulo_bachiller)
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger mr-1"></i>
                                        @endif
                                        Título de bachiller
                                    </li>
                                    <li>
                                        @if($requisitos?->libreta_colegio)
                                            <i class="fas fa-check-circle text-success mr-1"></i>
                                        @else
                                            <i class="fas fa-times-circle text-danger mr-1"></i>
                                        @endif
                                        Libreta del colegio
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>

                </div>

                {{-- 4. Pago --}}
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline card-secondary">
                            <div class="card-header bg-secondary">
                                <h5 class="card-title mb-0"><strong>4. Pago</strong></h5>
                            </div>
                            <div class="card-body">
                                @if($pago)
                                    <div class="row">
                                        <div class="col-md-3">
                                            <strong>Monto:</strong><br>
                                            USD {{ number_format($pago->monto, 2) }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Fecha de pago:</strong><br>
                                            {{ $pago->fecha ? \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') : '—' }}
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Estado:</strong><br>
                                            @if($pago->estado === 'CONFIRMADO')
                                                <span class="badge badge-success">Confirmado</span>
                                            @else
                                                <span class="badge badge-warning text-dark">Pendiente</span>
                                            @endif
                                        </div>
                                        <div class="col-md-3">
                                            <strong>Comprobante:</strong><br>
                                            {{ $pago->comprobante ?: '—' }}
                                        </div>
                                    </div>
                                @else
                                    <p class="text-muted mb-0">No se ha registrado ningún pago para esta inscripción.</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>

            </div>

            <div class="card-footer">
                <a href="{{ route('admin.inscripciones.index') }}" class="btn btn-secondary">VOLVER</a>
                <a href="{{ route('admin.inscripciones.edit', $inscripcion->id) }}" class="btn btn-success">EDITAR</a>
            </div>
        </div>
    </div>
</div>
@stop