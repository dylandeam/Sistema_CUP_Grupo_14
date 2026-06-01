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
                    
                    {{-- Formulario para editar un administrativo existente --}}
                    <form action="{{ url('/admin/administrativos/'.$administrativo->id) }}" method="post" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')

                        {{-- Código (solo lectura) --}}
                        <div class="form-group">
                            <label for="codigo">Código: </label>
                            <input type="text" class="form-control" id="codigo" name="codigo" 
                                   value="{{ old('codigo', $administrativo->codigo) }}" readonly>
                            @error('codigo')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Nombre --}}
                        <div class="form-group">
                            <label for="nombre">Nombre: </label>
                            <input type="text" class="form-control" id="nombre" name="nombre" 
                                   value="{{ old('nombre', $administrativo->nombre) }}" required>
                            @error('nombre')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Apellido --}}
                        <div class="form-group">
                            <label for="apellido">Apellido: </label>
                            <input type="text" class="form-control" id="apellido" name="apellido" 
                                   value="{{ old('apellido', $administrativo->apellido) }}" required>
                            @error('apellido')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- CI --}}
                        <div class="form-group">
                            <label for="ci">CI: </label>
                            <input type="text" class="form-control" id="ci" name="ci" 
                                   value="{{ old('ci', $administrativo->ci) }}" required>
                            @error('ci')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Fecha de nacimiento --}}
                        <div class="form-group">
                            <label for="fecha_nacimiento">Fecha de nacimiento: </label>
                            <input type="date" class="form-control" id="fecha_nacimiento" name="fecha_nacimiento" 
                                   value="{{ old('fecha_nacimiento', $administrativo->fecha_nacimiento) }}" required>
                            @error('fecha_nacimiento')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Email (editable) --}}
                        <div class="form-group">
                            <label for="email">Email: </label>
                            <input type="email" class="form-control" id="email" name="email" 
                                   value="{{ old('email', $administrativo->user->email) }}" required>
                            @error('email')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Teléfono --}}
                        <div class="form-group">
                            <label for="telefono">Teléfono: </label>
                            <input type="text" class="form-control" id="telefono" name="telefono" 
                                   value="{{ old('telefono', $administrativo->telefono) }}">
                            @error('telefono')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Dirección --}}
                        <div class="form-group">
                            <label for="direccion">Dirección: </label>
                            <input type="text" class="form-control" id="direccion" name="direccion" 
                                   value="{{ old('direccion', $administrativo->direccion) }}">
                            @error('direccion')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Cargo --}}
                        <div class="form-group">
                            <label for="cargo">Cargo: </label>
                            <input type="text" class="form-control" id="cargo" name="cargo" 
                                   value="{{ old('cargo', $administrativo->cargo) }}" required>
                            @error('cargo')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Foto --}}
                        <div class="form-group">
                            <label for="foto">Foto: </label>
                            <input type="file" class="form-control" id="foto" name="foto" accept="image/*">
                            @error('foto')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                            @if($administrativo->foto)
                                <p>Foto actual:</p>
                                <img src="{{ asset('storage/'.$administrativo->foto) }}" alt="Foto" width="100">
                            @endif
                        </div>

                        {{-- Rol (no editable) --}}
                        @php $currentRole = old('rol', $administrativo->user->roles->first()?->name) ?? ''; @endphp
                        <input type="hidden" name="rol" value="{{ $currentRole }}">
                        <div class="form-group">
                            <label for="rol">Rol: </label>
                            <input type="text" class="form-control" id="rol" value="{{ $currentRole }}" disabled>
                            @error('rol')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Botones de acción --}}
                        <div class="form-group">
                            <button type="submit" class="btn btn-success">Actualizar Administrativo</button>
                            <a href="{{ url('admin/administrativos') }}" class="btn btn-secondary">Cancelar</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop
