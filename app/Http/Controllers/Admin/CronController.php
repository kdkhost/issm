<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ScheduledTask;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class CronController extends Controller
{
    public function index()
    {
        $this->ensureDefaultTasks();
        $tasks = ScheduledTask::orderBy('id')->get();
        return view('admin.cron.index', compact('tasks'));
    }

    public function update(Request $request, ScheduledTask $task)
    {
        $validated = $request->validate([
            'frequency' => 'required|in:everyMinute,hourly,daily,weekly,monthly',
            'active' => 'boolean',
        ]);

        $task->update([
            'frequency' => $validated['frequency'],
            'active' => $request->boolean('active', false),
        ]);

        return redirect()->back()->with('success', 'Tarefa atualizada com sucesso!');
    }

    public function toggle(ScheduledTask $task)
    {
        $task->update(['active' => !$task->active]);
        return redirect()->back()->with('success', $task->active ? 'Tarefa ativada.' : 'Tarefa desativada.');
    }

    public function runNow(ScheduledTask $task)
    {
        try {
            Artisan::call($task->command);
            $output = Artisan::output();
            $task->update(['last_run_at' => now()]);
            return redirect()->back()->with('success', "Comando executado com sucesso.\n\nSaida:\n{$output}");
        } catch (\Throwable $e) {
            return redirect()->back()->with('error', 'Erro ao executar comando: ' . $e->getMessage());
        }
    }

    private function ensureDefaultTasks(): void
    {
        foreach (ScheduledTask::defaultTasks() as $definition) {
            ScheduledTask::firstOrCreate(
                ['command' => $definition['command']],
                $definition
            );
        }
    }
}
