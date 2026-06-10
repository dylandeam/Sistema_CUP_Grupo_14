@extends('adminlte::page')
 
@section('content_header')
    <h1><b>Postulantes / Registro de nueva inscripción</b></h1>
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
 
        <form action="{{ route('admin.inscripciones.store') }}" method="post" enctype="multipart/form-data" id="form-postulante">
            @csrf
            <input type="hidden" name="generar_pago" id="generar_pago" value="0">
 
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

                            {{-- Gestion --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="gestion_id">Gestión activa</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-alt"></i></span></div>
                                        <select class="form-control" id="gestion_id" name="gestion_id" required>
                                            <option value="">Selecciona una gestión</option>
                                            @foreach($gestions as $gestion)
                                                <option value="{{ $gestion->id }}" {{ old('gestion_id') == $gestion->id ? 'selected' : ($loop->first ? 'selected' : '') }}>
                                                    {{ $gestion->semestre }} - {{ $gestion->año }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            {{-- Estado de inscripción --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="estado">Estado de inscripción</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-flag"></i></span></div>
                                        <select class="form-control" id="estado" name="estado" required>
                                            <option value="PENDIENTE" {{ old('estado') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                                            <option value="INSCRITO" {{ old('estado') == 'INSCRITO' ? 'selected' : '' }}>Inscrito</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
 
                        {{-- Fecha de inscripción --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_insc">Fecha de inscripción</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-day"></i></span></div>
                                        <input type="date" class="form-control" id="fecha_insc" name="fecha_insc" value="{{ old('fecha_insc', now()->format('Y-m-d')) }}" required>
                                    </div>
                                </div>
                            </div>

                            {{-- Costo de inscripción --}}
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="costo_inscripcion">Costo de inscripción</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-dollar-sign"></i></span></div>
                                        <input type="text" class="form-control" id="costo_inscripcion" name="costo_inscripcion" value="{{ old('costo_inscripcion', number_format($costo_inscripcion, 2, '.', ',')) }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Modalidad de inscripción --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="modalidad_id">Modalidad</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-layer-group"></i></span></div>
                                        <select class="form-control" id="modalidad_id" name="modalidad_id" required>
                                            <option value="">Selecciona una modalidad</option>
                                            @foreach($modalidades as $modalidad)
                                                <option value="{{ $modalidad->id }}" {{ old('modalidad_id') == $modalidad->id ? 'selected' : ($loop->first ? 'selected' : '') }}>
                                                    {{ $modalidad->nombre }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                            </div>

                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="turno_id">Turno</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-clock"></i></span></div>
                                        <select class="form-control" id="turno_id" name="turno_id" required>
                                            <option value="">Selecciona un turno</option>
                                            @foreach($turnos as $turno)
                                                <option value="{{ $turno->id }}" {{ old('turno_id') == $turno->id ? 'selected' : '' }}>
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
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre') }}" placeholder="Ej: Juan" required>
                                </div>
                            </div>
                        </div>

                        {{-- Apellidos --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellidos">Apellidos</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user-tag"></i></span></div>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ old('apellidos') }}" placeholder="Ej: Pérez Mamani" required>
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
                                    <input type="text" class="form-control" id="ci" name="ci" value="{{ old('ci') }}" placeholder="Ej: 9876543" required>
                                </div>
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email') }}" placeholder="Ej: correo@ejemplo.com" required>
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
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento') }}" required>
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
                                        <option value="M" {{ old('sexo') == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('sexo') == 'F' ? 'selected' : '' }}>Femenino</option>
                                        <option value="O" {{ old('sexo') == 'O' ? 'selected' : '' }}>Otro</option>
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
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono') }}" placeholder="Ej: 70012345">
                                </div>
                            </div>
                        </div>

                        {{-- Dirección --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span></div>
                                    <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion') }}" placeholder="Ej: Av. Busch #123">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Ciudad de origen --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ciudad">Ciudad de origen</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-city"></i></span></div>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad" value="{{ old('ciudad') }}" placeholder="Ej: Santa Cruz">
                                </div>
                            </div>
                        </div>

                        {{-- Colegio de procedencia --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="colegio">Colegio de procedencia</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-school"></i></span></div>
                                    <input type="text" class="form-control" id="colegio" name="colegio" value="{{ old('colegio') }}" placeholder="Ej: U.E. San Ignacio">
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
                                    <img id="preview_foto" src="{{ asset('images/default-user.png') }}" alt="Previsualización" width="120" class="img-thumbnail" style="display:none;">
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
                        {{--Fotocopia de CI --}}
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input requisito-check" type="checkbox"
                                    id="fotocopia_ci" name="fotocopia_ci" value="1"{{ old('fotocopia_ci') ? 'checked' : '' }}onchange="validarRequisitos()">
                                <label class="form-check-label" for="fotocopia_ci">
                                    <i class="fas fa-id-card text-primary mr-1"></i>Fotocopia de CI<small class="text-muted d-block">Cédula de identidad vigente</small>
                                </label>
                            </div>
                        </div>

                        {{-- Certificado de nacimiento --}}
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input requisito-check" type="checkbox"
                                    id="certificado_nacimiento" name="certificado_nacimiento" value="1"{{ old('certificado_nacimiento') ? 'checked' : '' }}onchange="validarRequisitos()">
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
                                    id="titulo_bachiller" name="titulo_bachiller" value="1"{{ old('titulo_bachiller') ? 'checked' : '' }}onchange="validarRequisitos()">
                                <label class="form-check-label" for="titulo_bachiller">
                                    <i class="fas fa-graduation-cap text-primary mr-1"></i>Título de bachiller<small class="text-muted d-block">Original o fotocopia legalizada</small>
                                </label>
                            </div>
                        </div>

                        {{-- Libreta del colegio --}}
                        <div class="col-md-6">
                            <div class="form-check mb-3">
                                <input class="form-check-input requisito-check" type="checkbox"
                                    id="libreta_colegio" name="libreta_colegio" value="1"{{ old('libreta_colegio') ? 'checked' : '' }}onchange="validarRequisitos()">
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
                                            <option value="{{ $carrera->id }}" {{ old('carrera_primera_opcion_id') == $carrera->id ? 'selected' : '' }}>
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
                                            <option value="{{ $carrera->id }}" {{ old('carrera_segunda_opcion_id') == $carrera->id ? 'selected' : '' }}>
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
 
            {{-- 5. PAGO --}}
            <div class="card card-outline card-secondary mb-4">
                <div class="card-header bg-secondary">
                    <h3 class="card-title">5. Pago</h3>
                </div>
                <div class="card-body">

                    <div class="row">
                        {{-- Monto de pago --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="monto_pago">Monto</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-dollar-sign"></i></span></div>
                                    <input type="number" step="0.01" min="0" class="form-control" id="monto_pago" name="monto_pago" value="{{ old('monto_pago', '0.00') }}">
                                </div>
                                <small id="payment-status" class="form-text text-muted">Ingrese el monto que pagó el postulante.</small>
                            </div>
                        </div>

                        {{-- Fecha de pago --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_pago">Fecha de pago</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar-day"></i></span></div>
                                    <input type="date" class="form-control" id="fecha_pago" name="fecha_pago" value="{{ old('fecha_pago', now()->format('Y-m-d')) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        {{-- Estado de pago --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estado_pago">Estado</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-flag"></i></span></div>
                                    <select class="form-control" id="estado_pago" name="estado_pago">
                                        <option value="PENDIENTE" {{ old('estado_pago', 'PENDIENTE') == 'PENDIENTE' ? 'selected' : '' }}>Pendiente</option>
                                        <option value="CONFIRMADO" {{ old('estado_pago') == 'CONFIRMADO' ? 'selected' : '' }}>Confirmado</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Comprobante de pago --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="comprobante">Comprobante</label>
                                <div class="input-group">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-file-alt"></i></span></div>
                                    <input type="text" class="form-control" id="comprobante" name="comprobante" value="{{ old('comprobante') }}" placeholder="Número o referencia">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="d-flex justify-content-end mt-3">
                        <button type="button" id="btn-generate-payment" class="btn btn-warning" {{ $gestions->isEmpty() ? 'disabled' : '' }}>
                            <i class="fab fa-paypal mr-1"></i> Generar pago con PayPal
                        </button>
                    </div>
                    <div class="mt-3" id="paypal-email-alert" style="display:none;"></div>
                </div>
            </div>
 
            {{-- Botones de Acción --}}
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('admin.inscripciones.index') }}" class="btn btn-secondary mr-2">CANCELAR</a>
                <button type="submit" id="btn-submit" class="btn btn-primary" {{ $gestions->isEmpty() ? 'disabled' : '' }}>REGISTRAR</button>
            </div>
        </form>
    </div>
</div>
@stop
 
@section('js')
<script>
    let paymentConfirmed = false;
    const expectedCost = {{ $costo_inscripcion }};

    document.addEventListener('DOMContentLoaded', function () {
        validarRequisitos();
        validarPago();
    });

    function validarRequisitos() {
        const checks   = document.querySelectorAll('.requisito-check');
        const marcados = document.querySelectorAll('.requisito-check:checked');
        const total    = checks.length;
        const n        = marcados.length;

        document.getElementById('req-count').textContent = n + ' de ' + total + ' requisitos marcados';

        const btnGeneratePago = document.getElementById('btn-generate-payment');
        const alertDanger     = document.getElementById('alert-danger');
        const alertSuccess    = document.getElementById('alert-success');

        if (n === 0) {
            alertDanger.style.display  = 'none';
            alertSuccess.style.display = 'none';
            btnGeneratePago.disabled   = true;
        } else if (n < total) {
            alertDanger.style.display  = 'block';
            alertSuccess.style.display = 'none';
            btnGeneratePago.disabled   = true;
        } else {
            alertDanger.style.display  = 'none';
            alertSuccess.style.display = 'block';
            btnGeneratePago.disabled   = false;
        }

        actualizarBotonSubmit();
    }

    function validarPago() {
        const montoPagoInput = document.getElementById('monto_pago');
        const estadoPago     = document.getElementById('estado_pago');
        const comprobante    = document.getElementById('comprobante');
        const estadoInscripcion = document.getElementById('estado');
        const statusText     = document.getElementById('payment-status');
        const monto          = parseFloat(montoPagoInput.value) || 0;

        if (Math.abs(monto - expectedCost) < 0.001) {
            paymentConfirmed = true;
            estadoPago.value = 'CONFIRMADO';
            if (!comprobante.value) {
                comprobante.value = 'Pago exacto por PayPal';
            }
            estadoInscripcion.value = 'INSCRITO';
            statusText.textContent = 'Pago correcto: el monto coincide con el costo definido.';
            statusText.classList.remove('text-danger');
            statusText.classList.remove('text-muted');
            statusText.classList.add('text-success');
        } else if (monto === 0) {
            paymentConfirmed = false;
            estadoPago.value = 'PENDIENTE';
            estadoInscripcion.value = 'PENDIENTE';
            statusText.textContent = 'Ingrese el monto pagado para validar el pago.';
            statusText.classList.remove('text-success');
            statusText.classList.remove('text-danger');
            statusText.classList.add('text-muted');
        } else {
            paymentConfirmed = false;
            estadoPago.value = 'PENDIENTE';
            estadoInscripcion.value = 'PENDIENTE';
            statusText.textContent = 'Monto incorrecto: el pago no coincide con el costo definido.';
            statusText.classList.remove('text-success');
            statusText.classList.remove('text-muted');
            statusText.classList.add('text-danger');
        }

        actualizarBotonSubmit();
    }

    function actualizarBotonSubmit() {
        const btnSubmit = document.getElementById('btn-submit');
        const checks   = document.querySelectorAll('.requisito-check');
        const marcados = document.querySelectorAll('.requisito-check:checked');
        const total    = checks.length;
        const allRequirementsVerified = total > 0 && marcados.length === total;

        btnSubmit.disabled = !(allRequirementsVerified && paymentConfirmed);
    }

    document.getElementById('btn-generate-payment')?.addEventListener('click', async function () {
        const email = document.getElementById('email').value.trim();
        const alertContainer = document.getElementById('paypal-email-alert');
        const token = document.querySelector('input[name="_token"]').value;

        if (!email) {
            alertContainer.innerHTML = '<div class="alert alert-danger">Debe ingresar un email válido para enviar la solicitud de pago.</div>';
            alertContainer.style.display = 'block';
            return;
        }

        alertContainer.style.display = 'none';

        try {
            const response = await fetch('{{ route('admin.inscripciones.sendPaypalEmail') }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': token,
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email: email,
                    costo: expectedCost
                })
            });
            const data = await response.json();

            if (response.ok) {
                alertContainer.innerHTML = '<div class="alert alert-success">' + data.message + '</div>';
                alertContainer.style.display = 'block';
                document.getElementById('generar_pago').value = '1';
            } else {
                alertContainer.innerHTML = '<div class="alert alert-danger">' + (data.message || 'No se pudo enviar el email de pago.') + '</div>';
                alertContainer.style.display = 'block';
            }
        } catch (error) {
            alertContainer.innerHTML = '<div class="alert alert-danger">Error al enviar el email de PayPal. Intente nuevamente.</div>';
            alertContainer.style.display = 'block';
        }
    });

    document.getElementById('btn-submit')?.addEventListener('click', function () {
        document.getElementById('generar_pago').value = paymentConfirmed ? '1' : '0';
    });

    document.getElementById('monto_pago')?.addEventListener('input', validarPago);
    document.getElementById('estado_pago')?.addEventListener('change', validarPago);

    function previewFoto(event) {
        const [file] = event.target.files;
        const img    = document.getElementById('preview_foto');
        if (file) {
            img.src           = URL.createObjectURL(file);
            img.style.display = 'block';
        } else {
            img.src           = '{{ asset('images/default-user.png') }}';
            img.style.display = 'none';
        }
    }
</script>
@stop