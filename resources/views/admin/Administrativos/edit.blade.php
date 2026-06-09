@extends('adminlte::page')

@section('content_header')
    <h1><b>Administrativos / Editar Administrativo</b></h1>
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

                    <form action="{{ route('admin.administrativos.update', $administrativo->codigo) }}" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Código + Rol --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="codigo">Código:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="codigo"
                                               value="{{ $administrativo->codigo }}" readonly>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                @php $currentRole = old('rol', $administrativo->user->roles->first()?->name) ?? ''; @endphp
                                <input type="hidden" name="rol" value="{{ $currentRole }}">
                                <div class="form-group">
                                    <label for="rol">Rol:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-shield"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="rol"
                                               value="{{ $currentRole }}" disabled>
                                    </div>
                                </div>
                            </div>
                        </div>

                        {{-- Nombre + Apellido --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="nombre">Nombre:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                               value="{{ old('nombre', $administrativo->nombre) }}" required>
                                    </div>
                                    @error('nombre')
                                        <small style="color: red;">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="apellido">Apellido:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user-tag"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="apellido" name="apellido"
                                               value="{{ old('apellido', $administrativo->apellido) }}" required>
                                    </div>
                                    @error('apellido')
                                        <small style="color: red;">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- CI + Fecha de nacimiento --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="ci">CI:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-id-card"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="ci" name="ci"
                                               value="{{ old('ci', $administrativo->ci) }}" required>
                                    </div>
                                    @error('ci')
                                        <small style="color: red;">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fecha_nacimiento">Fecha de nacimiento:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                        </div>
                                        <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento"
                                               value="{{ old('fecha_nacimiento', $administrativo->fecha_nacimiento) }}" required>
                                    </div>
                                    @error('fecha_nacimiento')
                                        <small style="color: red;">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Email + Teléfono --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="email">Email:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-envelope"></i></span>
                                        </div>
                                        <input type="email" class="form-control" id="email" name="email"
                                               value="{{ old('email', $administrativo->user->email) }}" required>
                                    </div>
                                    @error('email')
                                        <small style="color: red;">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="telefono">Teléfono:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-phone"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="telefono" name="telefono"
                                               value="{{ old('telefono', $administrativo->telefono) }}">
                                    </div>
                                    @error('telefono')
                                        <small style="color: red;">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Dirección + Cargo --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="direccion">Dirección:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="direccion" name="direccion"
                                               value="{{ old('direccion', $administrativo->direccion) }}">
                                    </div>
                                    @error('direccion')
                                        <small style="color: red;">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="cargo">Cargo:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-briefcase"></i></span>
                                        </div>
                                        <input type="text" class="form-control" id="cargo" name="cargo"
                                               value="{{ old('cargo', $administrativo->cargo) }}" required>
                                    </div>
                                    @error('cargo')
                                        <small style="color: red;">{{ $message }}</small>
                                    @enderror
                                </div>
                            </div>
                        </div>

                        {{-- Foto (sola) --}}
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="foto">Foto:</label>
                                    <div class="input-group mb-3">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-image"></i></span>
                                        </div>
                                        <input type="file" class="form-control" id="foto" name="foto"
                                               accept="image/*" onchange="previewFoto(event)">
                                    </div>
                                    @error('foto')
                                        <small style="color: red;">{{ $message }}</small>
                                    @enderror
                                    <div class="mt-2">
                                        <img id="preview_foto" src="{{ asset('images/default-user.png') }}"
                                             alt="Previsualización" width="150" class="img-thumbnail" style="display:none;">
                                    </div>
                                    @if($administrativo->foto)
                                        <p class="mt-2">Foto actual:</p>
                                        <img src="{{ asset('storage/' . $administrativo->foto) }}"
                                             alt="Foto actual" width="100" class="img-thumbnail">
                                    @endif
                                </div>
                            </div>
                        </div>

                        {{-- Botones de Acción --}}
                        <div class="form-group">
                            <a href="{{ route('admin.administrativos.index') }}" class="btn btn-secondary">CANCELAR</a>
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
        } else {
            img.src = '{{ asset('images/default-user.png') }}';
            img.style.display = 'none';
        }
    }
</script>
@stop
