@extends('adminlte::page')

@section('content_header')
    <h1><b>Docentes / Editar Docente</b></h1>
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

        <form action="{{ route('admin.docentes.update', $docente) }}" method="post" enctype="multipart/form-data" id="form-docente">
            @csrf
            @method('PUT')

            {{-- 1. DATOS PERSONALES --}}
            <div class="card card-outline card-primary mb-4">
                <div class="card-header bg-primary">
                    <h3 class="card-title">1. Datos personales</h3>
                </div>

                {{--Código --}}
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="codigo">Código</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-barcode"></i></span></div>
                                    <input type="text" class="form-control" id="codigo" name="codigo" value="{{ old('codigo', $docente->codigo) }}" readonly>
                                </div>
                            </div>
                        </div>

                        {{--Rol --}}
                        <div class="col-md-6">
                            @php $docenteRole = $roles->firstWhere('name', 'DOCENTE'); @endphp
                            <input type="hidden" name="rol" value="{{ old('rol', $docente->user->roles->pluck('name')->first() ?? $docenteRole->name ?? '') }}">
                            <div class="form-group">
                                <label>Rol</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user-shield"></i></span></div>
                                    <input type="text" class="form-control" value="{{ old('rol', $docente->user->roles->pluck('name')->first() ?? $docenteRole->name ?? '') }}" disabled>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Nombre --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre">Nombre</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user"></i></span></div>
                                    <input type="text" class="form-control" id="nombre" name="nombre" value="{{ old('nombre', $docente->nombre) }}" placeholder="Ej: Juan" required>
                                </div>
                            </div>
                        </div>

                        {{-- Apellido --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="apellido">Apellido</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-user-tag"></i></span></div>
                                    <input type="text" class="form-control" id="apellido" name="apellido" value="{{ old('apellido', $docente->apellido) }}" placeholder="Ej: Pérez" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- CI --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="ci">CI</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-id-card"></i></span></div>
                                    <input type="text" class="form-control" id="ci" name="ci" value="{{ old('ci', $docente->ci) }}" placeholder="Ej: 9876543" required>
                                </div>
                            </div>
                        </div>

                        {{-- Fecha de nacimiento --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="fecha_nacimiento">Fecha de nacimiento</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-calendar"></i></span></div>
                                    <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" value="{{ old('fecha_nacimiento', $docente->fecha_nacimiento) }}" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Email --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="email">Email</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-envelope"></i></span></div>
                                    <input type="email" class="form-control" id="email" name="email" value="{{ old('email', $docente->user->email) }}" placeholder="Ej: ejemplo@correo.com" required>
                                </div>
                            </div>
                        </div>

                        {{-- Teléfono --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telefono">Teléfono</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-phone"></i></span></div>
                                    <input type="text" class="form-control" id="telefono" name="telefono" value="{{ old('telefono', $docente->telefono) }}" placeholder="Ej: 70012345">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Dirección --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="direccion">Dirección</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span></div>
                                    <input type="text" class="form-control" id="direccion" name="direccion" value="{{ old('direccion', $docente->direccion) }}" placeholder="Ej: Av. Busch #123">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{--Foto --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="foto">Foto</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-image"></i></span></div>
                                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*" onchange="previewFoto(event)">
                                </div>
                                @if($docente->foto)
                                    <p>Foto actual:</p>
                                    <img id="preview_foto" src="{{ asset('storage/'.$docente->foto) }}" alt="Previsualización" width="150" class="img-thumbnail" style="display:block;">
                                @else
                                    <img id="preview_foto" src="{{ asset('images/default-user.png') }}" alt="Previsualización" width="150" class="img-thumbnail" style="display:none;">
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- 2. REQUISITOS ACADÉMICOS --}}
            <div class="card card-outline card-success mb-4">
                <div class="card-header bg-success">
                    <h3 class="card-title">2. Requisitos académicos</h3>
                </div>
                <div class="card-body">
                    @php $requisito = $docente->requisitos->first(); @endphp

                    {{-- Titulo --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre_titulo">Nombre del Título</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-graduation-cap"></i></span></div>
                                    <input type="text" class="form-control requisito-input" id="nombre_titulo" name="nombre_titulo" value="{{ old('nombre_titulo', $requisito->nombre_titulo ?? '') }}" placeholder="Ej: Licenciatura en Informática" required>
                                </div>
                            </div>
                        </div>

                        {{--Maestría --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre_maestria">Nombre de la Maestría</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-award"></i></span></div>
                                    <input type="text" class="form-control requisito-input" id="nombre_maestria" name="nombre_maestria" value="{{ old('nombre_maestria', $requisito->nombre_maestria ?? '') }}" placeholder="Ej: Maestría en Ciencias de la Computación" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Diplomado --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="nombre_diplomado">Nombre del Diplomado</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-certificate"></i></span></div>
                                    <input type="text" class="form-control requisito-input" id="nombre_diplomado" name="nombre_diplomado" value="{{ old('nombre_diplomado', $requisito->nombre_diplomado ?? '') }}" placeholder="Ej: Diplomado en Educación Superior" required>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div id="error_requisitos" class="alert alert-danger mt-3" style="display: none;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        <span id="error_mensaje_texto">No cumple con los requisitos, por lo tanto, no puede ser contratado.</span>
                    </div>
                </div>
            </div>

            {{-- 3. MATERIA Y ESTADO --}}
            <div class="card card-outline card-info mb-4">
                <div class="card-header bg-info">
                    <h3 class="card-title">3. Materia y estado de contratación</h3>
                </div>
                <div class="card-body">

                    {{-- Materia --}}
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="materia_id">Materia</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-book"></i></span></div>
                                    <select id="materia_id" name="materia_id" class="form-control" required>
                                        <option value="">Selecciona una materia</option>
                                        @foreach($materias as $materia)
                                            <option value="{{ $materia->id }}" {{ old('materia_id', $asignacion->materia_id ?? $docente->materias->first()->id ?? '') == $materia->id ? 'selected' : '' }}>{{ $materia->nombre }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        {{-- Estado de contratación --}}
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="estado">Estado de contratación</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend"><span class="input-group-text"><i class="fas fa-check-circle"></i></span></div>
                                    <select id="estado" name="estado" class="form-control" required>
                                        <option value="activo" {{ old('estado', $asignacion->estado ?? 'activo') == 'activo' ? 'selected' : '' }}>Activo</option>
                                        <option value="baja" {{ old('estado', $asignacion->estado ?? 'activo') == 'baja' ? 'selected' : '' }}>Baja</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Botones de Acción --}}
            <div class="form-group">
                <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">CANCELAR</a>
                <button type="submit" id="btn-submit" class="btn btn-success">GUARDAR CAMBIOS</button>
            </div>
        </form>
    </div>
</div>
@stop

@section('js')
<script>

    // Validación de requisitos académicos en tiempo real
    document.addEventListener('DOMContentLoaded', function() {
        const inputsRequisitos = document.querySelectorAll('.requisito-input');
        inputsRequisitos.forEach(input => {
            input.addEventListener('input', validarRequisitos);
        });
        validarRequisitos();
    });

    // Función para validar los requisitos académicos
    function validarRequisitos() {
        const titulo = document.getElementById('nombre_titulo').value.trim();
        const maestria = document.getElementById('nombre_maestria').value.trim();
        const diplomado = document.getElementById('nombre_diplomado').value.trim();
        const errorDiv = document.getElementById('error_requisitos');
        const mensajeTexto = document.getElementById('error_mensaje_texto');
        const btnSubmit = document.getElementById('btn-submit');

        if (titulo === "" || maestria === "" || diplomado === "") {
            mensajeTexto.innerText = "No cumple con los requisitos (debe registrar Título, Maestría y Diplomado), por lo tanto, no puede ser contratado.";
            errorDiv.style.display = 'block';
            btnSubmit.disabled = true;
            return;
        }

        const textoCompleto = (titulo + ' ' + maestria + ' ' + diplomado).toLowerCase();
        const regexMatematicas = /matem[aá]tica|álgebra|estad[ií]stica|geometr[ií]a|probabilidad|cálculo|matemáticas/;
        const regexComputacion = /computaci[oó]n|programaci[oó]n|sistemas|software|inform[aá]tica|tecnolog[ií]a/;
        const regexFisica = /f[ií]sica|ingenier[ií]a|mec[aá]nica|electr[oó]nica|qu[ií]mica/;
        const regexIngles = /ingl[eé]s|idioma|lengua extranjera|traducci[oó]n|literatura inglesa/;
        const perteneceAlArea = regexMatematicas.test(textoCompleto) || regexComputacion.test(textoCompleto) || regexFisica.test(textoCompleto) || regexIngles.test(textoCompleto);

        if (!perteneceAlArea) {
            mensajeTexto.innerText = "Los estudios registrados no corresponden a las áreas requeridas (Matemática, Física, Computación o Inglés). No cumple con los requisitos, no puede ser contratado.";
            errorDiv.style.display = 'block';
            btnSubmit.disabled = true;
        } else {
            errorDiv.style.display = 'none';
            btnSubmit.disabled = false;
        }
    }

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
