@extends('adminlte::page')

@section('content_header')
    <h1><b>Carga Masiva de Postulantes</b></h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Subir archivo Excel o CSV</h3>
            </div>
            <div class="card-body" style="padding: 20px;">

                {{-- Mensajes de error o éxito --}}
                @if(session('mensaje'))
                    <div class="alert alert-{{ session('icono') }}">
                        {!! session('mensaje') !!}
                    </div>
                @endif

                {{-- Formulario --}}
                <form action="{{ route('admin.postulantes.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Seleccione archivo:</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.csv" required>
                        @error('file')
                            <small style="color:red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <hr>

                    <div class="form-group">
                        <a href="{{ route('admin.postulantes.index') }}" class="btn btn-secondary">CANCELAR</a>
                        <button type="submit" class="btn btn-success">CARGAR</button>

                        @if(session('icono') === 'success')
                            <a href="{{ route('admin.postulantes.index') }}" class="btn btn-info">Ver Postulantes</a>
                        @endif
                    </div>
                </form>

                <hr>
                <p><b>Formato esperado del archivo:</b></p>
                <ul>
                    <li><code>gestion</code></li>
                    <li><code>modalidad</code> (nombre de la modalidad, ej: Presencial)</li>
                    <li><code>turno</code> (nombre del turno, ej: Mañana, Tarde, Noche)</li>
                    <li><code>nombre</code></li>
                    <li><code>apellidos</code></li>
                    <li><code>ci</code></li>
                    <li><code>email</code></li>
                    <li><code>fecha_nacimiento</code> (formato: YYYY-MM-DD)</li>
                    <li><code>sexo</code> (<b>Masculino</b>, <b>Femenino</b> u <b>Otro</b>)</li>
                    <li><code>telefono</code></li>
                    <li><code>direccion</code></li>
                    <li><code>ciudad</code></li>
                    <li><code>colegio</code></li>
                    <li><code>primera_opcion</code> (nombre de la carrera)</li>
                    <li><code>segunda_opcion</code> (nombre de la carrera)</li>
                </ul>

                <div class="alert alert-info mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    <b>El sistema asignará automáticamente:</b>
                    <ul class="mb-0 mt-1">
                        <li>Estado de inscripción: <b>INSCRITO</b></li>
                        <li>Fecha de inscripción: <b>fecha de carga del archivo</b></li>
                        <li>Costo de inscripción: <b>según el costo fijo configurado</b></li>
                        <li>Requisitos del postulante: <b>todos marcados como verdadero</b></li>
                        <li>Monto de pago: <b>según el costo fijo configurado</b></li>
                        <li>Fecha de pago: <b>fecha de carga del archivo</b></li>
                        <li>Estado de pago: <b>CONFIRMADO</b></li>
                        <li>Comprobante: <b>Extracto por PayPal</b></li>
                    </ul>
                </div>

                <p class="mt-3"><b>Nota:</b> El campo <code>gestion</code> debe usar el formato <code>semestre-año</code> (ej: <b>1-2026</b>, <b>2-2025</b>).
                La gestión debe existir y estar activa en el sistema.</p>

            </div>
        </div>
    </div>
</div>
@stop