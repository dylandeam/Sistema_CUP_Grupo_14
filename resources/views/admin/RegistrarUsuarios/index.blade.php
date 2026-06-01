@extends('adminlte::page')

@section('plugins.Datatables', true)

@section('content_header')
    <h1><b>Listado de Usuarios</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Usuarios Registrados</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
                        Registrar Usuario
                    </a>
                </div>
            </div>

            <div class="card-body">
                <table id="users-table" class="table table-bordered table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th class="text-center">Nro</th>
                            <th class="text-center">Email</th>
                            <th class="text-center">Código</th>
                            <th class="text-center">Nombre</th>
                            <th class="text-center">Apellido</th>
                            <th class="text-center">CI</th>
                            <th class="text-center">Teléfono</th>
                            <th class="text-center">Rol</th>
                            <th class="text-center">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $contador = 1; @endphp
                        @foreach($users as $user)
                            <tr>
                                <td class="text-center">{{ $contador++ }}</td>
                                <td>{{ $user->email }}</td>
                                <td class="text-center">
                                    @if($user->hasRole('ADMINISTRATIVO') && $user->administrativo)
                                        {{ $user->administrativo->codigo }}
                                    @elseif($user->hasRole('DOCENTE') && $user->docente)
                                        {{ $user->docente->codigo }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($user->hasRole('ADMINISTRATIVO') && $user->administrativo)
                                        {{ $user->administrativo->nombre }}
                                    @elseif($user->hasRole('DOCENTE') && $user->docente)
                                        {{ $user->docente->nombre }}
                                    @else
                                        {{ $user->name }}
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($user->hasRole('ADMINISTRATIVO') && $user->administrativo)
                                        {{ $user->administrativo->apellido }}
                                    @elseif($user->hasRole('DOCENTE') && $user->docente)
                                        {{ $user->docente->apellido }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($user->hasRole('ADMINISTRATIVO') && $user->administrativo)
                                        {{ $user->administrativo->ci }}
                                    @elseif($user->hasRole('DOCENTE') && $user->docente)
                                        {{ $user->docente->ci }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($user->hasRole('ADMINISTRATIVO') && $user->administrativo)
                                        {{ $user->administrativo->telefono }}
                                    @elseif($user->hasRole('DOCENTE') && $user->docente)
                                        {{ $user->docente->telefono }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td class="text-center">{{ $user->roles->pluck('name')->join(', ') }}</td>
                                <td class="text-center">
                                    <div class="btn-group" role="group">
                                        <a href="{{ route('admin.users.show', $user->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                        <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                        <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" id="delete-user-{{ $user->id }}" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger btn-sm" onclick="confirmDelete({{ $user->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@stop

@section('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    function confirmDelete(userId) {
        Swal.fire({
            title: '¿Eliminar usuario?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Eliminar',
            cancelButtonText: 'Cancelar',
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('delete-user-' + userId).submit();
            }
        });
    }

    $(function () {
        $('#users-table').DataTable({
            pageLength: 5,
            responsive: true,
            autoWidth: false,
            language: {
                emptyTable: 'No hay información',
                info: 'Mostrando _START_ a _END_ de _TOTAL_ Usuarios',
                infoEmpty: 'Mostrando 0 a 0 de 0 Usuarios',
                infoFiltered: '(filtrado de _MAX_ total Usuarios)',
                lengthMenu: 'Mostrar _MENU_ Usuarios',
                search: 'Buscar:',
                zeroRecords: 'No se encontraron resultados',
                paginate: {
                    first: 'Primero',
                    last: 'Último',
                    next: 'Siguiente',
                    previous: 'Anterior'
                }
            }
        });
    });
</script>
@stop
