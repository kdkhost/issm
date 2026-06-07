<?php

namespace App\Services\Cms;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */

use App\Models\CmsAuditLog;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class CmsAuditService
{
    public function log(string $action, string $module, mixed $model = null, array $oldValues = [], array $newValues = []): CmsAuditLog
    {
        return CmsAuditLog::create([
            'user_id' => Auth::id(),
            'action' => $action,
            'module' => $module,
            'model_type' => is_object($model) ? get_class($model) : null,
            'model_id' => is_object($model) ? $model->id : (is_int($model) ? $model : null),
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'ip_address' => Request::ip(),
            'user_agent' => Request::userAgent(),
        ]);
    }

    public function logLogin(): void
    {
        $this->log('login', 'auth');
    }

    public function logLogout(): void
    {
        $this->log('logout', 'auth');
    }

    public function logFailedLogin(string $email): void
    {
        $this->log('failed_login', 'auth', null, [], ['email' => $email]);
    }

    public function logCreate(string $module, mixed $model): void
    {
        $this->log('create', $module, $model, [], is_object($model) ? $model->toArray() : []);
    }

    public function logUpdate(string $module, mixed $model, array $oldValues, array $newValues): void
    {
        $this->log('update', $module, $model, $oldValues, $newValues);
    }

    public function logDelete(string $module, mixed $model): void
    {
        $this->log('delete', $module, $model, is_object($model) ? $model->toArray() : [], []);
    }

    public function logPublish(string $module, mixed $model): void
    {
        $this->log('publish', $module, $model);
    }

    public function logArchive(string $module, mixed $model): void
    {
        $this->log('archive', $module, $model);
    }

    public function logUpload(string $module, mixed $model): void
    {
        $this->log('upload', $module, $model);
    }

    public function logCacheClear(?string $module = null): void
    {
        $this->log('cache_clear', $module ?? 'cms');
    }

    public function logRestore(string $module, mixed $model): void
    {
        $this->log('restore', $module, $model);
    }

    public function getAuditLogs(int $perPage = 50, array $filters = []): LengthAwarePaginator
    {
        $query = CmsAuditLog::with('user')->latest();

        if (!empty($filters['action'])) {
            $query->where('action', $filters['action']);
        }

        if (!empty($filters['module'])) {
            $query->where('module', $filters['module']);
        }

        if (!empty($filters['user_id'])) {
            $query->where('user_id', $filters['user_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function ($q) use ($search) {
                $q->where('module', 'like', "%{$search}%")
                  ->orWhere('action', 'like', "%{$search}%");
            });
        }

        return $query->paginate($perPage);
    }

    public function getModuleActions(): Collection
    {
        return CmsAuditLog::select('module', 'action')
            ->distinct()
            ->orderBy('module')
            ->orderBy('action')
            ->get()
            ->groupBy('module')
            ->map(fn ($items) => $items->pluck('action'));
    }
}
