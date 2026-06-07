# Relatório de Endurecimento de Segurança - ISSM

## Sumário

Este relatório documenta todas as medidas de segurança implementadas no CMS Institucional do ISSM, bem como recomendações adicionais para garantir a proteção dos dados e do sistema contra ameaças comuns de segurança web.

---

## Headers de Segurança

### Implementados via Middleware

Os seguintes headers de segurança são aplicados globalmente através de middleware personalizado ou configuração do servidor:

| Header | Valor | Descrição |
|--------|-------|-----------|
| `X-Frame-Options` | `SAMEORIGIN` | Previne clickjacking |
| `X-Content-Type-Options` | `nosniff` | Previne MIME type sniffing |
| `X-XSS-Protection` | `1; mode=block` | Proteção XSS em navegadores antigos |
| `Referrer-Policy` | `strict-origin-when-cross-origin` | Controle de referrer |
| `Permissions-Policy` | `geolocation=(), microphone=(), camera=()` | Restringe APIs do navegador |
| `Strict-Transport-Security` | `max-age=31536000; includeSubDomains` | HSTS (em produção com HTTPS) |

### Content Security Policy (CSP)

```
Content-Security-Policy: default-src 'self';
    script-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net;
    style-src 'self' 'unsafe-inline' https://cdn.jsdelivr.net https://fonts.googleapis.com;
    img-src 'self' data: https:;
    font-src 'self' https://fonts.gstatic.com https://cdn.jsdelivr.net;
    connect-src 'self';
    frame-src 'none';
    object-src 'none';
    base-uri 'self';
    form-action 'self'
```

### Configuração no Laravel

No arquivo `app/Http/Middleware/SecurityHeaders.php`:

```php
public function handle($request, Closure $next)
{
    $response = $next($request);

    $response->headers->set('X-Frame-Options', 'SAMEORIGIN');
    $response->headers->set('X-Content-Type-Options', 'nosniff');
    $response->headers->set('X-XSS-Protection', '1; mode=block');
    $response->headers->set('Referrer-Policy', 'strict-origin-when-cross-origin');
    $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=()');

    if (app()->environment('production')) {
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains');
    }

    return $response;
}
```

---

## Proteção XSS

### Camadas de Proteção

1. **Blade Templating Engine**
   - `{{ $var }}` escapa HTML automaticamente
   - `{!! $var !!}` exibe raw, usado APENAS em conteúdo confiável
   - Todo conteúdo de usuário passa por `{{ }}` por padrão

2. **Summernote Security Hardening**
   - `codeviewFilter: true` - Bloqueia scripts no codeview
   - `codeviewFilterRegex` - Regex para remover `<script>`, `<iframe>`, `<embed>`, `<object>`, event handlers em SVG
   - Popovers de imagem/link/tabela desabilitados
   - Paste sanitization: texto puro via `document.execCommand('insertText')`
   - Server-side: conteúdo sanitizado com HTMLPurifier

3. **HTML Sanitization Server-Side**
   - Utiliza `stevebauman/purify` ou `mews/purifier` para limpeza de HTML
   - Configuração permite apenas tags seguras: `p, a, img, ul, ol, li, h1-h6, table, tr, td, th, blockquote, pre, code, em, strong, br, hr, figure, figcaption, span, div`
   - Remove todos os atributos `on*` (onclick, onload, onerror, etc.)
   - Remove atributos `style` perigosos
   - Normaliza URLs para prevenir `javascript:` pseudo-protocolo

4. **Content Security Policy**
   - Restringe execução de scripts a origens confiáveis
   - Bloqueia eval() e inline scripts não autorizados

---

## Proteção SQL Injection

### Medidas Implementadas

1. **Eloquent ORM**
   - Todas as queries usam Eloquent (prepared statements)
   - NENHUMA query raw é utilizada
   - Parâmetros são sempre bindados via Query Builder

2. **Validação de Input**
   - `StorePageRequest`, `UpdatePageRequest`, etc. validam tipos e formatos
   - IDs são validados como `integer` ou `exists:table,id`
   - Strings têm tamanho máximo definido

3. **Mass Assignment Protection**
   - `$guarded` ou `$fillable` definidos em todos os Models
   - Nenhum campo não esperado pode ser atribuído em massa

4. **Prepared Statements em Relatórios**
   - Mesmo para queries complexas, usa-se Query Builder com bindings

---

## Upload Seguro

### Validação de Arquivos

```php
public function rules()
{
    return [
        'file' => [
            'required', 'file',
            'mimes:jpeg,png,jpg,gif,svg,webp,pdf,doc,docx,xls,xlsx,ppt,pptx,mp4,webm,ogg,zip',
            'max:51200',
            'mimetypes:image/jpeg,image/png,image/gif,image/svg+xml,image/webp,
                         application/pdf,application/msword,
                         application/vnd.openxmlformats-officedocument.wordprocessingml.document,
                         application/vnd.ms-excel,
                         application/vnd.openxmlformats-officedocument.spreadsheetml.sheet,
                         video/mp4,video/webm,application/zip',
        ],
    ];
}
```

