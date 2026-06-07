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
class BlockSuspiciousRequests
{
    private array $sqlPatterns = [
        '/\bUNION\b.*\bSELECT\b/i',
        '/\bSELECT\b.*\bFROM\b/i',
        '/\bDROP\s+TABLE/i',
        '/\bDROP\s+DATABASE/i',
        '/\bDELETE\s+FROM/i',
        '/\bUPDATE\s+\w+\s+SET/i',
        '/\bINSERT\s+INTO/i',
        '/\bALTER\s+TABLE/i',
        '/\bCREATE\s+TABLE/i',
        '/\bTRUNCATE\s+TABLE/i',
        '/\bEXEC\b/i',
        '/\bEXECUTE\b/i',
        '/\bLOAD_FILE\b/i',
        '/\bINTO\s+OUTFILE/i',
        '/\bINTO\s+DUMPFILE/i',
        '/\bSLEEP\s*\(/i',
        '/\bBENCHMARK\s*\(/i',
    ];

    private array $xssPatterns = [
        '/<script\b[^>]*>(.*?)<\/script>/is',
        '/javascript\s*:/i',
        '/on\w+\s*=\s*["\']?[^"\'>]*["\']?/i',
        '/<iframe\b[^>]*>/i',
        '/<object\b[^>]*>/i',
        '/<embed\b[^>]*>/i',
        '/<svg\b[^>]*>/i',
        '/document\.cookie/i',
        '/window\.location/i',
        '/eval\s*\(/i',
        '/alert\s*\(/i',
        '/prompt\s*\(/i',
        '/confirm\s*\(/i',
        '/String\.fromCharCode/i',
    ];

    private array $pathTraversalPatterns = [
        '/\.\.\\\\/',
        '/\.\.\\//',
        '/%2e%2e/i',
        '/%252e%252e/i',
        '/%c0%ae/i',
        '/%c0%ae%c0%ae/i',
        '/\\0/',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        if (!config('cms.security.block_suspicious', true)) {
            return $next($request);
        }

        $input = $request->all();
        $inputString = json_encode($input);
        $queryString = $request->getQueryString() ?? '';
        $uri = $request->getRequestUri();
        $combined = $inputString . ' ' . $queryString . ' ' . $uri;

        if ($this->isSuspicious($combined)) {
            app(\App\Services\Security\RequestSecurityService::class)->logSuspicious($request);
            abort(400, 'Suspicious request blocked.');
        }

        return $next($request);
    }

    private function isSuspicious(string $input): bool
    {
        foreach ($this->sqlPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        foreach ($this->xssPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        foreach ($this->pathTraversalPatterns as $pattern) {
            if (preg_match($pattern, $input)) {
                return true;
            }
        }

        return false;
    }
}
