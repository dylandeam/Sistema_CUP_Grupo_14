@extends('adminlte::page')

@section('content_header')
    <h1><b>Docentes / Requisitos para Contratación</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-success">
            <div class="card-header">
                <h3 class="card-title">Complete los requisitos del docente</h3>
            </div>

            <div class="card-body">
                {{-- Formulario de requisitos --}}
                <form action="{{ route('admin.requisitos_docente.store', $docente->id) }}" method="post">
                    @csrf

                    {{-- Checkbox Título --}}
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="chk_titulo" name="titulo" value="1" {{ old('titulo') ? 'checked' : '' }}>
                            Tiene Título
                        </label>
                        <input type="text" class="form-control mt-2" id="nombre_titulo" name="nombre_titulo"
                               value="{{ old('nombre_titulo') }}" placeholder="Nombre del título" 
                               style="display: none;">
                    </div>

                    {{-- Checkbox Maestría --}}
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="chk_maestria" name="maestria" value="1" {{ old('maestria') ? 'checked' : '' }}>
                            Tiene Maestría
                        </label>
                        <input type="text" class="form-control mt-2" id="nombre_maestria" name="nombre_maestria"
                               value="{{ old('nombre_maestria') }}" placeholder="Nombre de la maestría" 
                               style="display: none;">
                    </div>

                    {{-- Checkbox Diplomado --}}
                    <div class="form-group">
                        <label>
                            <input type="checkbox" id="chk_diplomado" name="diplomado" value="1" {{ old('diplomado') ? 'checked' : '' }}>
                            Tiene Diplomado
                        </label>
                        <input type="text" class="form-control mt-2" id="nombre_diplomado" name="nombre_diplomado"
                               value="{{ old('nombre_diplomado') }}" placeholder="Nombre del diplomado" 
                               style="display: none;">
                    </div>

                    {{-- Área de especialidad (se llenará automáticamente en el controlador) --}}
                    <div class="form-group">
                        <label for="area_especialidad">Área de Especialidad:</label>
                        <input type="text" class="form-control" id="area_especialidad" name="area_especialidad"
                               value="{{ old('area_especialidad') }}" placeholder="Ej: Matemáticas, Computación, Física, Inglés">
                    </div>

                    {{-- Botones --}}
                    <div class="form-group">
                        <a href="{{ route('admin.docentes.index') }}" class="btn btn-secondary">Cancelar</a>
                        <button type="submit" class="btn btn-success">Registrar Docente</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script>
    // Mostrar/ocultar campos según checkbox
    document.getElementById('chk_titulo').addEventListener('change', function() {
        document.getElementById('nombre_titulo').style.display = this.checked ? 'block' : 'none';
    });
    document.getElementById('chk_maestria').addEventListener('change', function() {
        document.getElementById('nombre_maestria').style.display = this.checked ? 'block' : 'none';
    });
    document.getElementById('chk_diplomado').addEventListener('change', function() {
        document.getElementById('nombre_diplomado').style.display = this.checked ? 'block' : 'none';
    });
</script>
@stop
