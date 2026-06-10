@extends('adminlte::page')

@section('content_header')
    <h1><b>Carga Masiva de Administrativos</b></h1>
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
                <form action="{{ route('admin.administrativos.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label for="file">Seleccione archivo:</label>
                        <input type="file" name="file" id="file" class="form-control" accept=".xlsx,.csv" required>
                        @error('file')
                            <small style="color:red;">{{ $message }}</small>
                        @enderror
                    </div>

                    <hr>

                    {{-- Botones de Acción --}}
                    <div class="form-group">
                        <a href="{{ route('admin.administrativos.index') }}" class="btn btn-secondary">CANCELAR</a>
                        <button type="submit" class="btn btn-success">CARGAR</button>

                        {{-- Botón de ver solo si fue éxito --}}
                        @if(session('icono') === 'success')
                            <a href="{{ route('admin.administrativos.index') }}" class="btn btn-info">Ver Administrativos</a>
                        @endif
                    </div>
                </form>

                <hr>
                <p><b>Formato esperado del archivo:</b></p>
                <ul>
                    <li><code>nombre</code></li>
                    <li><code>apellido</code></li>
                    <li><code>ci</code></li>
                    <li><code>fecha_nacimiento</code> (formato: YYYY-MM-DD)</li>
                    <li><code>email</code></li>
                    <li><code>telefono</code></li>
                    <li><code>direccion</code></li>
                    <li><code>cargo</code></li>
                    <li><code>rol</code> (opcional, si está vacío se asigna <b>ADMINISTRATIVO</b>)</li>
                </ul>
            </div>
        </div>
    </div>
</div>
@stop