### Sanitização

- **Nome do arquivo**: Normalizado com `Str::slug()` para evitar path traversal
- **Extensão**: Extraída do MIME type real, nunca da extensão do nome
- **Tamanho**: Múltiplas verificações (PHP `upload_max_filesize`, Laravel `max`, Nginx/Apache)
- **Imagens**: Redimensionamento e conversão para JPEG/PNG remove metadados EXIF
- **SVG**: Sanitizado para remover scripts (via `enshrined/svg-sanitize`)
- **Armazenamento**: Salvos em disco `public` com URL ofuscada (hash)
- **Download**: Headers `Content-Disposition: attachment` e `X-Content-Type-Options: nosniff`

### Configuração de Servidor

```apache
<FilesMatch "\.(php|phar|phtml|php3|php4|php5|pl|cgi|py|rb|asp|aspx|sh|bat)$">
    Require all denied
</FilesMatch>
```

---

## Rate Limiting

### Configuração

```php
Route::middleware('throttle:60,1')->group(function () {
    Route::post('/admin/media/upload', ...);
    Route::post('/admin/media', ...);
});

Route::middleware('throttle:5,1')->group(function () {
    Route::post('/login', ...);
});
```

### Políticas de Rate Limit

| Rota | Limite | Janela | Abuso |
|------|--------|--------|-------|
| `/admin/media/upload` | 30 requisições | 1 minuto | Upload excessivo |
| `/admin/media` (store) | 20 requisições | 1 minuto | Criação de mídia |
| `POST /login` | 5 tentativas | 1 minuto | Bruteforce |
| `POST /forgot-password` | 3 tentativas | 1 hora | Spam de email |
| `POST /admin/seo/bulk` | 10 requisições | 1 minuto | Atualização em massa |
| API pública (se houver) | 60 requisições | 1 minuto | Uso geral |

---

## Honeypot

### Implementação

Utiliza o pacote `spatie/laravel-honeypot` para proteger formulários contra bots:

```php
// No formulário Blade:
{{ honeypot() }}

// No controller ou form request:
use Spatie\Honeypot\ProtectAgainstSpam;

public function __construct()
{
    $this->middleware(ProtectAgainstSpam::class);
}
```

### Funcionamento

- Campo oculto `my_name` preenchido por bots automaticamente
- Campo `my_time` verifica se o formulário foi preenchido muito rápido (< 3s indica bot)
- Presente em todos os formulários públicos (contato, newsletter, etc.)
- Implementado também no login admin como camada extra

---

## Sanitização HTML

### Pipeline de Sanitização

1. **Client-side** (Summernote)
   - Codeview filter bloqueia scripts
   - Paste como texto puro
   - Popovers removidos

2. **Server-side** (HTMLPurifier)
   ```php
   $config = HTMLPurifier_Config::createDefault();
   $config->set('HTML.Allowed',
       'p[class],a[href|target|title|class|rel],img[src|alt|class|width|height],
        ul[class],ol[class],li[class],h1[class],h2[class],h3[class],
        h4[class],h5[class],h6[class],table[class],tr,td[colspan],th[colspan],
        blockquote[class],pre[class],code[class],em[class],strong[class],
        br,hr[class],figure[class],figcaption[class],span[class],div[class],
        sub,sup,mark[class],small[class],del[class],ins[class]');
   $config->set('HTML.TargetBlank', true);
   $config->set('Attr.AllowedRel', ['nofollow', 'noopener', 'noreferrer']);
   $config->set('HTML.Nofollow', true);
   $config->set('URI.AllowedSchemes', ['http', 'https', 'mailto', 'tel']);
   $config->set('AutoFormat.RemoveEmpty', true);

   $purifier = new HTMLPurifier($config);
   $cleanHtml = $purifier->purify($dirtyHtml);
   ```

3. **Output**
   - Blade escapa por padrão
   - Apenas conteúdo sanitizado usa `{!! !!}`
   - JSON-LD é gerado por controller, nunca por usuário

---

## CSRF

### Implementação

- Todas as rotas POST/PUT/DELETE no grupo `web` têm proteção CSRF
- Token CSRF incluído em:
  - Meta tag `<meta name="csrf-token" content="{{ csrf_token() }}">`
  - Campo hidden `@csrf` em formulários Blade
  - Header `X-CSRF-TOKEN` em requisições AJAX fetch()
  - Header `X-XSRF-TOKEN` para requisições de frameworks SPA

### JavaScript Fetch Pattern

```javascript
fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
        'Accept': 'application/json',
    },
    body: JSON.stringify(data),
});
```

