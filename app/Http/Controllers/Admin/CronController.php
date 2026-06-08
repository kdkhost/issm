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
        $tasks = ScheduledTask::orderBy('id')->get()->map(function ($task) {
            $task->computed_expression = $task->buildExpression();
            $task->computed_next_run = $task->nextRunAt();
            return $task;
        });
        return view('admin.cron.index', compact('tasks'));
    }

    public function update(Request $request, ScheduledTask $task)
    {
        $validated = $request->validate([
            'frequency' => 'required|in:everyMinute,everyFiveMinutes,everyFifteenMinutes,everyThirtyMinutes,hourly,daily,weekly,monthly,custom',
            'minute' => 'nullable|string|max:10',
            'hour' => 'nullable|string|max:10',
            'day_of_month' => 'nullable|string|max:10',
            'month' => 'nullable|string|max:10',
            'day_of_week' => 'nullable|string|max:10',
            'active' => 'boolean',
        ]);

        $data = [
            'frequency' => $validated['frequency'],
            'active' => $request->boolean('active', false),
        ];

        if ($validated['frequency'] === 'custom') {
            $data['minute'] = $validated['minute'] ?? '*';
            $data['hour'] = $validated['hour'] ?? '*';
            $data['day_of_month'] = $validated['day_of_month'] ?? '*';
            $data['month'] = $validated['month'] ?? '*';
            $data['day_of_week'] = $validated['day_of_week'] ?? '*';
        } else {
            $map = [
                'everyMinute' => ['*', '*', '*', '*', '*'],
                'everyFiveMinutes' => ['*/5', '*', '*', '*', '*'],
                'everyFifteenMinutes' => ['*/15', '*', '*', '*', '*'],
                'everyThirtyMinutes' => ['*/30', '*', '*', '*', '*'],
                'hourly' => ['0', '*', '*', '*', '*'],
                'daily' => ['0', '0', '*', '*', '*'],
                'weekly' => ['0', '0', '*', '*', '0'],
                'monthly' => ['0', '0', '1', '*', '*'],
            ];
            [$m, $h, $dom, $mo, $dow] = $map[$validated['frequency']] ?? ['0', '0', '*', '*', '*'];
            $data['minute'] = $m;
            $data['hour'] = $h;
            $data['day_of_month'] = $dom;
            $data['month'] = $mo;
            $data['day_of_week'] = $dow;
        }

        $task->update($data);

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
            $task->update([
                'last_run_at' => now(),
                'next_run_at' => $task->nextRunAt(),
            ]);
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
