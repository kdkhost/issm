@extends('layouts.admin')
@section('title', 'Central de Cron')
@section('page-title', 'Central de Tarefas Agendadas')

@section('content')
<style>
.cron-wrap{max-width:900px}
.cron-card{background:#fff;border-radius:1rem;border:1px solid #e5e7eb;overflow:hidden;margin-bottom:1rem}
.cron-head{display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;border-bottom:1px solid #f3f4f6;background:linear-gradient(to right,#f9fafb,#fff)}
.cron-head h3{font-size:1rem;font-weight:700;color:#111827;margin:0;flex:1}
.cron-head .cron-badge{font-size:.6875rem;font-weight:700;padding:.25rem .75rem;border-radius:1rem}
.cron-badge--green{background:#dcfce7;color:#166534}
.cron-badge--gray{background:#f3f4f6;color:#6b7280}
.cron-body{padding:1.25rem}
.cron-row{display:flex;align-items:center;gap:1rem;flex-wrap:wrap}
.cron-label{font-size:.8125rem;font-weight:600;color:#374151;min-width:6rem}
.cron-cmd{background:#f9fafb;border:1px solid #e5e7eb;border-radius:.5rem;padding:.375rem .75rem;font-family:monospace;font-size:.8125rem;color:#374151}
.cron-freq select{border:1px solid #d1d5db;border-radius:.5rem;padding:.375rem .75rem;font-size:.8125rem;background:#fff}
.cron-actions{display:flex;gap:.5rem;margin-left:auto}
.cron-btn{display:inline-flex;align-items:center;gap:.375rem;font-size:.75rem;font-weight:600;padding:.5rem 1rem;border-radius:.5rem;border:none;cursor:pointer;transition:background .15s}
.cron-btn--run{background:#16a34a;color:#fff}
.cron-btn--run:hover{background:#15803d}
.cron-btn--toggle-on{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}
.cron-btn--toggle-off{background:#fee2e2;color:#ef4444;border:1px solid #fecaca}
.cron-last{font-size:.75rem;color:#9ca3af;margin-top:.5rem}
.cron-empty{text-align:center;padding:3rem 1rem;color:#6b7280;font-size:.875rem}
.cron-info{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.75rem;padding:1rem;margin-bottom:1.5rem;font-size:.8125rem;color:#166534}
.cron-info code{background:#fff;padding:.125rem .375rem;border-radius:.25rem;font-family:monospace}
[data-theme="dark"] .cron-card{background:#1f2937;border-color:#374151}
[data-theme="dark"] .cron-head{background:linear-gradient(to right,#1a2535,#1f2937);border-color:#374151}
[data-theme="dark"] .cron-head h3{color:#f9fafb}
[data-theme="dark"] .cron-cmd{background:#1a2535;border-color:#374151;color:#d1d5db}
[data-theme="dark"] .cron-freq select{background:#374151;border-color:#4b5563;color:#f9fafb}
[data-theme="dark"] .cron-last{color:#6b7280}
[data-theme="dark"] .cron-info{background:rgba(22,163,74,.1);border-color:rgba(34,197,94,.2);color:#4ade80}
</style>

<div class="cron-wrap">

    <div class="cron-info">
        <strong>Como funciona:</strong> O servidor executa <code>php artisan schedule:run</code> a cada minuto. O sistema consulta o banco de dados e executa apenas as tarefas <strong>ativas</strong> que estiverem na hora programada. Nenhuma requisicao HTTP e necessaria.
    </div>

    @if($tasks->isEmpty())
        <div class="cron-empty">Nenhuma tarefa agendada cadastrada.</div>
    @else
        @foreach($tasks as $task)
        <div class="cron-card">
            <div class="cron-head">
                <h3>{{ $task->description ?? $task->command }}</h3>
                <span class="cron-badge {{ $task->active ? 'cron-badge--green' : 'cron-badge--gray' }}">
                    {{ $task->active ? 'Ativa' : 'Inativa' }}
                </span>
            </div>
            <div class="cron-body">
                <div class="cron-row">
                    <span class="cron-label">Comando:</span>
                    <code class="cron-cmd">php artisan {{ $task->command }}</code>
                </div>
                <div class="cron-row" style="margin-top:.75rem">
                    <span class="cron-label">Frequencia:</span>
                    <form method="POST" action="{{ route('admin.cron.update', $task) }}" class="cron-freq" style="display:flex;align-items:center;gap:.5rem">
                        @csrf
                        @method('PUT')
                        <select name="frequency" onchange="this.form.submit()">
                            <option value="everyMinute" {{ $task->frequency === 'everyMinute' ? 'selected' : '' }}>A cada minuto</option>
                            <option value="hourly" {{ $task->frequency === 'hourly' ? 'selected' : '' }}>A cada hora</option>
                            <option value="daily" {{ $task->frequency === 'daily' ? 'selected' : '' }}>Diario</option>
                            <option value="weekly" {{ $task->frequency === 'weekly' ? 'selected' : '' }}>Semanal</option>
                            <option value="monthly" {{ $task->frequency === 'monthly' ? 'selected' : '' }}>Mensal</option>
                        </select>
                    </form>
                    <div class="cron-actions">
                        <form method="POST" action="{{ route('admin.cron.toggle', $task) }}" style="display:inline">
                            @csrf
                            <button type="submit" class="cron-btn {{ $task->active ? 'cron-btn--toggle-off' : 'cron-btn--toggle-on' }}">
                                {{ $task->active ? 'Desativar' : 'Ativar' }}
                            </button>
                        </form>
                        <form method="POST" action="{{ route('admin.cron.run', $task) }}" style="display:inline" onsubmit="return confirm('Executar este comando agora?')">
                            @csrf
                            <button type="submit" class="cron-btn cron-btn--run">Executar agora</button>
                        </form>
                    </div>
                </div>
                @if($task->last_run_at)
                <div class="cron-last">Ultima execucao: {{ $task->last_run_at->format('d/m/Y H:i:s') }}</div>
                @endif
            </div>
        </div>
        @endforeach
    @endif

</div>
@endsection
