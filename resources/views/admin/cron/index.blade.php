@extends('layouts.admin')
@section('title', 'Central de Cron')
@section('page-title', 'Central de Tarefas Agendadas')

@section('content')
<style>
.cron-wrap{max-width:1100px}
.cron-card{background:#fff;border-radius:1rem;border:1px solid #e5e7eb;overflow:hidden;margin-bottom:1.25rem;box-shadow:0 1px 3px rgba(0,0,0,.04)}
.cron-head{display:flex;align-items:center;gap:1rem;padding:1rem 1.25rem;border-bottom:1px solid #f3f4f6;background:linear-gradient(to right,#f9fafb,#fff)}
.cron-head h3{font-size:1rem;font-weight:700;color:#111827;margin:0;flex:1}
.cron-head .cron-badge{font-size:.6875rem;font-weight:700;padding:.25rem .75rem;border-radius:1rem}
.cron-badge--green{background:#dcfce7;color:#166534}
.cron-badge--gray{background:#f3f4f6;color:#6b7280}
.cron-badge--blue{background:#dbeafe;color:#1e40af}
.cron-body{padding:1.25rem}
.cron-row{display:flex;align-items:flex-start;gap:1rem;flex-wrap:wrap}
.cron-label{font-size:.75rem;font-weight:700;color:#6b7280;text-transform:uppercase;letter-spacing:.04em;margin-bottom:.25rem}
.cron-cmd{background:#f9fafb;border:1px solid #e5e7eb;border-radius:.5rem;padding:.375rem .75rem;font-family:monospace;font-size:.8125rem;color:#374151;display:inline-block}
.cron-field{display:flex;flex-direction:column;gap:.25rem}
.cron-field select,.cron-field input{border:1px solid #d1d5db;border-radius:.5rem;padding:.375rem .5rem;font-size:.8125rem;background:#fff;min-width:4.5rem}
.cron-actions{display:flex;gap:.5rem;margin-left:auto;align-items:center}
.cron-btn{display:inline-flex;align-items:center;gap:.375rem;font-size:.75rem;font-weight:600;padding:.5rem 1rem;border-radius:.5rem;border:none;cursor:pointer;transition:background .15s}
.cron-btn--run{background:#16a34a;color:#fff}
.cron-btn--run:hover{background:#15803d}
.cron-btn--toggle-on{background:#dcfce7;color:#166534;border:1px solid #bbf7d0}
.cron-btn--toggle-off{background:#fee2e2;color:#ef4444;border:1px solid #fecaca}
.cron-meta{display:flex;gap:1.5rem;margin-top:1rem;padding-top:1rem;border-top:1px solid #f3f4f6;flex-wrap:wrap}
.cron-meta-item{display:flex;flex-direction:column;gap:.125rem}
.cron-meta-label{font-size:.6875rem;color:#9ca3af;font-weight:600;text-transform:uppercase}
.cron-meta-value{font-size:.8125rem;color:#374151;font-weight:600}
.cron-expr{font-family:monospace;font-size:.8125rem;background:#1f2937;color:#4ade80;padding:.25rem .5rem;border-radius:.375rem}
.cron-empty{text-align:center;padding:3rem 1rem;color:#6b7280;font-size:.875rem}
.cron-info{background:#f0fdf4;border:1px solid #bbf7d0;border-radius:.75rem;padding:1rem;margin-bottom:1.5rem;font-size:.8125rem;color:#166534}
.cron-info code{background:#fff;padding:.125rem .375rem;border-radius:.25rem;font-family:monospace}
.cron-grid{display:grid;grid-template-columns:repeat(5,1fr);gap:.5rem;margin-top:.5rem}
.cron-grid-label{text-align:center;font-size:.6875rem;color:#9ca3af;font-weight:600}
[data-theme="dark"] .cron-card{background:#1f2937;border-color:#374151}
[data-theme="dark"] .cron-head{background:linear-gradient(to right,#1a2535,#1f2937);border-color:#374151}
[data-theme="dark"] .cron-head h3{color:#f9fafb}
[data-theme="dark"] .cron-cmd{background:#1a2535;border-color:#374151;color:#d1d5db}
[data-theme="dark"] .cron-field select,[data-theme="dark"] .cron-field input{background:#374151;border-color:#4b5563;color:#f9fafb}
[data-theme="dark"] .cron-meta-value{color:#d1d5db}
[data-theme="dark"] .cron-meta-label{color:#6b7280}
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
                    <div style="flex:1;min-width:0">
                        <div class="cron-label">Comando</div>
                        <code class="cron-cmd">php artisan {{ $task->command }}</code>
                    </div>
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

                <form method="POST" action="{{ route('admin.cron.update', $task) }}" style="margin-top:1rem">
                    @csrf
                    @method('PUT')
                    <div style="display:flex;align-items:center;gap:1rem;flex-wrap:wrap;margin-bottom:.5rem">
                        <span class="cron-label" style="margin:0">Frequencia</span>
                        <select name="frequency" onchange="document.getElementById('cron-form-{{ $task->id }}').submit();" style="display:none">
                            <option value="{{ $task->frequency }}" selected>{{ $task->frequency }}</option>
                        </select>
                    </div>
                    <div id="cron-form-{{ $task->id }}">
                        <div style="display:flex;align-items:center;gap:.75rem;flex-wrap:wrap;margin-bottom:.75rem">
                            <select name="frequency" class="cron-field" onchange="toggleCronFields{{ $task->id }}(this.value)" style="border:1px solid #d1d5db;border-radius:.5rem;padding:.5rem .75rem;font-size:.8125rem;background:#fff;min-width:10rem">
                                <option value="everyMinute" {{ $task->frequency === 'everyMinute' ? 'selected' : '' }}>A cada minuto</option>
                                <option value="everyFiveMinutes" {{ $task->frequency === 'everyFiveMinutes' ? 'selected' : '' }}>A cada 5 minutos</option>
                                <option value="everyFifteenMinutes" {{ $task->frequency === 'everyFifteenMinutes' ? 'selected' : '' }}>A cada 15 minutos</option>
                                <option value="everyThirtyMinutes" {{ $task->frequency === 'everyThirtyMinutes' ? 'selected' : '' }}>A cada 30 minutos</option>
                                <option value="hourly" {{ $task->frequency === 'hourly' ? 'selected' : '' }}>A cada hora</option>
                                <option value="daily" {{ $task->frequency === 'daily' ? 'selected' : '' }}>Diario</option>
                                <option value="weekly" {{ $task->frequency === 'weekly' ? 'selected' : '' }}>Semanal</option>
                                <option value="monthly" {{ $task->frequency === 'monthly' ? 'selected' : '' }}>Mensal</option>
                                <option value="custom" {{ $task->frequency === 'custom' ? 'selected' : '' }}>Personalizado</option>
                            </select>
                            <button type="submit" class="cron-btn cron-btn--run">Salvar</button>
                        </div>

                        <div id="cron-custom-{{ $task->id }}" style="display:{{ $task->frequency === 'custom' ? 'block' : 'none' }}">
                            <div class="cron-grid-labels" style="display:grid;grid-template-columns:repeat(5,1fr);gap:.5rem;margin-bottom:.25rem">
                                <div class="cron-grid-label">Minuto</div>
                                <div class="cron-grid-label">Hora</div>
                                <div class="cron-grid-label">Dia</div>
                                <div class="cron-grid-label">Mes</div>
                                <div class="cron-grid-label">Semana</div>
                            </div>
                            <div class="cron-grid">
                                <div class="cron-field"><input type="text" name="minute" value="{{ $task->minute }}" placeholder="*"></div>
                                <div class="cron-field"><input type="text" name="hour" value="{{ $task->hour }}" placeholder="*"></div>
                                <div class="cron-field"><input type="text" name="day_of_month" value="{{ $task->day_of_month }}" placeholder="*"></div>
                                <div class="cron-field"><input type="text" name="month" value="{{ $task->month }}" placeholder="*"></div>
                                <div class="cron-field"><input type="text" name="day_of_week" value="{{ $task->day_of_week }}" placeholder="*"></div>
                            </div>
                            <div style="font-size:.6875rem;color:#9ca3af;margin-top:.25rem">Exemplos: minuto <code>*/5</code>, hora <code>0</code>, dia <code>1</code>, mes <code>*</code>, semana <code>0</code> (domingo)</div>
                        </div>
                    </div>
                </form>

                <div class="cron-meta">
                    <div class="cron-meta-item">
                        <span class="cron-meta-label">Expressao Cron</span>
                        <span class="cron-expr">{{ $task->computed_expression }}</span>
                    </div>
                    @if($task->last_run_at)
                    <div class="cron-meta-item">
                        <span class="cron-meta-label">Ultima execucao</span>
                        <span class="cron-meta-value">{{ $task->last_run_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    @endif
                    @if($task->computed_next_run)
                    <div class="cron-meta-item">
                        <span class="cron-meta-label">Proxima execucao</span>
                        <span class="cron-meta-value">{{ $task->computed_next_run->format('d/m/Y H:i:s') }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <script>
        function toggleCronFields{{ $task->id }}(val) {
            document.getElementById('cron-custom-{{ $task->id }}').style.display = val === 'custom' ? 'block' : 'none';
        }
        </script>
        @endforeach
    @endif

</div>
@endsection
