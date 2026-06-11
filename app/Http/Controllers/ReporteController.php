<?php

namespace App\Http\Controllers;

use App\Models\Postulante;
use App\Models\Docente;
use App\Models\Administrativo;
use App\Models\Inscripcion;
use App\Models\Resultado;
use App\Models\Grupo;
use App\Models\Materia;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\View;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ReporteController extends Controller
{
    private $reportDefinitions = [];

    public function __construct()
    {
        $this->getReportDefinitions();
    }

    /**
     * Obtener definición de todos los reportes (solo genéricos)
     */
    private function getReportDefinitions()
    {
        $this->reportDefinitions = [
            'postulantes' => [
                'label' => 'Postulantes',
                'model' => Postulante::class,
                'fields' => [
                    'codigo' => 'Código',
                    'nombre' => 'Nombre',
                    'apellidos' => 'Apellidos',
                    'ci' => 'CI',
                    'email' => ['relation' => 'user', 'field' => 'email'],
                    'telefono' => 'Teléfono',
                    'fecha_nacimiento' => 'Fecha Nacimiento',
                    'sexo' => 'Sexo',
                    'direccion' => 'Dirección',
                    'colegio' => 'Colegio',
                ]
            ],
            'docentes' => [
                'label' => 'Docentes',
                'model' => Docente::class,
                'fields' => [
                    'codigo' => 'Código',
                    'nombre' => 'Nombre',
                    'apellido' => 'Apellido',
                    'ci' => 'CI',
                    'email' => ['relation' => 'user', 'field' => 'email'],
                    'especialidad' => 'Especialidad',
                    'telefono' => 'Teléfono',
                ]
            ],
            'administrativos' => [
                'label' => 'Administrativos',
                'model' => Administrativo::class,
                'fields' => [
                    'codigo' => 'Código',
                    'nombre' => 'Nombre',
                    'apellido' => 'Apellido',
                    'ci' => 'CI',
                    'email' => ['relation' => 'user', 'field' => 'email'],
                    'cargo' => 'Cargo',
                    'telefono' => 'Teléfono',
                ]
            ],
            'grupos' => [
                'label' => 'Grupos',
                'model' => Grupo::class,
                'fields' => [
                    'id' => 'ID',
                    'nombre' => 'Nombre',
                    'capacidad' => 'Capacidad',
                ]
            ],
            'materias' => [
                'label' => 'Materias',
                'model' => Materia::class,
                'fields' => [
                    'id' => 'ID',
                    'nombre' => 'Nombre',
                    'codigo' => 'Código',
                    'sigla' => 'Sigla',
                ]
            ],
            'docente_materia' => [
                'label' => 'Docentes por Materia',
                'model' => Docente::class,
                'fields' => [
                    'codigo' => 'Código Docente',
                    'nombre' => 'Nombre',
                    'apellido' => 'Apellido',
                    'ci' => 'CI',
                    'email' => ['relation' => 'user', 'field' => 'email'],
                    'materias' => ['custom' => 'materias_list'],
                ]
            ],
        ];
    }

    /**
     * Mostrar vista principal de reportes
     */
    public function index()
    {
        $reportDefinitions = $this->reportDefinitions;
        $selectedTable = request()->get('report');
        $selectedFields = request()->get('fields', []);
        $search = request()->get('search', '');
        $results = collect();
        $availableFields = [];

        if (!empty($selectedTable) && isset($reportDefinitions[$selectedTable])) {
            $definition = $reportDefinitions[$selectedTable];
            $availableFields = $definition['fields'];

            // Si se seleccionan campos, generar reporte
            if (!empty($selectedFields)) {
                $results = $this->queryTable($selectedTable, $selectedFields, $search);
            }
        }

        return view('admin.reportes.index', [
            'reportDefinitions' => $reportDefinitions,
            'selectedTable' => $selectedTable,
            'selectedFields' => $selectedFields,
            'availableFields' => $availableFields,
            'results' => $results,
            'search' => $search,
        ]);
    }

    /**
     * Consultar tabla seleccionada con campos específicos
     */
    private function queryTable($table, $fields, $search = '', $paginate = true)
    {
        if (!isset($this->reportDefinitions[$table])) {
            return collect();
        }

        $definition = $this->reportDefinitions[$table];
        $modelClass = $definition['model'];
        $query = $modelClass::query();

        // Cargar relaciones si son necesarias
        $relationsToLoad = [];
        foreach ($fields as $field) {
            $fieldConfig = $definition['fields'][$field] ?? null;
            if (is_array($fieldConfig)) {
                if (isset($fieldConfig['relation'])) {
                    $relationsToLoad[] = $fieldConfig['relation'];
                } elseif (isset($fieldConfig['custom']) && $fieldConfig['custom'] === 'materias_list') {
                    // Para campos custom de materias, cargar la relación
                    $relationsToLoad[] = 'materias';
                }
            }
        }

        if (!empty($relationsToLoad)) {
            $query->with(array_unique($relationsToLoad));
        }

        // Aplicar búsqueda
        if (!empty($search)) {
            $query->where(function ($q) use ($search, $fields, $definition) {
                foreach ($fields as $field) {
                    if ($field !== 'created_at' && $field !== 'updated_at' && $field !== 'materias') {
                        $q->orWhere($field, 'like', "%{$search}%");
                    }
                }
            });
        }

        if ($paginate) {
            return $query->paginate(50);
        }

        return $query->get();
    }

    /**
     * Exportar reporte
     */
    public function export()
    {
        $table = request()->get('report');
        $fields = request()->get('fields', []);
        $search = request()->get('search', '');
        $format = request()->get('format', 'excel');

        // Validar que fields sea un array
        if (!is_array($fields)) {
            $fields = [];
        }

        if (empty($table) || empty($fields) || !isset($this->reportDefinitions[$table])) {
            return redirect()->back()->with('error', 'Selecciona tabla y campos válidos');
        }

        $definition = $this->reportDefinitions[$table];
        $results = $this->queryTable($table, $fields, $search, false);

        if (empty($results)) {
            return redirect()->back()->with('warning', 'No hay datos para exportar');
        }

        if ($format === 'pdf') {
            return $this->exportToPdf($definition, $fields, $results, $search);
        }

        return $this->exportToExcel($definition, $fields, $results);
    }

    /**
     * Exportar a Excel
     */
    private function exportToExcel($definition, $fields, $results)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();

        // Encabezados
        $colIndex = 1;
        $headers = [];
        foreach ($fields as $field) {
            $label = $definition['fields'][$field] ?? ucfirst(str_replace('_', ' ', $field));
            if (is_array($label)) {
                $label = ucfirst(str_replace('_', ' ', $field));
            }
            $headers[$field] = $label;
            $sheet->setCellValueByColumnAndRow($colIndex, 1, $label);
            $sheet->getStyleByColumnAndRow($colIndex, 1)->getFont()->setBold(true);
            $sheet->getColumnDimensionByColumn($colIndex)->setAutoSize(true);
            $colIndex++;
        }

        // Datos
        $rowIndex = 2;
        foreach ($results as $row) {
            $colIndex = 1;
            foreach ($fields as $field) {
                $value = $this->getFieldValue($row, $field, $definition);
                $sheet->setCellValueByColumnAndRow($colIndex, $rowIndex, $value);
                $colIndex++;
            }
            $rowIndex++;
        }

        // Descargar
        $writer = new Xlsx($spreadsheet);
        $filename = $definition['label'] . '_' . date('Ymdhis') . '.xlsx';
        
        return response()->stream(
            function () use ($writer) {
                $writer->save('php://output');
            },
            200,
            [
                'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
                'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            ]
        );
    }

    /**
     * Exportar a PDF (HTML para imprimir)
     */
    private function exportToPdf($definition, $fields, $results, $search)
    {
        $headers = [];
        foreach ($fields as $field) {
            $label = $definition['fields'][$field] ?? ucfirst(str_replace('_', ' ', $field));
            if (is_array($label)) {
                $label = ucfirst(str_replace('_', ' ', $field));
            }
            $headers[$field] = $label;
        }

        $rows = [];
        foreach ($results as $result) {
            $row = [];
            foreach ($fields as $field) {
                $row[$field] = $this->getFieldValue($result, $field, $definition);
            }
            $rows[] = $row;
        }

        $data = [
            'label' => $definition['label'],
            'fields' => $headers,
            'fieldKeys' => $fields,
            'rows' => $rows,
            'filters' => !empty($search) ? "Búsqueda: {$search}" : '',
        ];

        $html = View::make('admin.reportes.pdf', $data)->render();
        $filename = $definition['label'] . '_' . date('Ymdhis') . '.html';
        
        return response($html, 200)
            ->header('Content-Type', 'text/html; charset=utf-8')
            ->header('Content-Disposition', "attachment; filename=\"{$filename}\"");
    }

    /**
     * Obtener valor de un campo considerando relaciones y custom fields
     */
    private function getFieldValue($row, $field, $definition)
    {
        $fieldConfig = $definition['fields'][$field] ?? null;

        // Manejo de campos custom
        if (is_array($fieldConfig) && isset($fieldConfig['custom'])) {
            if ($fieldConfig['custom'] === 'materias_list') {
                $materias = $row->materias()->pluck('nombre')->toArray();
                return !empty($materias) ? implode(', ', $materias) : '-';
            }
            return '-';
        }

        // Manejo de relaciones
        if (is_array($fieldConfig) && isset($fieldConfig['relation'])) {
            $relation = $fieldConfig['relation'];
            $subField = $fieldConfig['field'];
            $value = $row->$relation?->$subField ?? '-';
        } else {
            $value = $row->$field ?? '-';
        }

        // Formateo inteligente (NO formattear CI ni Código)
        if ($value === '-' || $value === null) {
            return '-';
        }

        // Si es CI o Código, devolver como string sin formateo
        if ($field === 'ci' || $field === 'codigo') {
            return (string)$value;
        }

        // Formateo para valores booleanos
        if (is_bool($value)) {
            return $value ? '✓' : '✗';
        }

        // Formateo para fechas
        if ($value instanceof \DateTime || $value instanceof \Carbon\Carbon) {
            return $value->format('d/m/Y H:i');
        }

        return $value;
    }
}
