@extends('adminlte::page')

@section('content_header')
    <h1><b>Postulantes / Editar Postulante</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-primary">
            <div class="card-header">
                <h3 class="card-title">Modifique los Datos del Formulario</h3>
            </div>
            <div class="card-body">

                @if ($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.postulantes.update', $postulante->codigo) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    @php
                        $currentRole = old('rol', $postulante->user->roles->first()?->name) ?? '';
                    @endphp
                    <input type="hidden" name="rol" value="{{ $currentRole }}">

                    {{-- Código --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codigo">Código:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="codigo" value="{{ $postulante->codigo }}" readonly>
                                </div>
                            </div>
                        </div>

                        {{-- Rol --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="rol">Rol:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="rol" value="{{ $currentRole }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Nombre--}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $postulante->nombre) }}" required>
                                </div>
                                @error('nombre')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Apellidos --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellidos">Apellidos:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="apellidos" name="apellidos" value="{{ old('apellidos', $postulante->apellidos) }}" required>
                                </div>
                                @error('apellidos')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- CI --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ci">CI:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="ci" name="ci" value="{{ old('ci', $postulante->ci) }}" required>
                                </div>
                                @error('ci')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Fecha de nacimiento --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $postulante->fecha_nacimiento ? \Carbon\Carbon::parse($postulante->fecha_nacimiento)->format('Y-m-d') : '') }}" required>
                                </div>
                                @error('fecha_nacimiento')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Sexo --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="sexo">Sexo:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-venus-mars"></i></span>
                                    </div>
                                    <select class="form-control" id="sexo" name="sexo" required>
                                        <option value="">Seleccionar...</option>
                                        <option value="M" {{ old('sexo', $postulante->sexo) == 'M' ? 'selected' : '' }}>Masculino</option>
                                        <option value="F" {{ old('sexo', $postulante->sexo) == 'F' ? 'selected' : '' }}>Femenino</option>
                                        <option value="O" {{ old('sexo', $postulante->sexo) == 'O' ? 'selected' : '' }}>Otro</option>
                                    </select>
                                </div>
                                @error('sexo')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Email --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                    </div>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $postulante->user->email) }}" required>
                                </div>
                                @error('email')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Teléfono --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono">Teléfono:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono', $postulante->telefono) }}">
                                </div>
                                @error('telefono')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Dirección --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion">Dirección:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion', $postulante->direccion) }}">
                                </div>
                                @error('direccion')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Ciudad --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ciudad">Ciudad:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-city"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="ciudad" name="ciudad" value="{{ old('ciudad', $postulante->ciudad) }}">
                                </div>
                                @error('ciudad')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Colegio de procedencia --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="colegio">Colegio de procedencia:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-school"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="colegio" name="colegio" value="{{ old('colegio', $postulante->colegio) }}">
                                </div>
                                @error('colegio')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>
                    </div>

                    {{-- Foto --}}
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="foto">Foto:</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-image"></i></span>
                                    </div>
                                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewFoto(event)">
                                </div>
                                @error('foto')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                                <div class="mt-2">
                                    <img id="preview_foto" src="{{ asset('images/default-user.png') }}" alt="Previsualización" width="150" class="img-thumbnail" style="display:none;">
                                </div>
                                @if($postulante->foto)
                                    <p class="mt-2">Foto actual:</p>
                                    <img src="{{ asset('storage/' . $postulante->foto) }}" alt="Foto actual" width="100" class="img-thumbnail">
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Botones de Acción --}}
                    <div class="form-group">
                        <a href="{{ route('admin.postulantes.index') }}" class="btn btn-secondary">CANCELAR</a>
                        <button type="submit" class="btn btn-success">GUARDAR CAMBIOS</button>
                    </div>

                </form>

            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    function previewFoto(event) {
        const [file] = event.target.files;
        const img = document.getElementById('preview_foto');
        if (file) {
            img.src = URL.createObjectURL(file);
            img.style.display = 'block';
        }
    }
</script>
@stop