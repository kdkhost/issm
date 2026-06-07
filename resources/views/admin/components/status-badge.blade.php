{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $type = $type ?? "active";
    $label = $label ?? null;
    $types = [
        "active"    => ["bg" => "bg-green-100", "text" => "text-green-700", "default" => "Ativo"],
        "inactive"  => ["bg" => "bg-gray-100", "text" => "text-gray-600", "default" => "Inativo"],
        "published" => ["bg" => "bg-green-100", "text" => "text-green-700", "default" => "Publicado"],
        "draft"     => ["bg" => "bg-yellow-100", "text" => "text-yellow-700", "default" => "Rascunho"],
        "pending"   => ["bg" => "bg-orange-100", "text" => "text-orange-700", "default" => "Pendente"],
        "success"   => ["bg" => "bg-green-100", "text" => "text-green-700", "default" => "Sucesso"],
        "error"     => ["bg" => "bg-red-100", "text" => "text-red-700", "default" => "Erro"],
        "warning"   => ["bg" => "bg-yellow-100", "text" => "text-yellow-700", "default" => "Atenção"],
        "info"      => ["bg" => "bg-blue-100", "text" => "text-blue-700", "default" => "Info"],
        "new"       => ["bg" => "bg-red-100", "text" => "text-red-600", "default" => "Novo"],
    ];
    $cfg = $types[$type] ?? $types["active"];
    $displayLabel = $label ?? $cfg["default"];
@endphp
<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $cfg["bg"] }} {{ $cfg["text"] }} {{ $attributes->get("class") }}" {{ $attributes->except(["type", "label", "class"]) }}>
    @if($attributes->has("dot"))
    <svg class="w-1.5 h-1.5 mr-1.5 -ml-0.5 fill-current" viewBox="0 0 8 8">
        <circle cx="4" cy="4" r="3" />
    </svg>
    @endif
    {{ $displayLabel }}
</span>
