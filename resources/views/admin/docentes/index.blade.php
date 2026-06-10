@extends('adminlte::page')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
    <h1><b>Listado de Docentes</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Docentes Registrados</h3>
                    <div class="card-tools">

                        {{-- Botón para importar docentes desde Excel/CSV --}}
                        <a href="{{ url('/admin/docentes/import/form') }}" class="btn btn-success">Importar Excel/CSV</a>

                        {{-- Botón para contratar docente --}}
                        <a href="{{ url('/admin/docentes/create') }}" class="btn btn-primary">Contratar Docente</a>
                    </div>
                </div>
                <div class="card-body">
                    <table id="example1" class="table table-bordered table-hover table-striped table-sm">
                        <thead>
                            <tr>
                                <th style="text-align: center">Nro</th>
                                <th style="text-align: center">Código</th>
                                <th style="text-align: center">Nombre</th>
                                <th style="text-align: center">Apellido</th>
                                <th style="text-align: center">CI</th>
                                <th style="text-align: center">Fecha Nacimiento</th>
                                <th style="text-align: center; width:150px;">Email</th>
                                <th style="text-align: center">Teléfono</th>
                                <th style="text-align: center">Estado</th>
                                <th style="text-align: center">Materia</th>
                                <th style="text-align: center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = 1; @endphp
                            @foreach($docentes as $docente)
                                <tr>
                                    <td style="text-align: center">{{ $contador++ }}</td>
                                    <td style="text-align: center">{{ $docente->codigo }}</td>
                                    <td style="text-align: center">{{ $docente->nombre }}</td>
                                    <td style="text-align: center">{{ $docente->apellido }}</td>
                                    <td style="text-align: center">{{ $docente->ci }}</td>
                                    <td style="text-align: center">{{ $docente->fecha_nacimiento }}</td>
                                    <td class="email-cell">{{ $docente->user->email }}</td>
                                    <td style="text-align: center">{{ $docente->telefono }}</td>
                                    <td style="text-align: center">
                                        @php $estadoAsignacion = $docente->estado_asignacion ?? 'baja'; @endphp
                                        @if($estadoAsignacion == 'activo')
                                            <span class="badge badge-success">Activo</span>
                                        @else
                                            <span class="badge badge-danger">Baja</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                        @if($docente->materias->isNotEmpty())
                                            {{ $docente->materias->pluck('nombre')->join(', ') }}
                                        @else
                                            <span class="text-muted">Sin asignación</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.docentes.show', $docente) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('admin.docentes.edit', $docente) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.docentes.destroy', $docente) }}" method="POST" id="miformulario{{ $docente->codigo }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="confirmarEliminacion(event, '{{ $docente->codigo }}')" class="btn btn-danger btn-sm">
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

        /* Ajuste específico para la columna Email */
        .email-cell {
            max-width: 150px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
            text-align: center;
        }
    </style>
@stop

@section('js')
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap4.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.colVis.min.js"></script>

    <script>
        function confirmarEliminacion(event, id) {
            event.preventDefault();
            Swal.fire({
                title: '¿Desea eliminar este registro?',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: 'Eliminar',
                confirmButtonColor: '#a5161d',
                denyButtonText: 'Cancelar',
                denyButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('miformulario' + id).submit();
                }
            });
        }

        $(function () {
            $("#example1").DataTable({
                "pageLength": 5,
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Docentes",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Docentes",
                    "infoFiltered": "(Filtrado de _MAX_ total Docentes)",
                    "lengthMenu": "Mostrar _MENU_ Docentes",
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
