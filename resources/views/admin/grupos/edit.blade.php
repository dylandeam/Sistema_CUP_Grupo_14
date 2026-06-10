@extends('adminlte::page')

@section('content_header')
    <h1><b>Grupos / Editar Grupo</b></h1>
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
                    
                    <form action="{{ url('/admin/grupos/'.$grupo->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        {{-- Nombre + Cupos --}}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="nombre">Nombre del grupo: </label>
                                <input type="text" class="form-control" id="nombre" name="nombre" 
                                       value="{{ old('nombre', $grupo->nombre) }}">
                                @error('nombre')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="cupos">Cupos: </label>
                                <input type="number" class="form-control" id="cupos" name="cupos" 
                                       value="{{ old('cupos', $grupo->cupos) }}" min="1" readonly>
                                @error('cupos')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Gestión + Turno --}}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="id_gestion">Gestión: </label>
                                <input type="text" class="form-control" 
                                       value="{{ $gestionActiva->semestre }} - {{ $gestionActiva->año }}" readonly>
                                <input type="hidden" name="id_gestion" value="{{ $gestionActiva->id }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="id_turno">Turno: </label>
                                <select class="form-control" id="id_turno" name="id_turno" required>
                                    @foreach($turnos as $turno)
                                        <option value="{{ $turno->id }}" 
                                            {{ old('id_turno', $grupo->id_turno) == $turno->id ? 'selected' : '' }}>
                                            {{ $turno->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('id_turno')
                                    <small style="color: red;">{{ $message }}</small>
                                @enderror
                            </div>
                        </div>

                        {{-- Modalidad + Aula --}}
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="id_modalidad">Modalidad: </label>
                                <input type="text" class="form-control" 
                                       value="{{ $grupo->modalidad->nombre }}" readonly>
                                <input type="hidden" name="id_modalidad" value="{{ $grupo->id_modalidad }}">
                            </div>
                            <div class="form-group col-md-6">
                                <label for="id_aula">Aula: </label>
                                @if(strtolower($grupo->modalidad->nombre) === 'virtual')
                                    <input type="text" class="form-control" value="-" readonly>
                                    <input type="hidden" name="id_aula" value="">
                                @else
                                    <select class="form-control" id="id_aula" name="id_aula" required>
                                        @foreach($aulas as $aula)
                                            <option value="{{ $aula->id }}" 
                                                {{ old('id_aula', $grupo->id_aula) == $aula->id ? 'selected' : '' }}>
                                                Aula {{ $aula->nro_aula }} - {{ $aula->modulo }} - Piso {{ $aula->piso }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('id_aula')
                                        <small style="color: red;">{{ $message }}</small>
                                    @enderror
                                @endif
                            </div>
                        </div>

                        {{-- Botones de acción --}}
                        <div class="form-group">
                            <a href="{{ url('admin/grupos') }}" class="btn btn-secondary">CANCELAR</a>
                            <button type="submit" class="btn btn-success">GUARDAR CAMBIOS</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
@stop

@section('js')
@stop
