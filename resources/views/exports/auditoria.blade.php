<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Reporte de Auditoría</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 10px;
            margin: 20px;
        }
        h1 {
            text-align: center;
            color: #333;
            font-size: 18px;
            margin-bottom: 20px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th {
            background-color: #4A5568;
            color: white;
            padding: 8px;
            text-align: left;
            font-weight: bold;
            font-size: 9px;
        }
        td {
            padding: 6px;
            border-bottom: 1px solid #ddd;
            font-size: 8px;
        }
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        .header-info {
            margin-bottom: 15px;
            font-size: 10px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-size: 8px;
            color: #666;
        }
    </style>
</head>
<body>
    <h1>Reporte de Auditoría</h1>
    
    <div class="header-info">
        <strong>Fecha de generación:</strong> {{ now()->format('d/m/Y H:i:s') }}<br>
        <strong>Total de registros:</strong> {{ count($rows) }}
    </div>

    <table>
        <thead>
            <tr>
                @if($selectedFields['fecha'])
                    <th>Fecha</th>
                @endif
                @if($selectedFields['usuario'])
                    <th>Usuario</th>
                @endif
                @if($selectedFields['accion'])
                    <th>Acción</th>
                @endif
                @if($selectedFields['modelo'])
                    <th>Modelo</th>
                @endif
                @if($selectedFields['id_registro'])
                    <th>ID</th>
                @endif
                @if($selectedFields['ip_address'])
                    <th>IP</th>
                @endif
                @if($selectedFields['navegador'])
                    <th>Navegador</th>
                @endif
                @if($selectedFields['sistema_operativo'])
                    <th>SO</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @foreach($rows as $log)
                <tr>
                    @if($selectedFields['fecha'])
                        <td>{{ $log->created_at->format('d/m/Y H:i') }}</td>
                    @endif
                    @if($selectedFields['usuario'])
                        <td>{{ $log->user ? $log->user->nombre_1 . ' ' . $log->user->apellido_1 : 'Sistema' }}</td>
                    @endif
                    @if($selectedFields['accion'])
                        <td>{{ ucfirst($log->action) }}</td>
                    @endif
                    @if($selectedFields['modelo'])
                        <td>{{ class_basename($log->model_type) }}</td>
                    @endif
                    @if($selectedFields['id_registro'])
                        <td>{{ $log->model_id }}</td>
                    @endif
                    @if($selectedFields['ip_address'])
                        <td>{{ $log->ip_address }}</td>
                    @endif
                    @if($selectedFields['navegador'])
                        @php
                            $userAgentInfo = ['browser' => 'Desconocido', 'os' => 'Desconocido'];
                            if ($log->user_agent) {
                                // Detectar navegador
                                if (preg_match('/Firefox\/([0-9.]+)/', $log->user_agent, $matches)) {
                                    $userAgentInfo['browser'] = 'Firefox';
                                } elseif (preg_match('/Edg\/([0-9.]+)/', $log->user_agent, $matches)) {
                                    $userAgentInfo['browser'] = 'Edge';
                                } elseif (preg_match('/Chrome\/([0-9.]+)/', $log->user_agent, $matches)) {
                                    $userAgentInfo['browser'] = 'Chrome';
                                } elseif (preg_match('/Safari/', $log->user_agent) && !preg_match('/Chrome/', $log->user_agent)) {
                                    $userAgentInfo['browser'] = 'Safari';
                                }
                                // Detectar OS
                                if (preg_match('/Windows NT/', $log->user_agent)) {
                                    $userAgentInfo['os'] = 'Windows';
                                } elseif (preg_match('/Mac OS X/', $log->user_agent)) {
                                    $userAgentInfo['os'] = 'macOS';
                                } elseif (preg_match('/Linux/', $log->user_agent)) {
                                    $userAgentInfo['os'] = 'Linux';
                                } elseif (preg_match('/Android/', $log->user_agent)) {
                                    $userAgentInfo['os'] = 'Android';
                                }
                            }
                        @endphp
                        <td>{{ $userAgentInfo['browser'] }}</td>
                    @endif
                    @if($selectedFields['sistema_operativo'])
                        <td>{{ $userAgentInfo['os'] ?? 'Desconocido' }}</td>
                    @endif
                </tr>
            @endforeach
        </tbody>
    </table>

    <div class="footer">
        <p>Generado por Sistema de Auditoría - {{ config('app.name') }}</p>
    </div>
</body>
</html>
