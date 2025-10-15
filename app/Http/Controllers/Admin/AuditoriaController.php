<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Response;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Font;

class AuditoriaController extends Controller
{
    /**
{{ ... }}
     * Parsea el User Agent para extraer información resumida.
     */
    private function parseUserAgent(string $userAgent): array
    {
        $info = [
            'browser' => 'Desconocido',
            'os' => 'Desconocido',
            'full' => $userAgent
        ];

        // Detectar navegador
        if (stripos($userAgent, 'Chrome') !== false && stripos($userAgent, 'Edg') === false) {
            $info['browser'] = 'Chrome';
            if (preg_match('/Chrome\/(\d+\.\d+)/', $userAgent, $matches)) {
                $info['browser'] .= ' ' . $matches[1];
            }
        } elseif (stripos($userAgent, 'Firefox') !== false) {
            $info['browser'] = 'Firefox';
            if (preg_match('/Firefox\/(\d+\.\d+)/', $userAgent, $matches)) {
                $info['browser'] .= ' ' . $matches[1];
            }
        } elseif (stripos($userAgent, 'Safari') !== false && stripos($userAgent, 'Chrome') === false) {
            $info['browser'] = 'Safari';
            if (preg_match('/Version\/(\d+\.\d+)/', $userAgent, $matches)) {
                $info['browser'] .= ' ' . $matches[1];
            }
        } elseif (stripos($userAgent, 'Edg') !== false) {
            $info['browser'] = 'Edge';
            if (preg_match('/Edg\/(\d+\.\d+)/', $userAgent, $matches)) {
                $info['browser'] .= ' ' . $matches[1];
            }
        }

        // Detectar sistema operativo
        if (stripos($userAgent, 'Windows') !== false) {
            $info['os'] = 'Windows';
            if (preg_match('/Windows NT (\d+\.\d+)/', $userAgent, $matches)) {
                $info['os'] .= ' ' . $matches[1];
            }
        } elseif (stripos($userAgent, 'Mac OS X') !== false || stripos($userAgent, 'Macintosh') !== false) {
            $info['os'] = 'macOS';
            if (preg_match('/Mac OS X (\d+[._]\d+)/', $userAgent, $matches)) {
                $info['os'] .= ' ' . str_replace('_', '.', $matches[1]);
            }
        } elseif (stripos($userAgent, 'Linux') !== false) {
            $info['os'] = 'Linux';
        } elseif (stripos($userAgent, 'Android') !== false) {
            $info['os'] = 'Android';
        }

        return $info;
    }

    /**
     * Genera el contenido CSV.
     */
    private function generateCSV($query, $selectedFields): void
    {
        $file = fopen('php://output', 'w');

        // Configurar para UTF-8
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM para UTF-8

        // Construir headers dinámicamente
        $headers = [];
        if ($selectedFields['fecha']) $headers[] = 'Fecha';
        if ($selectedFields['usuario']) $headers[] = 'Usuario';
        if ($selectedFields['accion']) $headers[] = 'Acción';
        if ($selectedFields['modelo']) $headers[] = 'Modelo';
        if ($selectedFields['id_registro']) $headers[] = 'ID Registro';
        if ($selectedFields['ip_address']) $headers[] = 'IP Address';
        if ($selectedFields['valores_anteriores']) $headers[] = 'Valores Anteriores';
        if ($selectedFields['valores_nuevos']) $headers[] = 'Valores Nuevos';
        if ($selectedFields['navegador']) $headers[] = 'Navegador';
        if ($selectedFields['sistema_operativo']) $headers[] = 'Sistema Operativo';
        if ($selectedFields['user_agent_completo']) $headers[] = 'User Agent Completo';

        // Escribir headers
        fputcsv($file, $headers, ';', '"');

        // Datos del CSV
        foreach ($query as $log) {
            $row = [];

            if ($selectedFields['fecha']) {
                $row[] = $log->created_at->format('d/m/Y H:i:s');
            }
            if ($selectedFields['usuario']) {
                $row[] = $log->user ? $log->user->nombre_1 . ' ' . $log->user->apellido_1 : 'Sistema';
            }
            if ($selectedFields['accion']) {
                $row[] = ucfirst($log->action);
            }
            if ($selectedFields['modelo']) {
                $row[] = class_basename($log->model_type);
            }
            if ($selectedFields['id_registro']) {
                $row[] = $log->model_id;
            }
            if ($selectedFields['ip_address']) {
                $row[] = $log->ip_address;
            }
            if ($selectedFields['valores_anteriores']) {
                $row[] = $log->old_values ? '"' . addslashes(json_encode($log->old_values, JSON_UNESCAPED_UNICODE)) . '"' : '';
            }
            if ($selectedFields['valores_nuevos']) {
                $row[] = $log->new_values ? '"' . addslashes(json_encode($log->new_values, JSON_UNESCAPED_UNICODE)) . '"' : '';
            }

            // Procesar User Agent
            if ($log->user_agent) {
                $userAgentInfo = $this->parseUserAgent($log->user_agent);

                if ($selectedFields['navegador']) {
                    $row[] = $userAgentInfo['browser'];
                }
                if ($selectedFields['sistema_operativo']) {
                    $row[] = $userAgentInfo['os'];
                }
                if ($selectedFields['user_agent_completo']) {
                    $row[] = '"' . addslashes($log->user_agent) . '"';
                }
            } else {
                if ($selectedFields['navegador']) $row[] = 'Desconocido';
                if ($selectedFields['sistema_operativo']) $row[] = 'Desconocido';
                if ($selectedFields['user_agent_completo']) $row[] = '';
            }

            fputcsv($file, $row, ';', '"');
        }

        fclose($file);
    }

