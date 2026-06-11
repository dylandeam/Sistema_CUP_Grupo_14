@extends('adminlte::page')

@section('content_header')
    <h1><b>Reportes</b></h1>
    <hr>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-outline card-primary">
                <div class="card-header">
                    <h3 class="card-title">Generador de Reportes - Simple</h3>
                </div>
                <div class="card-body">
                    <form id="reportForm" method="GET" action="{{ route('admin.reportes.index') }}">
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="report"><b>Selecciona tabla</b> <span class="text-danger">(*)</span></label>
                                    <select name="report" id="report" class="form-control form-control-sm">
                                        <option value="">-- Seleccione --</option>
                                        @foreach ($reportDefinitions as $key => $definition)
                                            <option value="{{ $key }}" {{ $selectedTable === $key ? 'selected' : '' }}>
                                                {{ $definition['label'] }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        @if (!empty($availableFields))
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label><b>Campos a incluir</b></label>
                                    <div class="row" id="checkboxesArea">
                                        @foreach ($availableFields as $field => $label)
                                            <div class="col-md-3 mb-2">
                                                <div class="form-check">
                                                    <input class="form-check-input field-checkbox" type="checkbox" name="fields[]" value="{{ $field }}"
                                                        id="field_{{ $field }}" {{ in_array($field, $selectedFields) ? 'checked' : '' }}>
                                                    <label class="form-check-label" for="field_{{ $field }}">
                                                        {{ is_array($label) ? ucfirst(str_replace('_', ' ', $field)) : $label }}
                                                    </label>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="search"><b>Buscar</b> (opcional - busca en todos los campos)</label>
                                        <input type="text" name="search" id="search" class="form-control form-control-sm"
                                            placeholder="Escribe para buscar..." value="{{ $search }}">
                                        <small class="text-muted">Escribe algo y luego presiona Buscar.</small>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label>&nbsp;</label>
                                        <button type="submit" class="btn btn-primary btn-sm form-control">
                                            <i class="fas fa-search"></i> Buscar
                                        </button>
                                    </div>
                                </div>
                            </div>
                        @endif
                    </form>

                    @if (!empty($selectedTable) && empty($availableFields))
                        <div class="alert alert-warning">
                            Selecciona la tabla y presiona Buscar para ver los campos.
                        </div>
                    @endif

                    @if ($results->count() > 0)
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <div class="alert alert-info">
                                    <b>Resultados: {{ $results->count() }} registros encontrados</b>
                                </div>
                                <div class="table-responsive">
                                    <table class="table table-sm table-bordered table-hover">
                                        <thead class="thead-light">
                                            <tr>
                                                <th style="width: 50px">#</th>
                                                @foreach ($selectedFields as $field)
                                                    <th>
                                                        @php
                                                            $fieldLabel = $availableFields[$field] ?? ucfirst(str_replace('_', ' ', $field));
                                                            if (is_array($fieldLabel)) {
                                                                $fieldLabel = ucfirst(str_replace('_', ' ', $field));
                                                            }
                                                        @endphp
                                                        {{ $fieldLabel }}
                                                    </th>
                                                @endforeach
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($results as $index => $result)
                                                <tr>
                                                    <td>{{ $results->firstItem() + $index }}</td>
                                                    @foreach ($selectedFields as $field)
                                                        <td>
                                                            @php
                                                                $fieldConfig = $availableFields[$field] ?? null;
                                                                
                                                                // Manejar campos personalizados (custom)
                                                                if (is_array($fieldConfig) && isset($fieldConfig['custom'])) {
                                                                    if ($fieldConfig['custom'] === 'materias_list') {
                                                                        $materias = $result->materias()->pluck('nombre')->toArray();
                                                                        $value = !empty($materias) ? implode(', ', $materias) : '-';
                                                                    } else {
                                                                        $value = '-';
                                                                    }
                                                                }
                                                                // Manejar relaciones
                                                                elseif (is_array($fieldConfig) && isset($fieldConfig['relation'])) {
                                                                    $value = $result->{$fieldConfig['relation']}->{$fieldConfig['field']} ?? '-';
                                                                } else {
                                                                    $value = $result->$field ?? '-';
                                                                }

                                                                // Formateo (NO formattear CI ni Código como números)
                                                                if (is_bool($value)) {
                                                                    $value = $value ? '✓' : '✗';
                                                                } elseif ($value instanceof \DateTime || $value instanceof \Carbon\Carbon) {
                                                                    $value = $value->format('d/m/Y H:i');
                                                                }
                                                            @endphp
                                                            {{ $value }}
                                                        </td>
                                                    @endforeach
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <!-- Paginación -->
                                @if ($results->hasPages())
                                    <div class="mt-3">
                                        {{ $results->render('pagination::bootstrap-4') }}
                                    </div>
                                @endif
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-12">
                                <form method="POST" id="exportForm" action="{{ route('admin.reportes.export') }}">
                                    @csrf
                                    <input type="hidden" name="report" value="{{ $selectedTable }}">
                                    <input type="hidden" name="search" id="export_search" value="{{ $search }}">
                                    <div id="fieldsExport"></div>
                                    <button type="submit" name="format" value="excel" class="btn btn-success btn-sm">
                                        <i class="fas fa-file-excel"></i> Descargar Excel
                                    </button>
                                    <button type="submit" name="format" value="pdf" class="btn btn-danger btn-sm">
                                        <i class="fas fa-file-pdf"></i> Descargar PDF
                                    </button>
                                </form>
                            </div>
                        </div>
                    @elseif ($selectedTable && !empty($availableFields))
                        <div class="alert alert-warning">
                            <b>Selecciona al menos un campo y presiona Buscar para ver resultados.</b>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- REPORTES ESPECIALIZADOS -->
        <div class="col-md-12 mt-4">
            <div class="card card-outline card-success">
                <div class="card-header">
                    <h3 class="card-title">📊 Reportes Especializados</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <!-- Carga Horaria -->
                        <div class="col-md-6">
                            <a href="{{ route('admin.reportes.carga_horaria') }}" class="btn btn-outline-success btn-block mb-2">
                                <i class="fas fa-calendar-week"></i> Carga Horaria de Docentes
                            </a>
                            <small class="text-muted d-block mb-3">
                                Ver horarios de docentes con vista diaria, semanal y general.
                            </small>
                        </div>
                        <!-- Futuro: otros reportes especializados -->
                        <div class="col-md-6">
                            <button class="btn btn-outline-secondary btn-block mb-2" disabled>
                                <i class="fas fa-chart-bar"></i> Más Reportes Próximamente
                            </button>
                            <small class="text-muted d-block mb-3">
                                Más opciones de reportes especializados en desarrollo.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        const reportForm = document.getElementById('reportForm');
        const exportForm = document.getElementById('exportForm');
        const reportSelect = document.getElementById('report');

        function updateExportFields() {
            const container = document.getElementById('fieldsExport');
            if (!container) return;
            container.innerHTML = '';
            const checkboxes = document.querySelectorAll('.field-checkbox:checked');
            checkboxes.forEach(checkbox => {
                const input = document.createElement('input');
                input.type = 'hidden';
                input.name = 'fields[]';
                input.value = checkbox.value;
                container.appendChild(input);
            });
            const search = document.getElementById('search');
            const exportSearch = document.getElementById('export_search');
            if (exportSearch && search) {
                exportSearch.value = search.value;
            }
        }

        if (reportSelect) {
            reportSelect.addEventListener('change', function() {
                reportForm.submit();
            });
        }

        document.querySelectorAll('.field-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', function() {
                reportForm.submit();
            });
        });

        if (exportForm) {
            exportForm.addEventListener('submit', function() {
                updateExportFields();
            });
        }
    </script>
@stop
