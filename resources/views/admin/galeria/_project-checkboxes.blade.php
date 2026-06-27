@php
    $selectedProjectIds = collect($selectedProjectIds ?? [])->map(fn ($id) => (string) $id)->all();
@endphp

@if($projects->count())
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 max-h-64 overflow-y-auto pr-1">
        @foreach($projects as $project)
            <label class="flex items-start gap-2 rounded-lg border border-gray-200 px-3 py-2 text-sm cursor-pointer hover:bg-green-50 transition-colors">
                <input type="checkbox" name="project_ids[]" value="{{ $project->id }}" @checked(in_array((string) $project->id, $selectedProjectIds, true)) class="mt-0.5 w-4 h-4 text-green-600 rounded">
                <span class="font-medium text-gray-700 leading-snug">{{ Str::limit($project->title, 85) }}</span>
            </label>
        @endforeach
    </div>
@else
    <p class="text-sm text-gray-500">Nenhum projeto ativo cadastrado.</p>
@endif

@error("project_ids")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
@error("project_ids.*")<p class="text-sm text-red-600 mt-1">{{ $message }}</p>@enderror
