@extends('adminlte::page')
@section('title', 'Bitácora')
@section('content_header')
    <h1>Registro de Actividades (bitacora)</h1>
    <hr>
@stop
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Actividades Registradas</h3>
                </div>
                <div class="card-body">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Usuario</th>
                                <th>Acción</th>
                                <th>Hora</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($bitacora as $entrada)
                                <tr>
                                    <td>{{ $entrada->usuario }}</td>
                                    <td>{{ $entrada->accion }}</td>
                                    <td>{{ $entrada->hora ? $entrada->hora->format('d/m/Y H:i:s') : '' }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="text-center">No hay actividades registradas.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop