<?php

namespace App\Livewire\Traits;

use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Csv;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Component\HttpFoundation\StreamedResponse;

/**
 * Trait para exportación de datos a Excel/CSV/PDF.
 *
 * Proporciona funcionalidad de exportación reutilizable para cualquier CRUD.
 *
 * MODO DE USO:
 * ```php
 * class GestionarEquipos extends BaseCrudComponent
 * {
 *     use WithExport;
 *
 *     // Opcionalmente sobrescribir:
 *     protected function getExportColumns(): array
 *     {
 *         return ['id' => 'ID', 'nombre' => 'Nombre', 'created_at' => 'Fecha Creación'];
 *     }
 *
 *     protected function formatExportValue($item, string $column): mixed
 *     {
 *         if ($column === 'created_at') {
 *             return formatDate($item->created_at);
 *         }
 *         return $item->$column;
 *     }
 *
 *     // Para PDF:
 *     protected function getPdfOrientation(): string
 *     {
 *         return 'landscape'; // o 'portrait'
 *     }
 * }
 * ```
 *
 * En la vista:
 * ```blade
 * <button wire:click="exportExcel">Exportar Excel</button>
 * <button wire:click="exportCsv">Exportar CSV</button>
 * <button wire:click="exportPdf">Exportar PDF</button>
 * ```
 *
 * BENEFICIOS:
 * - Exportación en 1 minuto vs 30-45 minutos
 * - Formatos Excel, CSV y PDF
 * - Respeta filtros y búsqueda actuales
 * - Totalmente personalizable
 */
trait WithExport
{
    /**
     * Retorna las columnas a exportar.
     * Formato: ['campo_db' => 'Título en Excel']
     */
    protected function getExportColumns(): array
    {
        // Por defecto exporta ID y primer campo del modelo
        return [
            'id' => 'ID',
            'created_at' => 'Fecha Creación',
        ];
    }

    /**
     * Retorna el nombre del archivo de exportación.
     */
    protected function getExportFilename(string $extension): string
    {
        $modelClass = $this->getModelClass();
        $modelName = strtolower(class_basename($modelClass));
        $date = date('Y-m-d_H-i-s');

        return "{$modelName}s_{$date}.{$extension}";
    }

    /**
     * Formatea un valor antes de exportarlo.
     * Sobrescribe este método para personalizar el formato.
     *
     * @param  mixed  $item
     */
    protected function formatExportValue($item, string $column): mixed
    {
        $value = $item->$column;

        // Formatear fechas automáticamente
        if ($value instanceof \Carbon\Carbon) {
            return formatDateTime($value);
        }

        // Convertir booleanos a Sí/No
        if (is_bool($value)) {
            return $value ? 'Sí' : 'No';
        }

        return $value;
    }

    /**
     * Obtiene los datos a exportar.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    protected function getExportData()
    {
        $modelClass = $this->getModelClass();

        return $modelClass::query()
            ->filtered($this->search ?? '', $this->showingTrash ?? false, $this->sortField ?? 'id', $this->sortDirection->value ?? 'asc')
            ->get();
    }

    /**
     * Crea el spreadsheet con los datos.
     */
    protected function createSpreadsheet(): Spreadsheet
    {
        $spreadsheet = new Spreadsheet;
        $sheet = $spreadsheet->getActiveSheet();

        $columns = $this->getExportColumns();
        $data = $this->getExportData();

        // Headers
        $col = 1;
        foreach ($columns as $header) {
            $sheet->setCellValueByColumnAndRow($col, 1, $header);
            $col++;
        }

        // Estilo para headers
        $sheet->getStyle('A1:'.chr(64 + count($columns)).'1')->applyFromArray([
            'font' => ['bold' => true],
            'fill' => [
                'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E2E8F0'],
            ],
        ]);

        // Data
        $row = 2;
        foreach ($data as $item) {
            $col = 1;
            foreach (array_keys($columns) as $column) {
                $value = $this->formatExportValue($item, $column);
                $sheet->setCellValueByColumnAndRow($col, $row, $value);
                $col++;
            }
            $row++;
        }

        // Auto-ajustar columnas
        foreach (range(1, count($columns)) as $col) {
            $sheet->getColumnDimensionByColumn($col)->setAutoSize(true);
        }

        return $spreadsheet;
    }

    /**
     * Exporta los datos a Excel.
     */
    public function exportExcel(): StreamedResponse
    {
        $this->authorize('export', $this->getModelClass());

        $spreadsheet = $this->createSpreadsheet();
        $filename = $this->getExportFilename('xlsx');

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Xlsx($spreadsheet);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }

    /**
     * Exporta los datos a CSV.
     */
    public function exportCsv(): StreamedResponse
    {
        $this->authorize('export', $this->getModelClass());

        $spreadsheet = $this->createSpreadsheet();
        $filename = $this->getExportFilename('csv');

        return response()->streamDownload(function () use ($spreadsheet) {
            $writer = new Csv($spreadsheet);
            $writer->setDelimiter(',');
            $writer->setEnclosure('"');
            $writer->setLineEnding("\r\n");
            $writer->setSheetIndex(0);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }

    // ===================================================================
    // MÉTODOS PARA EXPORTACIÓN PDF
    // ===================================================================

    /**
     * Retorna el título del PDF.
     */
    protected function getPdfTitle(): string
    {
        $modelClass = $this->getModelClass();
        $modelName = class_basename($modelClass);

        return "Reporte de {$modelName}s";
    }

    /**
     * Retorna la orientación del PDF.
     *
     * @return string 'portrait' o 'landscape'
     */
    protected function getPdfOrientation(): string
    {
        // Landscape por defecto para tablas anchas
        return 'landscape';
    }

    /**
     * Retorna el tamaño de página del PDF.
     *
     * @return string 'a4', 'letter', 'legal', etc.
     */
    protected function getPdfPageSize(): string
    {
        return 'a4';
    }

    /**
     * Retorna información adicional del encabezado del PDF.
     */
    protected function getPdfHeader(): array
    {
        return [
            'fecha_generacion' => date('d/m/Y H:i:s'),
            'usuario' => auth()->user()->name ?? 'Sistema',
        ];
    }

    /**
     * Exporta los datos a PDF.
     *
     * @return \Illuminate\Http\Response
     */
    public function exportPdf()
    {
        $this->authorize('export', $this->getModelClass());

        $columns = $this->getExportColumns();
        $data = $this->getExportData();
        $title = $this->getPdfTitle();
        $header = $this->getPdfHeader();

        // Preparar datos para la vista
        $exportData = [
            'title' => $title,
            'columns' => $columns,
            'data' => $data,
            'header' => $header,
            'formatValue' => fn ($item, $column) => $this->formatExportValue($item, $column),
        ];

        $pdf = Pdf::loadView('exports.pdf-table', $exportData);

        // Configurar PDF
        $pdf->setPaper($this->getPdfPageSize(), $this->getPdfOrientation());

        $filename = $this->getExportFilename('pdf');

        return $pdf->download($filename);
    }
}
