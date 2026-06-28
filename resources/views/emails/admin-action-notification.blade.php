<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>{{ $type }} - {{ $title }}</title>
</head>
<body style="margin:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:640px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:#15803d;color:#ffffff;padding:22px 26px;">
                            <p style="margin:0 0 6px;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.06em;opacity:.85;">{{ $type }}</p>
                            <h1 style="margin:0;font-size:22px;line-height:1.25;">{{ $title }}</h1>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 26px;">
                            <p style="margin:0 0 18px;font-size:15px;color:#374151;line-height:1.6;">{{ $summary }}</p>

                            @if($details)
                                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;">
                                    @foreach($details as $label => $value)
                                        @if($value !== null && $value !== '')
                                            <tr>
                                                <td style="padding:10px 0;color:#6b7280;width:150px;border-bottom:1px solid #f3f4f6;">{{ $label }}</td>
                                                <td style="padding:10px 0;color:#111827;font-weight:700;border-bottom:1px solid #f3f4f6;">{{ $value }}</td>
                                            </tr>
                                        @endif
                                    @endforeach
                                </table>
                            @endif

                            <p style="margin:22px 0 0;">
                                <a href="{{ $url }}" style="display:inline-block;background:#15803d;color:#ffffff;text-decoration:none;font-weight:700;border-radius:10px;padding:12px 18px;">Abrir no painel</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
