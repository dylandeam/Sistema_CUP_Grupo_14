@extends('adminlte::page')

@section('content_header')
    <h1><b>Horarios / Editar Horario</b></h1>
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
                    
                    <form action="{{ url('/admin/horarios/'.$horario->id) }}" method="post">
                        @csrf
                        @method('PUT')

                        {{-- Turno --}}
                        <div class="form-group">
                            <label for="turno_id">Turno: </label>
                            <div class="input-group mb-3">
                                <div class="input-group-prepend">
                                    <span class="input-group-text"><i class="fas fa-list"></i></span>
                                </div>
                                <select name="turno_id" class="form-control" required>
                                    <option value="">-- Seleccione un turno --</option>
                                    @foreach($turnos as $turno)
                                        <option value="{{ $turno->id }}" 
                                            {{ old('turno_id', $horario->turno_id) == $turno->id ? 'selected' : '' }}>
                                            {{ $turno->nombre }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            @error('turno_id')
                                <small class="text-danger">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Hora de inicio --}}
                        <div class="form-group">
                            <label for="hora_inicio">Hora de inicio: </label>
                            <input type="time" class="form-control" id="hora_inicio" name="hora_inicio" 
                                   value="{{ old('hora_inicio', $horario->hora_inicio) }}" required>
                            @error('hora_inicio')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Hora de fin --}}
                        <div class="form-group">
                            <label for="hora_fin">Hora de fin: </label>
                            <input type="time" class="form-control" id="hora_fin" name="hora_fin" 
                                   value="{{ old('hora_fin', $horario->hora_fin) }}" required>
                            @error('hora_fin')
                                <small style="color: red;">{{ $message }}</small>
                            @enderror
                        </div>

                        {{-- Botones de acción --}}
                        <div class="form-group">
                            <a href="{{ url('admin/horarios') }}" class="btn btn-secondary">CANCELAR</a>
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
