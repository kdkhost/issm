<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */
class AdminAuditMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if (app('cms.audit.enabled', true) && $request->user()) {
            app(\App\Services\Cms\CmsAuditService::class)->log([
                'url' => $request->fullUrl(),
                'method' => $request->method(),
                'ip' => $request->ip(),
                'user_agent' => $request->userAgent(),
                'user_id' => $request->user()->id,
                'user_name' => $request->user()->name,
                'user_email' => $request->user()->email,
            ]);
        }

        return $next($request);
    }
}
