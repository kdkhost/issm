<!doctype html>
<html lang="pt-BR">
<head>
    <meta charset="utf-8">
    <title>Nova mensagem de contato</title>
</head>
<body style="margin:0;background:#f3f4f6;font-family:Arial,Helvetica,sans-serif;color:#111827;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background:#f3f4f6;padding:28px 12px;">
        <tr>
            <td align="center">
                <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="max-width:620px;background:#ffffff;border-radius:16px;overflow:hidden;border:1px solid #e5e7eb;">
                    <tr>
                        <td style="background:#15803d;color:#ffffff;padding:22px 26px;">
                            <h1 style="margin:0;font-size:22px;line-height:1.25;">Nova mensagem recebida</h1>
                            <p style="margin:6px 0 0;font-size:14px;opacity:.9;">Formulario de contato do site ISSM</p>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding:24px 26px;">
                            <p style="margin:0 0 18px;font-size:15px;color:#374151;">Uma nova mensagem foi registrada no painel administrativo.</p>

                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border-collapse:collapse;font-size:14px;">
                                <tr>
                                    <td style="padding:10px 0;color:#6b7280;width:120px;border-bottom:1px solid #f3f4f6;">Nome</td>
                                    <td style="padding:10px 0;color:#111827;font-weight:700;border-bottom:1px solid #f3f4f6;">{{ $contact->name }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;color:#6b7280;border-bottom:1px solid #f3f4f6;">E-mail</td>
                                    <td style="padding:10px 0;color:#111827;border-bottom:1px solid #f3f4f6;">{{ $contact->email }}</td>
                                </tr>
                                @if($contact->phone)
                                <tr>
                                    <td style="padding:10px 0;color:#6b7280;border-bottom:1px solid #f3f4f6;">Telefone</td>
                                    <td style="padding:10px 0;color:#111827;border-bottom:1px solid #f3f4f6;">{{ $contact->phone }}</td>
                                </tr>
                                @endif
                                <tr>
                                    <td style="padding:10px 0;color:#6b7280;border-bottom:1px solid #f3f4f6;">Assunto</td>
                                    <td style="padding:10px 0;color:#111827;font-weight:700;border-bottom:1px solid #f3f4f6;">{{ $contact->subject }}</td>
                                </tr>
                                <tr>
                                    <td style="padding:10px 0;color:#6b7280;">Data</td>
                                    <td style="padding:10px 0;color:#111827;">{{ optional($contact->created_at)->format('d/m/Y H:i') }}</td>
                                </tr>
                            </table>

                            <div style="margin-top:20px;padding:16px;border-radius:12px;background:#f9fafb;border:1px solid #e5e7eb;color:#374151;line-height:1.6;font-size:14px;">
                                {!! nl2br(e($contact->message)) !!}
                            </div>

                            <p style="margin:22px 0 0;">
                                <a href="{{ route('admin.contatos.show', $contact) }}" style="display:inline-block;background:#15803d;color:#ffffff;text-decoration:none;font-weight:700;border-radius:10px;padding:12px 18px;">Abrir no painel</a>
                            </p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
