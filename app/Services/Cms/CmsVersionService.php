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
            'model_type' => get_class($model),
            'model_id' => $model->id,
            'version_data' => $model->toArray(),
            'summary' => $summary,
            'user_id' => Auth::id(),
        ]);
    }

    public function restoreVersion(CmsVersion $version): Model
    {
        $modelClass = $version->model_type;
        $model = $modelClass::findOrFail($version->model_id);

        $this->createVersion($model, 'Restaurado para versão #' . $version->id);

        $model->update($version->version_data);

        return $model->fresh();
    }

    public function getVersions(Model $model): Collection
    {
        return CmsVersion::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->with('user')
            ->orderBy('id', 'desc')
            ->get();
    }

    public function getVersionDiff(CmsVersion $version): array
    {
        $currentData = $version->version_data;

        $previous = CmsVersion::where('model_type', $version->model_type)
            ->where('model_id', $version->model_id)
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

        $oldData = $previous->version_data;

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
        return CmsVersion::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->orderBy('id', 'desc')
            ->first();
    }

    public function pruneVersions(Model $model, int $keep = 10): void
    {
        $versionIds = CmsVersion::where('model_type', get_class($model))
            ->where('model_id', $model->id)
            ->orderBy('id', 'desc')
            ->take($keep)
            ->pluck('id');

        if ($versionIds->isNotEmpty()) {
            CmsVersion::where('model_type', get_class($model))
                ->where('model_id', $model->id)
                ->whereNotIn('id', $versionIds)
                ->delete();
        }
    }

    public function compareVersions(int $versionId1, int $versionId2): array
    {
        $v1 = CmsVersion::findOrFail($versionId1);
        $v2 = CmsVersion::findOrFail($versionId2);

        $data1 = $v1->version_data;
        $data2 = $v2->version_data;

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
