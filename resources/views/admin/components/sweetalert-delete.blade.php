{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
@php
    $formAction = $formAction ?? "#";
    $message = $message ?? "Esta ação não pode ser desfeita.";
    $title = $title ?? "Confirmar exclusão";
    $confirmText = $confirmText ?? "Sim, excluir";
    $cancelText = $cancelText ?? "Cancelar";
    $itemName = $itemName ?? "registro";
@endphp
<form method="POST" action="{{ $formAction }}" class="inline delete-form-{{ uniqid() }} {{ $attributes->get("class") }}" data-delete-message="{{ $message }}" data-delete-title="{{ $title }}" data-confirm-text="{{ $confirmText }}" data-cancel-text="{{ $cancelText }}" data-item-name="{{ $itemName }}">
    @csrf
    @method("DELETE")
    {{ $slot }}
    <button type="submit" class="text-red-600 hover:text-red-800 text-sm font-medium px-1" data-tooltip="Excluir {{ $itemName }}">
        <svg class="w-4 h-4 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/></svg>
        Excluir
    </button>
</form>

@push("scripts")
<script>
$(function() {
    $("form[data-delete-message]").on("submit", function(e) {
        e.preventDefault();
        var form = this;
        var msg = form.dataset.deleteMessage || "Esta ação não pode ser desfeita.";
        var title = form.dataset.deleteTitle || "Confirmar exclusão";
        var confirmText = form.dataset.confirmText || "Sim, excluir";
        var cancelText = form.dataset.cancelText || "Cancelar";
        Swal.fire({
            title: title,
            text: msg,
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#dc2626",
            cancelButtonColor: "#6b7280",
            confirmButtonText: confirmText,
            cancelButtonText: cancelText,
            reverseButtons: true,
            borderRadius: "16px",
        }).then(function(result) {
            if (result.isConfirmed) form.submit();
        });
    });
});
</script>
@endpush