    /**
     * Genera contenido Excel compatible (XML Spreadsheet 2003).
     */
    private function generateExcelCSV($query, $selectedFields): void
    {
        // Por ahora, generar CSV compatible con Excel (delimitado por comas)
        $file = fopen('php://output', 'w');

        // Configurar para UTF-8
        fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF)); // BOM para UTF-8

        // Construir headers
        $headers = [];
        if ($selectedFields['fecha']) $headers[] = 'Fecha';
        if ($selectedFields['usuario']) $headers[] = 'Usuario';
        if ($selectedFields['accion']) $headers[] = 'Acción';
        if ($selectedFields['modelo']) $headers[] = 'Modelo';
        if ($selectedFields['id_registro']) $headers[] = 'ID Registro';
        if ($selectedFields['ip_address']) $headers[] = 'IP Address';
        if ($selectedFields['valores_anteriores']) $headers[] = 'Valores Anteriores';
        if ($selectedFields['valores_nuevos']) $headers[] = 'Valores Nuevos';
        if ($selectedFields['navegador']) $headers[] = 'Navegador';
        if ($selectedFields['sistema_operativo']) $headers[] = 'Sistema Operativo';
        if ($selectedFields['user_agent_completo']) $headers[] = 'User Agent Completo';

        fputcsv($file, $headers, ',', '"');

        // Datos
        foreach ($query as $log) {
            $row = [];

            if ($selectedFields['fecha']) $row[] = $log->created_at->format('d/m/Y H:i:s');
            if ($selectedFields['usuario']) $row[] = $log->user ? $log->user->nombre_1 . ' ' . $log->user->apellido_1 : 'Sistema';
            if ($selectedFields['accion']) $row[] = ucfirst($log->action);
            if ($selectedFields['modelo']) $row[] = class_basename($log->model_type);
            if ($selectedFields['id_registro']) $row[] = $log->model_id;
            if ($selectedFields['ip_address']) $row[] = $log->ip_address;
            if ($selectedFields['valores_anteriores']) $row[] = $log->old_values ? json_encode($log->old_values, JSON_UNESCAPED_UNICODE) : '';
            if ($selectedFields['valores_nuevos']) $row[] = $log->new_values ? json_encode($log->new_values, JSON_UNESCAPED_UNICODE) : '';

            if ($log->user_agent) {
                $userAgentInfo = $this->parseUserAgent($log->user_agent);
                if ($selectedFields['navegador']) $row[] = $userAgentInfo['browser'];
                if ($selectedFields['sistema_operativo']) $row[] = $userAgentInfo['os'];
                if ($selectedFields['user_agent_completo']) $row[] = $log->user_agent;
            } else {
                if ($selectedFields['navegador']) $row[] = 'Desconocido';
                if ($selectedFields['sistema_operativo']) $row[] = 'Desconocido';
                if ($selectedFields['user_agent_completo']) $row[] = '';
            }

            fputcsv($file, $row, ',', '"');
        }

        fclose($file);
    }

    /**
     * Genera contenido PDF simple.
     */
    private function generateSimplePDF($query, $selectedFields): void
    {
        // Generar un PDF básico sin librerías externas
        $pdf = "%PDF-1.4\n";
        $pdf .= "1 0 obj\n<< /Type /Catalog /Pages 2 0 R >>\nendobj\n";
        $pdf .= "2 0 obj\n<< /Type /Pages /Kids [3 0 R] /Count 1 >>\nendobj\n";
        $pdf .= "3 0 obj\n<< /Type /Page /Parent 2 0 R /Resources << /Font << /F1 4 0 R >> >> /MediaBox [0 0 612 792] /Contents 5 0 R >>\nendobj\n";
        $pdf .= "4 0 obj\n<< /Type /Font /Subtype /Type1 /BaseFont /Helvetica >>\nendobj\n";
        
        $content = "BT\n/F1 12 Tf\n50 750 Td\n(Reporte de Auditoria) Tj\n0 -20 Td\n";
        $y = 730;
        
        foreach ($query->take(20) as $log) { // Limitar a 20 registros por simplicidad
            $line = sprintf("(%s - %s - %s) Tj\n0 -15 Td\n",
                $log->created_at->format('d/m/Y'),
                $log->user ? substr($log->user->nombre_1, 0, 15) : 'Sistema',
                substr(ucfirst($log->action), 0, 20)
            );
            $content .= $line;
            $y -= 15;
            if ($y < 50) break;
        }
        
        $content .= "ET\n";
        $length = strlen($content);
        
        $pdf .= "5 0 obj\n<< /Length $length >>\nstream\n$content\nendstream\nendobj\n";
        $pdf .= "xref\n0 6\n0000000000 65535 f\n0000000009 00000 n\n0000000056 00000 n\n0000000115 00000 n\n0000000259 00000 n\n0000000339 00000 n\n";
        $pdf .= "trailer\n<< /Size 6 /Root 1 0 R >>\nstartxref\n" . strlen($pdf) . "\n%%EOF";
        
        echo $pdf;
    }

    /**
     * Exporta los logs de auditoría en formato CSV.
     */
    public function export(Request $request)
    {
        // Configurar locale para manejo correcto de caracteres especiales
        setlocale(LC_ALL, 'es_ES.UTF-8');

        // Obtener parámetros de consulta
        $search = $request->get('search', '');
        $actionFilter = $request->get('action_filter', '');
        $modelFilter = $request->get('model_filter', '');
        $userFilter = $request->get('user_filter');
        $startDate = $request->get('start_date', '');
        $endDate = $request->get('end_date', '');
        $sortField = $request->get('sort_field', 'created_at');
        $sortDirection = $request->get('sort_direction', 'desc');

        // Obtener formato de exportación
        $format = $request->get('format', 'csv');

        // Obtener opciones de campos seleccionados
        $selectedFields = [
            'fecha' => (bool) $request->get('fecha', true),
            'usuario' => (bool) $request->get('usuario', true),
            'accion' => (bool) $request->get('accion', true),
            'modelo' => (bool) $request->get('modelo', true),
            'id_registro' => (bool) $request->get('id_registro', true),
            'ip_address' => (bool) $request->get('ip_address', true),
            'valores_anteriores' => (bool) $request->get('valores_anteriores', true),
            'valores_nuevos' => (bool) $request->get('valores_nuevos', true),
            'navegador' => (bool) $request->get('navegador', true),
            'sistema_operativo' => (bool) $request->get('sistema_operativo', true),
            'user_agent_completo' => (bool) $request->get('user_agent_completo', false)
        ];

        // Construir la consulta con los filtros
        $query = AuditLog::query()
            ->with('user:id,nombre_1,apellido_1,correo')
            ->when($search, function($q) {
                $q->where(function($query) {
                    $query->where('action', 'like', '%' . $search . '%')
                          ->orWhere('model_type', 'like', '%' . $search . '%')
                          ->orWhere('ip_address', 'like', '%' . $search . '%')
                          ->orWhereHas('user', function($q) {
                              $q->where('nombre_1', 'like', '%' . $search . '%')
                                ->orWhere('apellido_1', 'like', '%' . $search . '%')
                                ->orWhere('correo', 'like', '%' . $search . '%');
                          });
                });
            })
            ->when($actionFilter, fn($q) => $q->where('action', $actionFilter))
            ->when($modelFilter, fn($q) => $q->where('model_type', 'like', '%' . $modelFilter . '%'))
            ->when($userFilter, fn($q) => $q->where('user_id', $userFilter))
            ->when($startDate, fn($q) => $q->whereDate('created_at', '>=', $startDate))
            ->when($endDate, fn($q) => $q->whereDate('created_at', '<=', $endDate))
            ->orderBy($sortField, $sortDirection)
            ->get();

        // Crear el contenido según formato
        $timestamp = now()->format('Y-m-d_H-i-s');
        $filename = "auditoria_{$timestamp}";

        // Generar archivo según el formato
        switch ($format) {
            case 'excel':
                // Generar archivo XLSX nativo
                return $this->generateNativeExcel($query, $selectedFields, $filename . '.xlsx');
                
            case 'pdf':
                $pdf = Pdf::loadView('exports.auditoria', [
                    'rows' => $query,
                    'selectedFields' => $selectedFields
                ]);
                return $pdf->download($filename . '.pdf');
                
            default:
                // CSV
                $headers = [
                    'Content-Type' => 'text/csv; charset=UTF-8',
                    'Content-Disposition' => 'attachment; filename="' . $filename . '.csv"',
                ];
                
                $callback = function() use ($query, $selectedFields) {
                    $this->generateCSV($query, $selectedFields);
                };
                
                return response()->stream($callback, 200, $headers);
        }
    }

    /**
     * Genera archivo Excel XLSX nativo usando PhpSpreadsheet.
     */
    private function generateNativeExcel($query, $selectedFields, $filename)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Auditoría');

        // Configurar headers
        $headers = [];
        if ($selectedFields['fecha']) $headers[] = 'Fecha';
        if ($selectedFields['usuario']) $headers[] = 'Usuario';
        if ($selectedFields['accion']) $headers[] = 'Acción';
        if ($selectedFields['modelo']) $headers[] = 'Modelo';
        if ($selectedFields['id_registro']) $headers[] = 'ID Registro';
        if ($selectedFields['ip_address']) $headers[] = 'IP Address';
        if ($selectedFields['valores_anteriores']) $headers[] = 'Valores Anteriores';
        if ($selectedFields['valores_nuevos']) $headers[] = 'Valores Nuevos';
        if ($selectedFields['navegador']) $headers[] = 'Navegador';
        if ($selectedFields['sistema_operativo']) $headers[] = 'Sistema Operativo';
        if ($selectedFields['user_agent_completo']) $headers[] = 'User Agent Completo';

        // Escribir headers
        $col = 'A';
        foreach ($headers as $header) {
            $sheet->setCellValue($col . '1', $header);
            $sheet->getStyle($col . '1')->getFont()->setBold(true);
            $sheet->getStyle($col . '1')->getFill()
                ->setFillType(Fill::FILL_SOLID)
                ->getStartColor()->setARGB('FFE2E8F0');
            $col++;
        }

        // Escribir datos
        $row = 2;
        foreach ($query as $log) {
            $col = 'A';
            
            if ($selectedFields['fecha']) {
                $sheet->setCellValue($col++ . $row, $log->created_at->format('d/m/Y H:i:s'));
            }
            if ($selectedFields['usuario']) {
                $usuario = $log->user ? $log->user->nombre_1 . ' ' . $log->user->apellido_1 : 'Sistema';
                $sheet->setCellValue($col++ . $row, $usuario);
            }
            if ($selectedFields['accion']) {
                $sheet->setCellValue($col++ . $row, ucfirst($log->action));
            }
            if ($selectedFields['modelo']) {
                $sheet->setCellValue($col++ . $row, class_basename($log->model_type));
            }
            if ($selectedFields['id_registro']) {
                $sheet->setCellValue($col++ . $row, $log->model_id);
            }
            if ($selectedFields['ip_address']) {
                $sheet->setCellValue($col++ . $row, $log->ip_address);
            }
            if ($selectedFields['valores_anteriores']) {
                $value = $log->old_values ? json_encode($log->old_values, JSON_UNESCAPED_UNICODE) : '';
                $sheet->setCellValue($col++ . $row, $value);
            }
            if ($selectedFields['valores_nuevos']) {
                $value = $log->new_values ? json_encode($log->new_values, JSON_UNESCAPED_UNICODE) : '';
                $sheet->setCellValue($col++ . $row, $value);
            }

            if ($log->user_agent) {
                $userAgentInfo = $this->parseUserAgent($log->user_agent);
                if ($selectedFields['navegador']) {
                    $sheet->setCellValue($col++ . $row, $userAgentInfo['browser']);
                }
                if ($selectedFields['sistema_operativo']) {
                    $sheet->setCellValue($col++ . $row, $userAgentInfo['os']);
                }
                if ($selectedFields['user_agent_completo']) {
                    $sheet->setCellValue($col++ . $row, $log->user_agent);
                }
            } else {
                if ($selectedFields['navegador']) $sheet->setCellValue($col++ . $row, 'Desconocido');
                if ($selectedFields['sistema_operativo']) $sheet->setCellValue($col++ . $row, 'Desconocido');
                if ($selectedFields['user_agent_completo']) $sheet->setCellValue($col++ . $row, '');
            }

            $row++;
        }

        // Auto-ajustar ancho de columnas
        foreach (range('A', $col) as $columnID) {
            $sheet->getColumnDimension($columnID)->setAutoSize(true);
        }

        // Generar y descargar archivo
        $writer = new Xlsx($spreadsheet);
        
        return response()->streamDownload(function() use ($writer) {
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ]);
    }
}
