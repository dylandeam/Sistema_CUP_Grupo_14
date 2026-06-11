<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Reporte {{ $label }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body { 
            font-family: DejaVu Sans, Arial, sans-serif; 
            font-size: 11px;
            line-height: 1.4;
            color: #333;
        }
        .container {
            padding: 20px;
        }
        .header { 
            margin-bottom: 25px;
            border-bottom: 3px solid #007bff;
            padding-bottom: 15px;
        }
        .header h2 {
            font-size: 18px;
            color: #007bff;
            margin-bottom: 8px;
        }
        .header-info {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #666;
        }
        .filters { 
            margin-bottom: 15px;
            padding: 10px;
            background: #f9f9f9;
            border-left: 4px solid #007bff;
        }
        .filters-title {
            font-weight: bold;
            font-size: 11px;
            margin-bottom: 5px;
            color: #007bff;
        }
        .filters span { 
            display: inline-block; 
            margin-right: 15px; 
            font-size: 10px;
            color: #555;
        }
        table { 
            width: 100%; 
            border-collapse: collapse; 
            margin-top: 10px;
        }
        thead {
            background: #007bff;
            color: white;
        }
        th, td { 
            border: 1px solid #ddd; 
            padding: 8px; 
            text-align: left;
        }
        th {
            background: #007bff;
            color: white;
            font-weight: bold;
            font-size: 11px;
        }
        tbody tr:nth-child(even) {
            background: #f9f9f9;
        }
        tbody tr:hover {
            background: #f0f0f0;
        }
        td {
            font-size: 10px;
        }
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ddd;
            font-size: 9px;
            color: #999;
            text-align: center;
        }
        .summary {
            margin-top: 15px;
            padding: 10px;
            background: #e8f4f8;
            border-left: 4px solid #007bff;
            font-size: 10px;
        }
        .summary-item {
            display: inline-block;
            margin-right: 20px;
        }
        .summary-label {
            font-weight: bold;
            color: #007bff;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>{{ $label }}</h2>
            <div class="header-info">
                <span><strong>Fecha de Generación:</strong> {{ now('America/La_Paz')->format('d/m/Y H:i') }}</span>
                <span><strong>Total de Registros:</strong> {{ count($rows) }}</span>
            </div>
        </div>

        @if (!empty($filters))
            <div class="filters">
                <div class="filters-title">📋 Filtros Aplicados:</div>
                @if (is_array($filters))
                    @foreach ($filters as $key => $value)
                        <span><strong>{{ ucfirst(str_replace('_', ' ', $key))}}:</strong> {{ $value }}</span>
                    @endforeach
                @else
                    <span>{{ $filters }}</span>
                @endif
            </div>
        @endif

        <table>
            <thead>
                <tr>
                    <th style="width: 30px;">#</th>
                    @foreach ($fields as $label)
                        <th>{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @forelse ($rows as $index => $row)
                    <tr>
                        <td style="text-align: center; font-weight: bold;">{{ $index + 1 }}</td>
                        @foreach ($fieldKeys as $fieldKey)
                            <td>{{ $row[$fieldKey] ?? '-' }}</td>
                        @endforeach
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ count($fields) + 1 }}" style="text-align: center; color: #999;">
                            No hay datos para mostrar
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <div class="summary">
            <div class="summary-item">
                <span class="summary-label">Total de Filas:</span> {{ count($rows) }}
            </div>
            <div class="summary-item">
                <span class="summary-label">Campos Mostrados:</span> {{ count($fields) }}
            </div>
        </div>

        <div class="footer">
            <p>Reporte generado automáticamente por el Sistema de Admisión Estudiantil</p>
            <p>© {{ now()->year }} - Todos los derechos reservados</p>
        </div>
    </div>
</body>
</html>
