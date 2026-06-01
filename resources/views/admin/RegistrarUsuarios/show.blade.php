@extends('adminlte::page')

@section('content_header')
    <h1><b>Detalle de Usuario</b></h1>
    <hr>
@stop

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Usuario</h3>
            </div>
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Email</th>
                        <td>{{ $user->email }}</td>
                    </tr>
                    <tr>
                        <th>Rol</th>
                        <td>{{ $user->roles->pluck('name')->join(', ') }}</td>
                    </tr>
                    <tr>
                        <th>Código</th>
                        <td>
                            @if($user->hasRole('ADMINISTRATIVO') && $user->administrativo)
                                {{ $user->administrativo->codigo }}
                            @elseif($user->hasRole('DOCENTE') && $user->docente)
                                {{ $user->docente->codigo }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Nombre</th>
                        <td>
                            @if($user->hasRole('ADMINISTRATIVO') && $user->administrativo)
                                {{ $user->administrativo->nombre }}
                            @elseif($user->hasRole('DOCENTE') && $user->docente)
                                {{ $user->docente->nombre }}
                            @else
                                {{ $user->name }}
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Apellido</th>
                        <td>
                            @if($user->hasRole('ADMINISTRATIVO') && $user->administrativo)
                                {{ $user->administrativo->apellido }}
                            @elseif($user->hasRole('DOCENTE') && $user->docente)
                                {{ $user->docente->apellido }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>CI</th>
                        <td>
                            @if($user->hasRole('ADMINISTRATIVO') && $user->administrativo)
                                {{ $user->administrativo->ci }}
                            @elseif($user->hasRole('DOCENTE') && $user->docente)
                                {{ $user->docente->ci }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                    <tr>
                        <th>Teléfono</th>
                        <td>
                            @if($user->hasRole('ADMINISTRATIVO') && $user->administrativo)
                                {{ $user->administrativo->telefono }}
                            @elseif($user->hasRole('DOCENTE') && $user->docente)
                                {{ $user->docente->telefono }}
                            @else
                                -
                            @endif
                        </td>
                    </tr>
                </table>
            </div>
            <div class="card-footer text-right">
                <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Volver</a>
            </div>
        </div>
    </div>
</div>
@stop
