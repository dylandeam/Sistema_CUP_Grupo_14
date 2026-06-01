@extends('adminlte::page')

@section('content_header')
    <h1><b>Administrativos / Registro de un nuevo administrativo</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Rellene los datos del Formulario</h3>
                </div>

                <div class="card-body">
                    <form action="{{ url('admin/administrativos') }}" method="post" enctype="multipart/form-data">
                        @csrf

                        {{-- Código --}}
                        <div class="form-group">
                            <label for="codigo">Código: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                </div>
                                <input type="text" class="form-control" name="codigo" 
                                       value="{{ old('codigo') }}" placeholder="Se generará automáticamente" readonly>
                            </div>
                            @error('codigo')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Nombre --}}
                        <div class="form-group">
                            <label for="nombre">Nombre: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user"></i></span>
                                </div>
                                <input type="text" class="form-control" name="nombre"
                                       value="{{ old('nombre') }}" placeholder="Ej: Maria" required>
                            </div>
                            @error('nombre')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Apellido --}}
                        <div class="form-group">
                            <label for="apellido">Apellido: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                </div>
                                <input type="text" class="form-control" name="apellido"
                                       value="{{ old('apellido') }}" placeholder="Ej: Perez" required>
                            </div>
                            @error('apellido')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- CI --}}
                        <div class="form-group">
                            <label for="ci">CI: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                </div>
                                <input type="text" class="form-control" name="ci"
                                       value="{{ old('ci') }}" placeholder="Ej: 9876543" required>
                            </div>
                            @error('ci')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Fecha de nacimiento --}}
                        <div class="form-group">
                            <label for="fecha_nacimiento">Fecha de nacimiento: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                </div>
                                <input type="date" class="form-control" name="fecha_nacimiento"
                                       value="{{ old('fecha_nacimiento') }}" required>
                            </div>
                            @error('fecha_nacimiento')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Email --}}
                        <div class="form-group">
                            <label for="email">Email: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                </div>
                                <input type="email" class="form-control" name="email"
                                       value="{{ old('email') }}" placeholder="Ej: ejemplo@correo.com" required>
                            </div>
                            @error('email')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Teléfono --}}
                        <div class="form-group">
                            <label for="telefono">Teléfono: </label>
                            <input type="text" class="form-control" name="telefono"
                                   value="{{ old('telefono') }}" placeholder="Ej: 70012345">
                            @error('telefono')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Dirección --}}
                        <div class="form-group">
                            <label for="direccion">Dirección: </label>
                            <input type="text" class="form-control" name="direccion"
                                   value="{{ old('direccion') }}" placeholder="Ej: Av. Busch #123">
                            @error('direccion')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Cargo --}}
                        <div class="form-group">
                            <label for="cargo">Cargo: </label>
                            <input type="text" class="form-control" name="cargo"
                                   value="{{ old('cargo') }}" placeholder="Ej: Secretaria" required>
                            @error('cargo')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Foto --}}
                        <div class="form-group">
                            <label for="foto">Foto: </label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewFoto(event)">
                            @error('foto')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                            <div class="mt-2">
                                <img id="preview_foto" src="{{ asset('images/default-user.png') }}" alt="Previsualización" width="150" class="img-thumbnail" style="display:none;">
                            </div>
                        </div>

                        {{-- Rol jalado de la tabla roles --}}
                        @php
                            $adminRole = $roles->firstWhere('name', 'ADMINISTRATIVO');
                        @endphp
                        <input type="hidden" name="rol" value="{{ $adminRole->name }}">
                        <div class="form-group">
                            <label>Rol: </label>
                            <input type="text" class="form-control" value="{{ $adminRole->name }}" disabled>
                        </div>

                        {{-- Botones --}}
                        <div class="form-group">
                            <a href="{{ url('/admin/administrativos') }}" class="btn btn-secondary">Cancelar</a>
                            <button type="submit" class="btn btn-primary">Registrar</button>
                        </div>

                        <script>
                            function previewFoto(event) {
                                const [file] = event.target.files;
                                const img = document.getElementById('preview_foto');
                                if (file) {
                                    img.src = URL.createObjectURL(file);
                                    img.style.display = 'block';
                                } else {
                                    img.src = '{{ asset('images/default-user.png') }}';
                                    img.style.display = 'none';
                                }
                            }
                        </script>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
