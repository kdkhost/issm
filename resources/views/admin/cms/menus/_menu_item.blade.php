{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
<li data-id="{{ $item->id }}">
    <div class="menu-item-row">
        <div class="menu-drag-handle cursor-grab text-gray-400 hover:text-gray-600" data-tooltip="Arrastar">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8h16M4 16h16"/></svg>
        </div>
        @if($item->icon)
        <span class="text-gray-500 text-sm">{{ $item->icon }}</span>
        @endif
        <span class="font-medium text-gray-900 text-sm flex-1">{{ $item->label }}</span>
        <span class="text-xs text-gray-400 font-mono truncate max-w-[200px]">{{ $item->url }}</span>
        @if($item->target == "_blank")<span class="badge-gray text-xs">blank</span>@endif
        @if(!$item->is_active)<span class="text-xs text-gray-400 italic">inativo</span>@endif
        <div class="flex items-center gap-1">
            <button type="button" class="edit-menu-item text-blue-600 hover:text-blue-800 text-xs font-medium px-1"
                data-id="{{ $item->id }}"
                data-label="{{ $item->label }}"
                data-url="{{ $item->url }}"
                data-icon="{{ $item->icon }}"
                data-target="{{ $item->target }}"
                data-parent="{{ $item->parent_id }}">Editar</button>
            <form method="POST" action="{{ route("admin.cms.menus.destroy", $item) }}" class="inline">
                @csrf @method("DELETE")
                <button type="submit" class="text-red-600 hover:text-red-800 text-xs font-medium px-1" data-confirm="Excluir este item e seus subitens?" data-tooltip="Excluir">Excluir</button>
            </form>
        </div>
    </div>
    @if($item->children->count() > 0)
    <ul class="menu-children space-y-1">
        @foreach($item->children as $child)
            @include("admin.cms.menus._menu_item", ["item" => $child, "depth" => $depth + 1])
        @endforeach
    </ul>
    @endif
</li>
