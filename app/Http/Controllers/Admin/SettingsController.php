<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except(['_token', '_method']);

        // Process image removals (fields ending with _remove)
        foreach ($data as $removeKey => $removeValue) {
            if (str_ends_with($removeKey, '_remove') && $removeValue == '1') {
                $settingKey = substr($removeKey, 0, -7); // remove "_remove" suffix
                $oldValue = Setting::get($settingKey);
                if ($oldValue && Storage::disk('public')->exists($oldValue)) {
                    Storage::disk('public')->delete($oldValue);
                }
                $data[$settingKey] = '';
                unset($data[$removeKey]);
            }
        }

        // Process image file uploads (fields ending with _file)
        foreach ($request->allFiles() as $fileKey => $file) {
            if (str_ends_with($fileKey, '_file')) {
                $settingKey = substr($fileKey, 0, -5); // remove "_file" suffix
                // Skip upload if remove was requested
                if (isset($data[$settingKey]) && $data[$settingKey] === '') {
                    unset($data[$fileKey]);
                    continue;
                }
                if ($file->isValid()) {
                    // Delete old file if exists
                    $oldValue = Setting::get($settingKey);
                    if ($oldValue && Storage::disk('public')->exists($oldValue)) {
                        Storage::disk('public')->delete($oldValue);
                    }
                    $path = $file->store('settings', 'public');
                    $data[$settingKey] = $path;
                }
                unset($data[$fileKey]); // remove the _file key
            }
        }

        foreach ($data as $key => $value) {
            // Skip _file keys that might remain
            if (str_ends_with($key, '_file')) continue;
            Setting::set($key, $value);
        }

        // Handle boolean checkboxes (unchecked = not submitted)
        $booleanKeys = Setting::where('type', 'boolean')->pluck('key');
        foreach ($booleanKeys as $key) {
            if (!array_key_exists($key, $data)) {
                Setting::set($key, '0');
            }
        }

        Cache::flush();

        return redirect()->back()->with('success', 'Configurações salvas com sucesso!');
    }

    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|max:2048']);

        $path = $request->file('image')->store('settings', 'public');

        return response()->json(['path' => $path, 'url' => asset('media/' . $path)]);
    }
}
