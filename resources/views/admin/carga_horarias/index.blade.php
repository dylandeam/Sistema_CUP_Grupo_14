@extends('adminlte::page')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
    <h1><b>Carga Horaria de Docentes</b></h1>
    <hr>
@stop

@section('content')
<div class="row">
    <div class="col-md-12">
        <div class="card card-outline card-primary">
            <div class="card-header">
                <h3 class="card-title">Docentes con Carga Horaria Asignada</h3>
            </div>
            <div class="card-body">
                @if(!empty($activeGestionMessage))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fas fa-exclamation-circle mr-2"></i>
                        <strong>⚠️ No se puede asignar carga horaria:</strong>
                        <p>{{ $activeGestionMessage }}</p>
                        @if(strpos($activeGestionMessage, 'docentes registrados') !== false)
                            <a href="{{ route('admin.docentes.create') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-plus"></i> Registrar Docentes
                            </a>
                        @elseif(strpos($activeGestionMessage, 'gestiones activas') !== false)
                            <a href="{{ route('admin.gestion.index') }}" class="btn btn-sm btn-primary mt-2">
                                <i class="fas fa-calendar"></i> Crear Gestión Activa
                            </a>
                        @endif
                        <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                @endif

                <table id="tablaCargaHoraria" class="table table-bordered table-hover table-striped table-sm">
                    <thead>
                        <tr>
                            <th style="text-align: center">Nro</th>
                            <th style="text-align: center">Código</th>
                            <th style="text-align: center">Nombre</th>
                            <th style="text-align: center">Apellido</th>
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
                                <td style="text-align: center">
                                    <a href="{{ route('admin.carga_horaria.show_docente', $docente->codigo) }}" class="btn btn-info btn-sm">
                                        <i class="fas fa-eye"></i> Ver
                                    </a>
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
    #tablaCargaHoraria_wrapper .dt-buttons {
        display: flex;
        justify-content: center;
        gap: 10px;
        margin-bottom: 15px;
    }
    #tablaCargaHoraria_wrapper .btn {
        color: #fff;
        border-radius: 4px;
        padding: 5px 15px;
        font-size: 14px;
    }
    .btn-danger  { background-color: #dc3545; border: none; }
    .btn-success { background-color: #28a745; border: none; }
    .btn-info    { background-color: #17a2b8; border: none; }
    .btn-warning { background-color: #ffc107; color: #212529; border: none; }
    .btn-orange  { background-color: #e67e22; color: #fff; border: none; }
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

@if(session('mensaje'))
<script>
    Swal.fire({
        icon: '{{ session("icono") }}',
        title: '{{ session("mensaje") }}',
        showConfirmButton: false,
        timer: 2500
    });
</script>
@endif

<script>
    $(function () {
        $("#tablaCargaHoraria").DataTable({
            pageLength: 5,
            language: {
                emptyTable:    "No hay registros de carga horaria",
                info:          "Mostrando _START_ a _END_ de _TOTAL_ registros",
                infoEmpty:     "Mostrando 0 a 0 de 0 registros",
                infoFiltered:  "(Filtrado de _MAX_ total registros)",
                lengthMenu:    "Mostrar _MENU_ registros",
                loadingRecords:"Cargando...",
                processing:    "Procesando...",
                search:        "Buscador:",
                zeroRecords:   "Sin resultados encontrados",
                paginate: {
                    first:    "Primero",
                    last:     "Último",
                    next:     "Siguiente",
                    previous: "Anterior"
                }
            },
            responsive:   true,
            lengthChange: true,
            autoWidth:    false,
            buttons: [
                { text: '<i class="fas fa-copy"></i> COPIAR',     extend: 'copy',  className: 'btn btn-orange'  },
                { text: '<i class="fas fa-file-pdf"></i> PDF',     extend: 'pdf',   className: 'btn btn-danger'  },
                { text: '<i class="fas fa-file-excel"></i> EXCEL', extend: 'excel', className: 'btn btn-success' },
                { text: '<i class="fas fa-file-csv"></i> CSV',     extend: 'csv',   className: 'btn btn-info'    },
                { text: '<i class="fas fa-print"></i> Imprimir',   extend: 'print', className: 'btn btn-warning' }
            ]
        }).buttons().container().appendTo('#tablaCargaHoraria_wrapper .row:eq(0)');
    });
</script>
@stop
