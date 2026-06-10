@extends('adminlte::page')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
    <h1><b>Listado de Inscripciones</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Inscripciones Registradas</h3>
                    <div class="card-tools">
                        {{-- Botón para inscribir postulante --}}
                        <a href="{{ route('admin.inscripciones.create') }}" class="btn btn-primary">
                            Inscribir Postulante
                        </a>
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
                                <th style="text-align: center">Estado</th>
                                <th style="text-align: center">Fecha Inscripción</th>
                                <th style="text-align: center">Modalidad</th>
                                <th style="text-align: center">Turno</th>
                                <th style="text-align: center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = 1; @endphp
                            @foreach($inscripciones as $inscripcion)
                                <tr>
                                    <td style="text-align: center">{{ $contador++ }}</td>
                                    <td style="text-align: center">{{ $inscripcion->postulante->codigo }}</td>
                                    <td style="text-align: center">{{ $inscripcion->postulante->nombre }}</td>
                                    <td style="text-align: center">{{ $inscripcion->postulante->apellidos }}</td>
                                    <td style="text-align: center">
                                        @if($inscripcion->estado == 'INSCRITO')
                                            <span class="badge badge-success">Inscrito</span>
                                        @else
                                            <span class="badge badge-warning">Pendiente</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center">{{ $inscripcion->fecha_insc }}</td>
                                    <td style="text-align: center">{{ $inscripcion->modalidad->nombre }}</td>
                                    <td style="text-align: center">{{ $inscripcion->turno->nombre }}</td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group">
                                            <a href="{{ route('admin.inscripciones.show', $inscripcion) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                            <a href="{{ route('admin.inscripciones.edit', $inscripcion) }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                            <form action="{{ route('admin.inscripciones.destroy', $inscripcion) }}" method="POST" id="formInscripcion{{ $inscripcion->id }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="confirmarEliminacion(event, '{{ $inscripcion->id }}')" class="btn btn-danger btn-sm">
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
                title: '¿Desea eliminar esta inscripción?',
                icon: 'question',
                showDenyButton: true,
                confirmButtonText: 'Eliminar',
                confirmButtonColor: '#a5161d',
                denyButtonText: 'Cancelar',
                denyButtonColor: '#6c757d',
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formInscripcion' + id).submit();
                }
            });
        }

        $(function () {
            $("#example1").DataTable({
                "pageLength": 5,
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Inscripciones",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Inscripciones",
                    "infoFiltered": "(Filtrado de _MAX_ total Inscripciones)",
                    "lengthMenu": "Mostrar _MENU_ Inscripciones",
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
