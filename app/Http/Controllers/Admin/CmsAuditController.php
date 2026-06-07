<?php

namespace App\Http\Controllers\Admin;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */

use App\Http\Controllers\Controller;
use App\Services\Cms\CmsAuditService;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CmsAuditController extends Controller
{
    protected CmsAuditService $auditService;

    public function __construct(CmsAuditService $auditService)
    {
        $this->auditService = $auditService;

        $this->middleware('can:cms.audit.view')->only(['index']);
    }

    public function index(Request $request): View
    {
        $filters = $request->only(['action', 'module', 'user_id', 'date_from', 'date_to', 'search']);

        $logs = $this->auditService->getAuditLogs(50, $filters);

        $actions = $this->auditService->getModuleActions();

        return view('admin.cms.audit.index', compact('logs', 'actions', 'filters'));
    }
}
