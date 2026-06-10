@extends('adminlte::page')

@section('plugins.Datatables', true)
@section('plugins.DatatablesPlugins', true)

@section('content_header')
    <h1><b>Listado de Horarios de Grupos</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title">Horarios de Grupos Registrados</h3>
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
                                <th style="text-align: center">Nombre del Grupo</th>
                                <th style="text-align: center">Modalidad</th>
                                <th style="text-align: center">Turno</th>
                                <th style="text-align: center">Gestión</th>
                                <th style="text-align: center">Acción</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php $contador = 1; @endphp
                            @foreach($grupos as $grupo)
                                <tr>
                                    <td style="text-align: center">{{ $contador++ }}</td>
                                    <td style="text-align: center">{{ $grupo->nombre }}</td>
                                    <td style="text-align: center">{{ $grupo->modalidad->nombre ?? '---' }}</td>
                                    <td style="text-align: center">{{ $grupo->turno->nombre ?? '---' }}</td>
                                    <td style="text-align: center">{{ $grupo->gestion->semestre }}-{{ $grupo->gestion->año }}</td>
                                    <td style="text-align: center">
                                        <a href="{{ route('admin.grupos.showhorario', $grupo->id) }}" class="btn btn-info btn-sm">
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

@section('js')
    <script>
        $(function () {
            $("#example1").DataTable({
                pageLength: 5,
                language: {
                    emptyTable: "No hay información",
                    info: "Mostrando _START_ a _END_ de _TOTAL_ Horarios",
                    infoEmpty: "Mostrando 0 a 0 de 0 Horarios",
                    infoFiltered: "(Filtrado de _MAX_ total Horarios)",
                    lengthMenu: "Mostrar _MENU_ Horarios",
                    loadingRecords: "Cargando...",
                    processing: "Procesando...",
                    search: "Buscador:",
                    zeroRecords: "Sin resultados encontrados",
                    paginate: { first: "Primero", last: "Último", next: "Siguiente", previous: "Anterior" }
                },
            }).buttons().container().appendTo('#example1_wrapper .row:eq(0)');
        });
    </script>
@stop
