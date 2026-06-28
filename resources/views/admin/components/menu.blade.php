@php
$adminMenuItems = \App\Models\AdminMenuItem::getOrdered();
function adminMenuActiveRoutes($item) {
    if ($item->is_dropdown && !empty($item->children)) {
        $patterns = array_map(fn($c) => str_replace(".", "*", $c["route_name"]), $item->children);
        return implode(",", $patterns);
    }
    return $item->route_name . "*";
}
function adminMenuIsActive($item) {
    if ($item->is_dropdown && !empty($item->children)) {
        $routeNames = array_column($item->children, "route_name");
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
        <div class="nav-dropdown {{ $isActive ? "active" : "" }}">
            <button type="button" class="w-full flex items-center justify-between gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors nav-dropdown-trigger">
                <div class="flex items-center gap-3">
                    {!! $item->icon_svg !!}
                    <span>{{ $item->label }}</span>
                </div>
                <svg class="w-4 h-4 transition-transform nav-dropdown-icon" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
            </button>
            <div class="nav-dropdown-content space-y-1 pl-6 mt-1">
                @foreach($item->children as $child)
                    @php $childActive = adminMenuChildIsActive($child["route_name"]); @endphp
                    <a href="{{ route($child["route_name"]) }}" class="flex items-center gap-3 px-3 py-2 rounded-lg text-sm text-green-100 hover:bg-green-700 hover:text-white transition-all {{ $childActive ? "sidebar-link-active" : "" }}">
                        <svg class="w-4 h-4 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                        <span>{{ $child["label"] }}</span>
                        @if($child["route_name"] === "admin.contatos.index")
                            @php $newContacts = \App\Models\Contact::where("status","new")->count(); @endphp
                            @if($newContacts > 0)<span class="ml-auto bg-red-500 text-white text-[10px] rounded-full px-1.5 py-0.5">{{ $newContacts }}</span>@endif
                        @endif
                        @if($child["route_name"] === "admin.project-supports.index")
                            @php $newSupports = \App\Models\ProjectSupportRequest::where("status","new")->count(); @endphp
                            @if($newSupports > 0)<span class="ml-auto bg-red-500 text-white text-[10px] rounded-full px-1.5 py-0.5">{{ $newSupports }}</span>@endif
                        @endif
                    </a>
                @endforeach
            </div>
        </div>
    @else
        @php $isActive = adminMenuIsActive($item); @endphp
        <a href="{{ $item->route_name === "#" ? "#" : route($item->route_name) }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-100 hover:bg-green-700 hover:text-white transition-colors {{ $isActive ? "bg-green-700 text-white" : "" }}">
            {!! $item->icon_svg !!}
            {{ $item->label }}
        </a>
    @endif
@endforeach

<div class="my-2 border-t border-green-700/50"></div>

<div class="space-y-1">
    <p class="px-3 text-[10px] font-bold text-green-500 uppercase tracking-wider">Personalização</p>
    <a href="{{ route("admin.menu.index") }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-300 hover:bg-green-700 hover:text-white transition-colors text-sm {{ request()->routeIs("admin.menu*") ? "bg-green-700 text-white" : "" }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/></svg>
        <span>Editor de Menu Admin</span>
    </a>
    <a href="{{ route("admin.frontend-menu.index") }}" class="flex items-center gap-3 px-3 py-2.5 rounded-lg text-green-300 hover:bg-green-700 hover:text-white transition-colors text-sm {{ request()->routeIs("admin.frontend-menu*") ? "bg-green-700 text-white" : "" }}">
        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/></svg>
        <span>Editor de Menu do Site</span>
    </a>
</div>
