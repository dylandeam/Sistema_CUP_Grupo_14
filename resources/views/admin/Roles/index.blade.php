@extends('adminlte::page')

@section('plugins.Datatables', true)

@section('content_header')
    <h1><b>Listado de Roles</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header bg-white">
                <h3 class="card-title text-dark">Roles Registrados</h3>
                <div class="card-tools">
                    <a href="{{ route('admin.roles.create') }}" class="btn btn-primary btn-sm">Crear Nuevo</a>
                </div>
            </div>

            <div class="card-body">
                <table id="example1" class="table table-bordered table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th style="text-align:center;">Nro</th>
                            <th style="text-align:center;">Nombre del Rol</th>
                            <th style="text-align:center;">Acción</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $contador = 1; @endphp
                        @foreach ($roles as $role)
                            <tr>
                                <td style="text-align:center;">{{ $contador++ }}</td>
                                <td>{{ $role->name }}</td>
                                <td style="text-align:center;">
                                    <div class="btn-group">
                                       
                                         {{-- Botón Permisos --}}
                                        <a href="{{ route('admin.roles.permisos', $role->id) }}" class="btn btn-warning btn-sm"><i class="fas fa-check"></i></a>


                                        {{-- Botón Editar --}}
                                        <a href="{{ url('/admin/roles/'.$role->id.'/edit') }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                        
                                        {{-- Botón Eliminar con confirmación --}}
                                        <form action="{{ route('admin.roles.destroy', $role->id) }}"
                                              method="POST"
                                              id="form-eliminar-{{ $role->id }}"
                                              style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button"
                                                    class="btn btn-danger btn-sm"
                                                    onclick="confirmarEliminacion(event, {{ $role->id }})">
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

@section('css')

    <!-- CSS de DataTables Buttons -->
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap4.min.css">

    <style>
        #example1_wrapper .dt-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        #example1_wrapper .btn {
            border-radius: 4px;
            padding: 5px 15px;
            font-size: 14px;
            color: #fff;
        }
        .btn-danger { background-color: #dc3545; border: none; }
        .btn-success { background-color: #28a745; border: none; }
        .btn-info { background-color: #17a2b8; border: none; }
        .btn-warning { background-color: #ffc107; color: #212529; border: none; }
        .btn-orange { background-color: #e67e22; color: #fff; border: none; }
    </style>
@stop

@section('js')
    
    {{-- SweetAlert2 --}}
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    {{-- DataTables Buttons --}}
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

    <script>
        // Script único para confirmación de eliminación
        function confirmarEliminacion(event, id) {
            if (event) {
                event.preventDefault();
            }
            Swal.fire({
                title: '¿Desea eliminar este Rol?',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: 'Eliminar',
                confirmButtonColor: '#a5161d',
                denyButtonText: 'Cancelar',
                denyButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-eliminar-' + id).submit();
                }
            });
        }

        // Configuración de DataTables
        $(function () {
            $("#example1").DataTable({
                "pageLength": 5,
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Roles",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Roles",
                    "infoFiltered": "(Filtrado de _MAX_ total Roles)",
                    "lengthMenu": "Mostrar _MENU_ Roles",
                    "loadingRecords": "Cargando...",
                    "processing": "Procesando...",
                    "search": "Buscador:",
                    "zeroRecords": "Sin resultados encontrados",
                    "paginate": {
                        "first": "Primero",
                        "last": "Último",
                        "next": "Siguiente",
                        "previous": "Anterior"
                    }
                }, 
                responsive: true,
                lengthChange: true,
                autoWidth: false,
                buttons: [
                    { text: '<i class="fas fa-copy"></i> COPIAR', extend: 'copy', className: 'btn btn-orange' },
                    { text: '<i class="fas fa-file-pdf"></i> PDF', extend: 'pdf', className: 'btn btn-danger' },
                    { text: '<i class="fas fa-file-excel"></i> EXCEL', extend: 'excel', className: 'btn btn-success' },
                    { text: '<i class="fas fa-file-csv"></i> CSV', extend: 'csv', className: 'btn btn-info' },
                    { text: '<i class="fas fa-print"></i> Imprimir', extend: 'print', className: 'btn btn-warning' }
                ]
            }).buttons().container().appendTo('#example1_wrapper .row:eq(0)');
        });
    </script>
@stop

