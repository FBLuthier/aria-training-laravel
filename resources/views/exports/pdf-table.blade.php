<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 10px;
            color: #333;
            padding: 20px;
        }
        
        .header {
            margin-bottom: 20px;
            border-bottom: 2px solid #4F46E5;
            padding-bottom: 10px;
        }
        
        .header h1 {
            font-size: 18px;
            color: #4F46E5;
            margin-bottom: 5px;
        }
        
        .header-info {
            font-size: 9px;
            color: #666;
            margin-top: 5px;
        }
        
        .header-info span {
            margin-right: 15px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        
        thead {
            background-color: #4F46E5;
            color: white;
        }
        
        th {
            padding: 8px 6px;
            text-align: left;
            font-weight: bold;
            font-size: 10px;
            border: 1px solid #4F46E5;
        }
        
        tbody tr {
            border-bottom: 1px solid #E5E7EB;
        }
        
        tbody tr:nth-child(even) {
            background-color: #F9FAFB;
        }
        
        tbody tr:hover {
            background-color: #F3F4F6;
        }
        
        td {
            padding: 6px 6px;
            border: 1px solid #E5E7EB;
            font-size: 9px;
        }
        
        .footer {
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #E5E7EB;
            text-align: center;
            font-size: 8px;
            color: #999;
        }
        
        .page-break {
            page-break-after: always;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .no-data {
            padding: 30px;
            text-align: center;
            color: #999;
            font-style: italic;
        }
    </style>
</head>
<body>
    {{-- Encabezado --}}
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="header-info">
            <span><strong>Fecha:</strong> {{ $header['fecha_generacion'] }}</span>
            <span><strong>Generado por:</strong> {{ $header['usuario'] }}</span>
            <span><strong>Total de registros:</strong> {{ count($data) }}</span>
        </div>
    </div>
    
    {{-- Tabla de datos --}}
    @if(count($data) > 0)
        <table>
            <thead>
                <tr>
                    @foreach($columns as $column => $label)
                        <th>{{ $label }}</th>
                    @endforeach
                </tr>
            </thead>
            <tbody>
                @foreach($data as $item)
                    <tr>
                        @foreach(array_keys($columns) as $column)
                            <td>{{ $formatValue($item, $column) }}</td>
                        @endforeach
                    </tr>
                @endforeach
            </tbody>
        </table>
    @else
        <div class="no-data">
            No hay datos para exportar
        </div>
    @endif
    
    {{-- Pie de página --}}
    <div class="footer">
        <p>Documento generado automáticamente - {{ config('app.name') }}</p>
    </div>
</body>
</html>
