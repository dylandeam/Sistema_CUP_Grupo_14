@extends('adminlte::page')
 
@section('content_header')
    <h1><b>Postulantes / Editar inscripción</b></h1>
    <hr>
@stop
 
@section('content')
<div class="row">
    <div class="col-md-12">
 
        @if($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif
 
        <form action="{{ route('admin.inscripciones.update', $inscripcion->id) }}" method="post" enctype="multipart/form-data" id="form-postulante">
            @csrf
            @method('PUT')
 
            {{-- 1. INSCRIPCIÓN --}}
            <div class="card card-outline card-warning mb-4">
                <div class="card-header bg-warning">
                    <h3 class="card-title">1. Inscripción</h3>
                </div>
                <div class="card-body">
                    @if($gestions->isEmpty())
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            No hay gestiones activas. La inscripción no está disponible porque la gestión ya se cerró.
                        </div>
                    @else

                        <div class="row">
                            {{-- Gestión --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gestion_id">Gestión activa</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>
                                        <select class="form-control" id="gestion_id" name="gestion_id" required>
                                            <option value="">Selecciona una gestión</option>
                                            @foreach($gestions as $gestion)
                                                <option value="{{ $gestion->id }}" {{ old('gestion_id', $inscripcion->gestion_id) == $gestion->id ? 'selected' : '' }}>
                                                    {{ $gestion->semestre }} - {{ $gestion->año }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Estado de Inscripción --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado">Estado de inscripción</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-flag"></i></span></div>
                                        <select class="form-control" id="estado" name="estado" required>
                                            <option value="PENDIENTE" {{ old('estado', $inscripcion->estado) == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="INSCRITO"  {{ old('estado', $inscripcion->estado) == 'INSCRITO'  ? 'selected' : '' }}>Inscrito</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
 
                        <div class="row">
                            {{-- Fecha de Inscripción --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_insc">Fecha de inscripción</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-day"></i></span></div>
                                        <input type="date" class="form-control" id="fecha_insc" name="fecha_insc"
                                            value="{{ old('fecha_insc', $inscripcion->fecha_insc ? \Carbon\Carbon::parse($inscripcion->fecha_insc)->format('Y-m-d') : '') }}" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Costo de Inscripción --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo_inscripcion">Costo de inscripción</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-dollar-sign"></i></span></div>
                                        <input type="text" class="form-control" id="costo_inscripcion" name="costo_inscripcion"
                                            value="{{ old('costo_inscripcion', number_format($costo_inscripcion, 2, '.', ',')) }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            {{-- Modalidad --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="modalidad_id">Modalidad</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-layer-group"></i></span></div>
                                        <select class="form-control" id="modalidad_id" name="modalidad_id" required>
                                            <option value="">Selecciona una modalidad</option>
                                            @foreach($modalidades as $modalidad)
                                                <option value="{{ $modalidad->id }}" {{ old('modalidad_id', $inscripcion->modalidad_id) == $modalidad->id ? 'selected' : '' }}>
                                                    {{ $modalidad->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Turno --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="turno_id">Turno</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-clock"></i></span></div>
                                        <select class="form-control" id="turno_id" name="turno_id" required>
                                            <option value="">Selecciona un turno</option>
                                            @foreach($turnos as $turno)
                                                <option value="{{ $turno->id }}" {{ old('turno_id', $inscripcion->turno_id) == $turno->id ? 'selected' : '' }}>
                                                    {{ $turno->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
 
            {{-- 2. DATOS DEL POSTULANTE --}}
            <div class="card card-outline card-primary mb-4">
                <div class="card-header bg-primary">
                    <h3 class="card-title">2. Datos del postulante</h3>
                </div>
                <div class="card-body">
 
                    <div class="row">
                        {{-- Nombre --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                    <input type="text" class="form-control" id="nombre" name="nombre"
                                        value="{{ old('nombre', $inscripcion->postulante->nombre ?? '') }}" placeholder="Ej: Juan" required>
                                </div>
                            </div>
                        </div>

                        {{-- Apellidos --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellidos">Apellidos</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user-tag"></i></span></div>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos"
                                        value="{{ old('apellidos', $inscripcion->postulante->apellidos ?? '') }}" placeholder="Ej: Pérez Mamani" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- CI --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ci">CI</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-id-card"></i></span></div>
                                    <input type="text" class="form-control" id="ci" name="ci"
                                        value="{{ old('ci', $inscripcion->postulante->ci ?? '') }}" placeholder="Ej: 9876543" required>
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                    <input type="email" class="form-control" id="email" name="email"
                                        value="{{ old('email', $postulante->user->email ?? $inscripcion->postulante->user->email ?? '') }}" placeholder="Ej: correo@ejemplo.com" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Fecha de nacimiento --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar"></i></span></div>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                                        value="{{ old('fecha_nacimiento', $inscripcion->postulante->fecha_nacimiento ? \Carbon\Carbon::parse($inscripcion->postulante->fecha_nacimiento)->format('Y-m-d') : '') }}" required>
                                </div>
                            </div>
                        </div>
                        
                        {{-- Sexo --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sexo">Sexo</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-venus-mars"></i></span></div>
                                    <select class="form-control" id="sexo" name="sexo" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="M" {{ old('sexo', $inscripcion->postulante->sexo ?? '') == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('sexo', $inscripcion->postulante->sexo ?? '') == 'F' ? 'selected' : '' }}>Femenino</option>
                                        <option value="O" {{ old('sexo', $inscripcion->postulante->sexo ?? '') == 'O' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Teléfono --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                                    <input type="text" class="form-control" id="telefono" name="telefono"
                                        value="{{ old('telefono', $inscripcion->postulante->telefono ?? '') }}" placeholder="Ej: 70012345">
                                </div>
                            </div>
                        </div>

                        {{-- Dirección --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span></div>
                                    <input type="text" class="form-control" id="direccion" name="direccion"
                                        value="{{ old('direccion', $inscripcion->postulante->direccion ?? '') }}" placeholder="Ej: Av. Busch #123">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Ciudad --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ciudad">Ciudad de origen</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-city"></i></span></div>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad"
                                        value="{{ old('ciudad', $inscripcion->postulante->ciudad ?? '') }}" placeholder="Ej: Santa Cruz">
                                </div>
                            </div>
                        </div>

                        {{-- Colegio de Procedencia --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="colegio">Colegio de procedencia</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-school"></i></span></div>
                                    <input type="text" class="form-control" id="colegio" name="colegio"
                                        value="{{ old('colegio', $inscripcion->postulante->colegio ?? '') }}" placeholder="Ej: U.E. San Ignacio">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Foto --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto">Foto</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-image"></i></span></div>
                                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewFoto(event)">
                                </div>
                                <div class="mt-2">
                                    @php
                                        $fotoActual = $inscripcion->postulante->foto ?? null;
                                    @endphp
                                    <img id="preview_foto"
                                        src="{{ $fotoActual ? asset('storage/' . $fotoActual) : asset('images/default-user.png') }}"
                                        alt="Previsualización" width="120" class="img-thumbnail">
                                    @if($fotoActual)
                                        <small class="text-muted d-block mt-1">
                                            <i class="fas fa-info-circle mr-1"></i>
                                            Seleccione una nueva foto solo si desea reemplazar la actual.
                                        </small>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
 
                </div>
            </div>
 
            {{-- 3. REQUISITOS DEL POSTULANTE --}}
            <div class="card card-outline card-success mb-4">
                <div class="card-header bg-success">
                    <h3 class="card-title">3. Requisitos del postulante</h3>
                </div>
                <div class="card-body">
 
                    <p class="text-muted mb-3">
                        <i class="fas fa-info-circle mr-1"></i>
                        Marque los documentos que el postulante presenta al momento de la inscripción.
                    </p>
 
                    <div class="row">
                        {{-- Fotocopia de CI --}}
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input requisito-check" type="checkbox"
                                    id="fotocopia_ci" name="fotocopia_ci" value="1"{{ old('fotocopia_ci', $requisitos->fotocopia_ci ?? false) ? 'checked' : '' }}onchange="validarRequisitos()">
                                <label class="form-check-label" for="fotocopia_ci">
                                    <i class="fas fa-id-card text-primary mr-1"></i>Fotocopia de CI<small class="text-muted d-block">Cédula de identidad vigente</small>
                                </label>
                            </div>
                        </div>

                        {{-- Certificado de nacimiento --}}
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input requisito-check" type="checkbox"
                                    id="certificado_nacimiento" name="certificado_nacimiento" value="1"{{ old('certificado_nacimiento', $requisitos->certificado_nacimiento ?? false) ? 'checked' : '' }}onchange="validarRequisitos()">
                                <label class="form-check-label" for="certificado_nacimiento">
                                    <i class="fas fa-file-alt text-primary mr-1"></i>Certificado de nacimiento<small class="text-muted d-block">Original o copia legalizada</small>
                                </label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Título de bachiller --}}
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input requisito-check" type="checkbox"
                                    id="titulo_bachiller" name="titulo_bachiller" value="1"{{ old('titulo_bachiller', $requisitos->titulo_bachiller ?? false) ? 'checked' : '' }}onchange="validarRequisitos()">
                                <label class="form-check-label" for="titulo_bachiller">
                                    <i class="fas fa-graduation-cap text-primary mr-1"></i>Título de bachiller<small class="text-muted d-block">Original o fotocopia legalizada</small>
                                </label>
                            </div>
                        </div>

                        {{-- Libreta del colegio --}}
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input requisito-check" type="checkbox"
                                    id="libreta_colegio" name="libreta_colegio" value="1"{{ old('libreta_colegio', $requisitos->libreta_colegio ?? false) ? 'checked' : '' }}onchange="validarRequisitos()">
                                <label class="form-check-label" for="libreta_colegio">
                                    <i class="fas fa-book text-primary mr-1"></i>Libreta del colegio<small class="text-muted d-block">Último año cursado</small>
                                </label>
                            </div>
                        </div>
                    </div>
 
                    <small class="text-muted" id="req-count">0 de 4 requisitos marcados</small>
 
                    <div class="alert alert-danger mt-3" id="alert-danger" style="display:none;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        El postulante debe presentar <strong>todos los requisitos</strong> para completar la inscripción.
                    </div>
 
                    <div class="alert alert-success mt-3" id="alert-success" style="display:none;">
                        <i class="fas fa-check-circle mr-2"></i>
                        Todos los requisitos han sido verificados. El postulante puede continuar.
                    </div>
                </div>
            </div>
 
            {{-- 4. OPCIONES DE CARRERA --}}
            <div class="card card-outline card-info mb-4">
                <div class="card-header bg-info">
                    <h3 class="card-title">4. Opciones de carrera</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Carrera 1ra opción --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="carrera_primera_opcion_id">1ra opción</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-list"></i></span></div>
                                    <select class="form-control" id="carrera_primera_opcion_id" name="carrera_primera_opcion_id" required>
                                        <option value="">Selecciona la primera opción</option>
                                        @foreach($carreras as $carrera)
                                            <option value="{{ $carrera->id }}" {{ old('carrera_primera_opcion_id', $primeraCarrera->carrera_id ?? '') == $carrera->id ? 'selected' : '' }}>
                                                {{ $carrera->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Carrera 2da opción --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="carrera_segunda_opcion_id">2da opción</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-list-ul"></i></span></div>
                                    <select class="form-control" id="carrera_segunda_opcion_id" name="carrera_segunda_opcion_id" required>
                                        <option value="">Selecciona la segunda opción</option>
                                        @foreach($carreras as $carrera)
                                            <option value="{{ $carrera->id }}" {{ old('carrera_segunda_opcion_id', $segundaCarrera->carrera_id ?? '') == $carrera->id ? 'selected' : '' }}>
                                                {{ $carrera->nombre }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
 
            {{-- 5. PAGO (solo lectura) --}}
            <div class="card card-outline card-secondary mb-4">
                <div class="card-header bg-secondary d-flex justify-content-between align-items-center">
                    <h3 class="card-title mb-0">5. Pago</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        {{-- Monto pagado --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Monto</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-dollar-sign"></i></span></div>
                                    <input type="text" class="form-control" 
                                        value="{{ $pago ? number_format($pago->monto, 2, '.', ',') : '0.00' }}" 
                                        readonly disabled>
                                </div>
                            </div>
                        </div>

                        {{-- Fecha de pago --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Fecha de pago</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-day"></i></span></div>
                                    <input type="text" class="form-control"
                                        value="{{ $pago?->fecha ? \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') : '—' }}"
                                        readonly disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Estado del pago --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Estado</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-flag"></i></span></div>
                                    @php $estadoPago = $pago->estado ?? 'PENDIENTE'; @endphp
                                    <input type="text" class="form-control
                                        {{ $estadoPago === 'CONFIRMADO' ? 'text-success font-weight-bold' : 'text-warning font-weight-bold' }}"
                                        value="{{ $estadoPago }}"
                                        readonly disabled>
                                </div>
                            </div>
                        </div>

                        {{-- Comprobante de pago --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Comprobante</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-file-alt"></i></span></div>
                                    <input type="text" class="form-control"
                                        value="{{ $pago->comprobante ?? '—' }}"
                                        readonly disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
 
            {{-- BOTONES FINALES --}}
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('admin.inscripciones.index') }}" class="btn btn-secondary mr-2">CANCELAR</a>
                <button type="submit" id="btn-submit" class="btn btn-success" {{ $gestions->isEmpty() ? 'disabled' : '' }}>GUARDAR CAMBIOS</button>
            </div>
        </form>
    </div>
</div>
@stop
 
@section('js')
<script>

    {{-- Validación de requisitos --}}
    document.addEventListener('DOMContentLoaded', function () {
        validarRequisitos();
    });

    function validarRequisitos() {
        const checks   = document.querySelectorAll('.requisito-check');
        const marcados = document.querySelectorAll('.requisito-check:checked');
        const total    = checks.length;
        const n        = marcados.length;

        document.getElementById('req-count').textContent = n + ' de ' + total + ' requisitos marcados';

        const alertDanger  = document.getElementById('alert-danger');
        const alertSuccess = document.getElementById('alert-success');
        const btnSubmit    = document.getElementById('btn-submit');

        if (n === 0) {
            alertDanger.style.display  = 'none';
            alertSuccess.style.display = 'none';
        } else if (n < total) {
            alertDanger.style.display  = 'block';
            alertSuccess.style.display = 'none';
        } else {
            alertDanger.style.display  = 'none';
            alertSuccess.style.display = 'block';
        }

        // En edición el submit solo se bloquea si no hay gestiones activas;
        // los requisitos son informativos pero no bloquean guardar.
        // Ajusta esta lógica según tu regla de negocio.
        if ({{ $gestions->isEmpty() ? 'true' : 'false' }}) {
            btnSubmit.disabled = true;
        }
    }

    function previewFoto(event) {
        const [file] = event.target.files;
        const img    = document.getElementById('preview_foto');
        if (file) {
            img.src = URL.createObjectURL(file);
        } else {
            img.src = '{{ $inscripcion->postulante->foto ? asset('storage/' . $inscripcion->postulante->foto) : asset('images/default-user.png') }}';
        }
    }
</script>
@stop