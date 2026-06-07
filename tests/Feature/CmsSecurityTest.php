<?php

namespace Tests\Feature;

use App\Services\Security\RequestSecurityService;
use App\Services\Security\SanitizeHtmlService;
use App\Services\Security\UploadSecurityService;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Tests\TestCase;

class CmsSecurityTest extends TestCase
{
    public function test_dangerous_file_upload_is_blocked(): void
    {
        $service = app(UploadSecurityService::class);

        $file = UploadedFile::fake()->create('shell.php', 100);

        $this->assertTrue($service->isDangerousFile($file));
    }

    public function test_html_sanitization_removes_scripts(): void
    {
        $service = app(SanitizeHtmlService::class);

        $dirtyHtml = '<p>Hello</p><script>alert("xss")</script><p>World</p>';
        $clean = $service->sanitize($dirtyHtml);

        $this->assertStringNotContainsString('<script', $clean);
        $this->assertStringNotContainsString('alert', $clean);
        $this->assertStringContainsString('Hello', $clean);
        $this->assertStringContainsString('World', $clean);
    }

    public function test_suspicious_request_is_blocked(): void
    {
        $service = app(RequestSecurityService::class);

        $request = new Request([], [
            'search' => '\' OR 1=1 --',
        ]);

        $this->assertTrue($service->isSuspiciousRequest($request));
    }

    public function test_honeypot_detects_bots(): void
    {
        $service = app(RequestSecurityService::class);

        $request = new Request([], [
            '_website' => 'http://spam-site.com',
        ]);

        $this->assertTrue($service->isHoneypotFilled($request));
    }
}
