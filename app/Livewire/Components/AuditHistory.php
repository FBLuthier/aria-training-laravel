<?php

namespace App\Livewire\Components;

use Illuminate\Support\Facades\DB;
use Livewire\Attributes\On;
use Livewire\Component;

class AuditHistory extends Component
{
    public $modelType;

    public $modelId;

    public $logs = [];

    public $isOpen = false;

    #[On('open-audit-history')]
    public function open($modelType, $modelId)
    {
        $this->modelType = $modelType;
        $this->modelId = $modelId;
        $this->loadLogs();
        $this->isOpen = true;
    }

    public function close()
    {
        $this->isOpen = false;
        $this->reset(['modelType', 'modelId', 'logs']);
    }

    public function loadLogs()
    {
        // Asumiendo una tabla 'audit_logs' simple
        // Ajusta según tu esquema real de auditoría
        $this->logs = DB::table('audit_logs')
            ->where('model_type', $this->modelType)
            ->where('model_id', $this->modelId)
            ->orderBy('created_at', 'desc')
            ->limit(50)
            ->get()
            ->map(function ($log) {
                // Intentar obtener el nombre del usuario que hizo la acción
                $user = \App\Models\User::find($log->user_id);
                $log->user_name = $user ? $user->usuario : 'Sistema/Desconocido';

                return $log;
            });
    }

    public function render()
    {
        return view('livewire.components.audit-history');
    }
}
