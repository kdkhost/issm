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

use App\Models\CmsVersion;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class CmsVersionService
{
    public function createVersion(Model $model, string $summary = ''): CmsVersion
    {
        return CmsVersion::create([
            'versionable_type' => get_class($model),
            'versionable_id' => $model->id,
            'data' => $model->toArray(),
            'summary' => $summary,
            'user_id' => Auth::id(),
        ]);
    }

    public function restoreVersion(CmsVersion $version): Model
    {
        $modelClass = $version->versionable_type;
        $model = $modelClass::findOrFail($version->versionable_id);

        $this->createVersion($model, 'Restaurado para versão #' . $version->id);

        $model->update($version->data);

        return $model->fresh();
    }

    public function getVersions(Model $model): Collection
    {
        return CmsVersion::where('versionable_type', get_class($model))
            ->where('versionable_id', $model->id)
            ->with('user')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getVersionDiff(CmsVersion $version): array
    {
        $currentData = $version->data;

        $previous = CmsVersion::where('versionable_type', $version->versionable_type)
            ->where('versionable_id', $version->versionable_id)
            ->where('id', '<', $version->id)
            ->orderBy('id', 'desc')
            ->first();

        if (!$previous) {
            return [
                'added' => $currentData,
                'removed' => [],
                'modified' => [],
            ];
        }

        $oldData = $previous->data;

        $added = array_diff_key($currentData, $oldData);
        $removed = array_diff_key($oldData, $currentData);
        $modified = [];

        foreach (array_intersect_key($currentData, $oldData) as $key => $value) {
            $oldValue = $oldData[$key] ?? null;
            if ($value !== $oldValue) {
                $modified[$key] = [
                    'old' => $oldValue,
                    'new' => $value,
                ];
            }
        }

        return compact('added', 'removed', 'modified');
    }

    public function getLatestVersion(Model $model): ?CmsVersion
    {
        return CmsVersion::where('versionable_type', get_class($model))
            ->where('versionable_id', $model->id)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function pruneVersions(Model $model, int $keep = 10): void
    {
        $versionIds = CmsVersion::where('versionable_type', get_class($model))
            ->where('versionable_id', $model->id)
            ->orderBy('id', 'desc')
            ->take($keep)
            ->pluck('id');

        if ($versionIds->isNotEmpty()) {
            CmsVersion::where('versionable_type', get_class($model))
                ->where('versionable_id', $model->id)
                ->whereNotIn('id', $versionIds)
                ->delete();
        }
    }

    public function compareVersions(int $versionId1, int $versionId2): array
    {
        $v1 = CmsVersion::findOrFail($versionId1);
        $v2 = CmsVersion::findOrFail($versionId2);

        $data1 = $v1->data;
        $data2 = $v2->data;

        $added = array_diff_key($data2, $data1);
        $removed = array_diff_key($data1, $data2);
        $modified = [];

        foreach (array_intersect_key($data2, $data1) as $key => $value) {
            $oldValue = $data1[$key] ?? null;
            if ($value !== $oldValue) {
                $modified[$key] = [
                    'from' => $oldValue,
                    'to' => $value,
                ];
            }
        }

        return compact('v1', 'v2', 'added', 'removed', 'modified');
    }
}
