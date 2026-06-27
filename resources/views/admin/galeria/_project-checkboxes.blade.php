@php
    $selectedProjectIds = collect($selectedProjectIds ?? [])->map(fn ($id) => (string) $id)->all();
@endphp

@once
    @push("styles")
        <style>
            .gallery-project-option {
                display: flex !important;
                align-items: center;
                gap: 10px;
                min-height: 44px;
                border: 1px solid #e5e7eb;
                border-radius: 10px;
                padding: 10px 12px;
                background: #fff;
                color: #374151;
                cursor: pointer;
                transition: background-color .18s ease, border-color .18s ease, color .18s ease;
            }

            .gallery-project-option:hover {
                background: #f0fdf4;
                border-color: #86efac;
                color: #166534;
            }

            .gallery-project-option:has(input:checked) {
                background: #dcfce7;
                border-color: #22c55e;
                color: #166534;
            }

            .gallery-project-option input[type="checkbox"] {
                flex: 0 0 18px;
                width: 18px;
                height: 18px;
                margin: 0;
                align-self: center;
            }

            .gallery-project-option span {
                display: block;
                min-width: 0;
                line-height: 1.35;
            }

            [data-theme="dark"] .gallery-project-option {
                background: #1f2937;
                border-color: #374151;
                color: #e5e7eb;
            }

            [data-theme="dark"] .gallery-project-option:hover {
                background: rgba(34, 197, 94, .08);
                border-color: #22c55e;
                color: #f9fafb;
            }

            [data-theme="dark"] .gallery-project-option:has(input:checked) {
                background: rgba(34, 197, 94, .14);
                border-color: #22c55e;
                color: #f9fafb;
            }
        </style>
    @endpush
@endonce

@if($projects->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-64 overflow-y-auto pr-1">
        @foreach($projects as $project)
            <label class="custom-control-label gallery-project-option text-sm">
                <input type="checkbox" name="project_ids[]" value="{{ $project->id }}" @checked(in_array((string) $project->id, $selectedProjectIds, true)) class="text-green-600 rounded">
                <span class="font-medium">{{ Str::limit($project->title, 85) }}</span>
            </label>
        @endforeach
    </div>
@else
    <p class="text-sm text-gray-500">Nenhum projeto ativo cadastrado.</p>
@endif

@error("project_ids")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
@error("project_ids.*")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
