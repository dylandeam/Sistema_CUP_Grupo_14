@extends('adminlte::page')
 
@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)
 
@section('content_header')
    <h1><b>Historial de Pagos</b></h1>
    <hr>
@stop
 
@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Pagos Registrados</h3>
                    <div class="card-tools">
                        <button type="button" class="btn btn-warning"
                                onclick="window.location='{{ route('admin.inscripciones.create') }}'">
                            <i class="fab fa-paypal mr-1"></i> Generar pago con PayPal
                        </button>
                    </div>
                </div>
                <div class="card-body">
                    <table id="tablaPagos" class="table table-bordered table-hover table-striped table-sm">
                        <thead>
                            <tr>
                                <th style="text-align: center">Nro</th>
                                <th style="text-align: center">ID Inscripción</th>
                                <th style="text-align: center">Monto</th>
                                <th style="text-align: center">Fecha de Pago</th>
                                <th style="text-align: center">Estado</th>
                                <th style="text-align: center">Comprobante</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = 1; @endphp
                            @foreach($pagos as $pago)
                                <tr>
                                    <td style="text-align: center">{{ $contador++ }}</td>
                                    <td style="text-align: center">
                                        {{ $pago->inscripcion_id }}
                                    </td>
                                    <td style="text-align: center">
                                        {{ number_format($pago->monto, 2) }}
                                    </td>
                                    <td style="text-align: center">
                                        {{ $pago->fecha ? \Carbon\Carbon::parse($pago->fecha)->format('d/m/Y') : '-' }}
                                    </td>
                                    <td style="text-align: center">
                                        @if($pago->estado === 'CONFIRMADO')
                                            <span class="badge badge-success">Confirmado</span>
                                        @elseif($pago->estado === 'PENDIENTE')
                                            <span class="badge badge-warning text-dark">Pendiente</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $pago->estado }}</span>
                                        @endif
                                    </td>
                                    <td style="text-align: center">
                                        {{ $pago->comprobante ?? '-' }}
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
        #tablaPagos_wrapper .dt-buttons {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-bottom: 15px;
        }
        #tablaPagos_wrapper .btn {
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
            $("#tablaPagos").DataTable({
                pageLength: 5,
                language: {
                    emptyTable:    "No hay pagos registrados",
                    info:          "Mostrando _START_ a _END_ de _TOTAL_ Pagos",
                    infoEmpty:     "Mostrando 0 a 0 de 0 Pagos",
                    infoFiltered:  "(Filtrado de _MAX_ total Pagos)",
                    lengthMenu:    "Mostrar _MENU_ Pagos",
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
                lengthChange:  true,
                autoWidth:    false,
                buttons: [
                    { text: '<i class="fas fa-copy"></i> COPIAR',     extend: 'copy',  className: 'btn btn-orange'  },
                    { text: '<i class="fas fa-file-pdf"></i> PDF',     extend: 'pdf',   className: 'btn btn-danger'  },
                    { text: '<i class="fas fa-file-excel"></i> EXCEL', extend: 'excel', className: 'btn btn-success' },
                    { text: '<i class="fas fa-file-csv"></i> CSV',     extend: 'csv',   className: 'btn btn-info'    },
                    { text: '<i class="fas fa-print"></i> Imprimir',   extend: 'print', className: 'btn btn-warning' }
                ]
            }).buttons().container().appendTo('#tablaPagos_wrapper .row:eq(0)');
        });
    </script>
@stop