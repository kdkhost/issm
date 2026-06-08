@php
$adminMenuItems = \App\Models\AdminMenuItem::getOrdered();
function adminMenuActiveRoutes($item) {
    if ($item->is_dropdown && !empty($item->children)) {
        $patterns = array_map(fn($c) => str_replace('.', '*', $c['route_name']), $item->children);
        return implode(',', $patterns);
    }
    return $item->route_name . '*';
}
function adminMenuIsActive($item) {
    if ($item->is_dropdown && !empty($item->children)) {
        $routeNames = array_column($item->children, 'route_name');
        return request()->routeIs(...$routeNames);
    }
    return request()->routeIs($item->route_name);
}
function adminMenuChildIsActive($routeName) {
    return request()->routeIs($routeName);
}
@endphp

@foreach($adminMenuItems as $item)
    @if($item->is_dropdown && !empty($item->children))
        @php $isActive = adminMenuIsActive($item); @endphp
        <div class="nav-dropdown {{ $isActive ? 'active' : '' }}">
            <button type="button" class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors nav-dropdown-trigger">
                <div class="flex items-center gap-3">
                    {!! $item->icon_svg !!}
                    <span>{{ $item->label }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform nav-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div class="nav-dropdown-content space-y-1 pl-6 mt-1">
                @foreach($item->children as $child)
                    @php $childActive = adminMenuChildIsActive($child['route_name']); @endphp
                    <a href="{{ route($child['route_name']) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-green-100 hover:bg-green-700 hover:text-white transition-all {{ $childActive ? 'sidebar-link-active' : '' }}">
                        @if($child['route_name'] === 'admin.contatos.index')
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/></svg>
                            <span>{{ $child['label'] }}</span>
                            @php $newContacts = \App\Models\Contact::where('status','new')->count(); @endphp
                            @if($newContacts > 0)<span class="ml-auto bg-red-500 text-white text-[10px] rounded-full px-1.5 py-0.5">{{ $newContacts }}</span>@endif
                        @elseif($child['route_name'] === 'admin.settings.index')
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"/><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/></svg>
                            <span>{{ $child['label'] }}</span>
                        @elseif($child['route_name'] === 'admin.ips-manutencao.index')
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/></svg>
                            <span>{{ $child['label'] }}</span>
                        @elseif($child['route_name'] === 'admin.faq.index')
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            <span>{{ $child['label'] }}</span>
                        @elseif($child['route_name'] === 'admin.analytics.index')
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/></svg>
                            <span>{{ $child['label'] }}</span>
                        @else
                            <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                            <span>{{ $child['label'] }}</span>
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @else
        @php $isActive = adminMenuIsActive($item); @endphp
        <a href="{{ $item->route_name === '#' ? '#' : route($item->route_name) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ $isActive ? 'bg-green-700 text-white' : '' }}">
            {!! $item->icon_svg !!}
            {{ $item->label }}
        </a>
    @endif
@endforeach

{{-- Editor de Menu --}}
<div class="mt-4 pt-4 border-t border-green-700/50">
    <a href="{{ route('admin.menu.index') }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-300 hover:bg-green-700 hover:text-white transition-colors text-sm {{ request()->routeIs('admin.menu*') ? 'bg-green-700 text-white' : '' }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        <span>Editor de Menu</span>
    </a>
</div>
