{{-- @autor marcelo-brad rj --}}
{{-- @contato Tel: 21 981325441 | Email: contato@kdkhost.com.br | Telegram: @MARCELO_BRAD | Instagram: @marcelobradrj | WhatsApp: 21981325441 --}}
{{-- Toast notifications are handled globally in layouts/admin.blade.php via showToast() --}}
{{-- This component is a placeholder for inline usage --}}
@once
@push("scripts")
<script>
function showToast(message, type) {
    type = type || "success";
    var bg = type === "success"
        ? "linear-gradient(135deg, #16a34a, #15803d)"
        : type === "error"
        ? "linear-gradient(135deg, #dc2626, #b91c1c)"
        : "linear-gradient(135deg, #2563eb, #1d4ed8)";
    Toastify({
        text: message,
        duration: 4500,
        gravity: "top",
        position: "right",
        stopOnFocus: true,
        style: {
            background: bg,
            borderRadius: "12px",
            padding: "12px 20px",
            fontSize: "14px",
            fontFamily: "'Inter', sans-serif",
            boxShadow: "0 8px 24px rgba(0,0,0,0.18)",
            minWidth: "260px",
        },
    }).showToast();
}
</script>
@endpush
@endonce
