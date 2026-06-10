@extends('adminlte::page')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
    <h1><b>Listado de Grupos</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">             
                <div class="card-header">
                    <h3 class="card-title">Grupos Registradas</h3>
                    <div class="card-tools">

                        {{-- Botón para ver horarios de grupos --}}
                        <a href="{{ route('admin.grupos.horariosgrupos') }}" class="btn btn-primary">Ver Horarios de Grupos</a>
                    </div>
                </div>

                <div class="card-body">
                    @if(!empty($activeGestionMessage))
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle mr-2"></i>
                            {{ $activeGestionMessage }}
                        </div>
                    @endif

                    <table id="example1" class="table table-bordered table-hover table-striped table-sm">
                        <thead>
                            <tr>
                                <th style="text-align: center">Nro</th>        
                                <th style="text-align: center">Nombre</th>   
                                <th style="text-align: center">Cupos</th>        
                                <th style="text-align: center">Gestión</th>         
                                <th style="text-align: center">Modalidad</th>     
                                <th style="text-align: center">Turno</th>     
                                <th style="text-align: center">Aula</th>        
                                <th style="text-align: center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = 1; @endphp
                            @foreach($grupos as $grupo)
                                <tr>
                                    <td style="text-align: center">{{ $contador++ }}</td>
                                    <td style="text-align: center">{{ $grupo->nombre }}</td>
                                    <td style="text-align: center">{{ $grupo->cupos }}</td>
                                    <td style="text-align: center">{{ $grupo->gestion->semestre }}-{{ $grupo->gestion->año }}</td>
                                    <td style="text-align: center">{{ $grupo->modalidad->nombre ?? '---' }}</td>
                                    <td style="text-align: center">{{ $grupo->turno->nombre ?? '---' }}</td>
                                    <td style="text-align: center">{{ $grupo->aula->nro_aula ?? '---' }}</td>
                                    <td style="text-align: center">
                                        <div class="btn-group" role="group">
                                            {{-- Botón Show --}}
                                            <a href="{{ url('/admin/grupos/'.$grupo->id) }}" class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                            {{-- Botón Editar --}}
                                            <a href="{{ url('/admin/grupos/'.$grupo->id.'/edit') }}" class="btn btn-success btn-sm"><i class="fas fa-edit"></i></a>
                                            {{-- Botón Eliminar --}}
                                            <form action="{{ url('/admin/grupos/'.$grupo->id) }}" method="POST" id="miformulario{{ $grupo->id }}" style="display:inline;">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="confirmarEliminacion(event, {{ $grupo->id }})" class="btn btn-danger btn-sm">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    {{-- Bloque de resumen de grupos por modalidad --}}
                    <div class="card mt-4">
                        <div class="card-header bg-secondary">
                            <h5 class="mb-0">Cantidad de grupos creados</h5>
                        </div>
                        <div class="card-body">
                            @php
                                $virtual = $groupCountsByModalidad->firstWhere('nombre', 'Virtual')->total ?? 0;
                                $presencial = $groupCountsByModalidad->firstWhere('nombre', 'Presencial')->total ?? 0;
                            @endphp
                            <p><b>Modalidad Virtual:</b> {{ $virtual }}</p>
                            <p><b>Modalidad Presencial:</b> {{ $presencial }}</p>
                        </div>
                    </div>
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
                title: '¿Desea eliminar este grupo?',
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
                pageLength: 5,
                language: {
                    emptyTable: "No hay información",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ Grupos",
                    infoEmpty: "Mostrando 0 a 0 de 0 Grupos",
                    infoFiltered: "(Filtrado de _MAX_ total Grupos)",
                    lengthMenu: "Mostrar _MENU_ Grupos",
                    loadingRecords: "Cargando...",
                    processing: "Procesando...",
                    search: "Buscador:",
                    zeroRecords: "Sin resultados encontrados",
                    paginate: {
                        first: "Primero",
                        last: "Último",
                        next: "Siguiente",
                        previous: "Anterior"
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