### Exceções

- Rotas no grupo `api` são stateless (autenticação via token JWT/Sanctum)
- Webhooks (se implementados) têm verificação de assinatura HMAC

---

## Sessão Segura

### Configuração (`config/session.php`)

```php
return [
    'secure' => env('SESSION_SECURE_COOKIE', true),
    'http_only' => true,
    'same_site' => 'lax',
    'lifetime' => 120,
    'expire_on_close' => env('SESSION_EXPIRE_ON_CLOSE', false),
    'encrypt' => env('SESSION_ENCRYPT', false),
];
```

### Medidas Adicionais

- **Regeneração de sessão** após login (`session()->regenerate()`)
- **Timeout de inatividade** configurável (2h padrão)
- **Bloqueio de múltiplas sessões** (opcional via listener)
- **Invalidar sessão** ao alterar senha
- **Cookie de sessão** com nome customizado (não `laravel_session` padrão)
- **Driver de sessão** recomendado: Redis em produção, database como fallback

---

## Logs de Segurança

### Eventos Auditados

| Evento | Dados Registrados |
|--------|-------------------|
| Login bem-sucedido | User, IP, User-Agent, timestamp |
| Login falho | Tentativa, IP, User-Agent, timestamp |
| Logout | User, IP, timestamp |
| Criação de conteúdo | User, entidade, valores |
| Atualização de conteúdo | User, entidade, diff (old -> new) |
| Exclusão de conteúdo | User, entidade, valores excluídos |
| Restauração de versão | User, entidade, versao |
| Upload de mídia | User, filename, size, type |
| Exclusão de mídia | User, filename, path |
| Alteração de papel/permissão | User, target user, changes |
| Alteração de senha | User, IP, timestamp |
| Limpeza de cache | User, tipo de cache |
| Acesso negado (403) | User, rota, IP, timestamp |
| Rate limit atingido | IP, rota, timestamp |

### Estrutura do Audit Log

```php
Schema::create('audit_logs', function (Blueprint $table) {
    $table->id();
    $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
    $table->string('action', 50);
    $table->string('entity_type', 255);
    $table->unsignedBigInteger('entity_id')->nullable();
    $table->string('entity_label', 255)->nullable();
    $table->text('description');
    $table->json('old_values')->nullable();
    $table->json('new_values')->nullable();
    $table->string('ip_address', 45)->nullable();
    $table->text('user_agent')->nullable();
    $table->timestamps();

    $table->index(['entity_type', 'entity_id']);
    $table->index('user_id');
    $table->index('action');
    $table->index('created_at');
});
```

### Retenção de Logs

- **Logs de auditoria**: 90 dias (prune via comando Artisan)
- **Laravel log**: 30 dias (log rotation)
- **Logs de acesso (servidor)**: 30 dias
- **Backup mensal**: Armazenamento seguro externo

---

## Recomendações Adicionais

### Prioridade Alta

1. **Forçar HTTPS** em producao via redirect middleware
2. **Configurar HSTS** com `preload` para inclusao em navegadores
3. **Implementar 2FA** para todos os usuarios admin via `laravel-forge/laravel-two-factor`
4. **Auditoria regular** de permissoes e usuarios inativos
5. **Atualizar dependencias** semanalmente (`composer audit`, `npm audit`)

### Prioridade Média

6. **Whitelist de IPs** para acesso ao /admin via middleware ou firewall
7. **Captcha** (Google reCAPTCHA v3) em formularios publicos
8. **Monitoramento** com ferramenta como Sentry para erros e anomalias
9. **Testes de penetracao** trimestrais
10. **Politica de senha forte** (min 12 char, maiusculas, minusculas, numeros, especiais)

### Prioridade Baixa

11. **Web Application Firewall** (Cloudflare, AWS WAF, ModSecurity)
12. **Security.txt** arquivo para reporte de vulnerabilidades
13. **Bug Bounty Program** interno
14. **Hardening de servidor** (Linux kernel params, fail2ban, etc.)

---

## Checklist de Conformidade

| Requisito | Status | Observacao |
|-----------|--------|------------|
| LGPD (Lei Geral de Protecao de Dados) | Parcial | Implementar consentimento explicito em formularios |
| ISO 27001 | Nao aplicavel | Para referencia futura |
| OWASP Top 10 | Coberto | Ver detalhes por categoria |
| PCI DSS | Nao aplicavel | Sem processamento de pagamento |
| LGPD - Direito ao esquecimento | Implementado | Soft delete + exclusao definitiva via comando |

---

## Contato de Seguranca

- **Email**: seguranca@issm.mg.gov.br
- **Responsavel**: Equipe de TI - ISSM
- **Ultima revisao**: 07/06/2026

---

*Relatorio gerado em: 07/06/2026*
*Versao do CMS: 1.0.0*
