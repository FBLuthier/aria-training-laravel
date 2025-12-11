<?php

namespace App\Livewire\Components;

use Livewire\Component;
use Illuminate\Support\Facades\DB;
use App\Models\User;

class RecentActivity extends Component
{
    public $activities = [];

    public function mount()
    {
        $this->loadActivities();
    }

    public function loadActivities()
    {
        $this->activities = DB::table('audit_logs')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get()
            ->map(function ($log) {
                $user = User::find($log->user_id);
                $log->user_name = $user ? $user->usuario : 'Sistema';
                
                // Formatear mensaje amigable
                $modelName = class_basename($log->model_type);
                $action = match($log->action) {
                    'created' => 'creó',
                    'updated' => 'actualizó',
                    'deleted' => 'eliminó',
                    'restored' => 'restauró',
                    'forceDeleted' => 'eliminó permanentemente',
                    default => $log->action
                };
                
                $log->description = "{$log->user_name} {$action} un registro de {$modelName}";
                
                return $log;
            });
    }

    public function render()
    {
        return view('livewire.components.recent-activity');
    }
}
