@extends('adminlte::page')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
    <h1><b>Listado de Exámenes</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Exámenes Registrados</h3>
                    <div class="card-tools">
                        @if($gestionActiva)
                            <a href="{{ url('admin/examenes/create') }}" class="btn btn-primary">Nuevo Examen</a>
                        @else
                            <button class="btn btn-secondary" disabled>Nuevo Examen</button>
                        @endif
                    </div>
                </div>
                <div class="card-body">

                    @if(!$gestionActiva)
                        <div class="alert alert-danger" role="alert">
                            <i class="fas fa-exclamation-triangle"></i>
                            No hay gestiones activas. No se pueden registrar exámenes hasta que exista una gestión activa.
                        </div>
                    @endif

                    <table id="example1" class="table table-bordered table-hover table-striped table-sm">
                        <thead>
                            <tr>
                                <th style="text-align: center">Nro</th>        
                                <th style="text-align: center">Nro Examen</th>   
                                <th style="text-align: center">Fecha</th>            
                                <th style="text-align: center">Ponderación</th>            
                                <th style="text-align: center">Gestión</th>            
                                <th style="text-align: center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = 1; @endphp
                            @foreach($examenes as $examen)
                                <tr>
                                    <td style="text-align: center">{{ $contador++ }}</td>
                                    <td style="text-align: center">{{ $examen->nro_examen }}</td>
                                    <td style="text-align: center">{{ $examen->fecha }}</td>
                                    <td style="text-align: center">{{ $examen->ponderacion }}</td>
                                    <td style="text-align: center">
                                        {{ $examen->gestion->semestre ?? '' }} - {{ $examen->gestion->año ?? '' }}
                                    </td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group">

                                            {{--Boton de Editar --}}
                                            <a href="{{ url('/admin/examenes/'.$examen->id.'/edit') }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                            
                                            {{-- Botón de Eliminar --}}
                                            <form action="{{ url('/admin/examenes/'.$examen->id) }}" method="POST" id="miformulario{{ $examen->id }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="confirmarEliminacion(event, {{ $examen->id }})" class="btn btn-danger btn-sm">
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
            event.preventDefault();
            Swal.fire({
                title: '¿Desea eliminar este examen?',
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

        // Configuración de DataTables
        $(function () {
            $("#example1").DataTable({
                "pageLength": 5,
                "language": {
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Exámenes",
                    "infoEmpty": "Mostrando 0 a 0 de 0 Exámenes",
                    "infoFiltered": "(Filtrado de _MAX_ total Exámenes)",
                    "lengthMenu": "Mostrar _MENU_ Exámenes",
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

