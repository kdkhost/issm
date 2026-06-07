<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Ods;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class OdsController extends Controller
{
    public function index()
    {
        $odsList = Ods::orderBy('number')->paginate(20);
        return view('admin.ods.index', compact('odsList'));
    }

    public function edit(Ods $od)
    {
        return view('admin.ods.edit', compact('od'));
    }

    public function update(Request $request, Ods $od)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'color' => 'required|string|max:20',
            'icon' => 'nullable|image|max:' . Setting::uploadLimitKb('image'),
            'active' => 'nullable|boolean',
        ]);

        if ($request->hasFile('icon')) {
            $uploadDir = public_path('media/ods/uploads');
            if (!File::isDirectory($uploadDir)) {
                File::makeDirectory($uploadDir, 0755, true);
            }

            if ($od->icon) {
                $oldMediaPath = public_path('media/' . $od->icon);
                if (File::exists($oldMediaPath)) {
                    File::delete($oldMediaPath);
                }
            }

            $extension = $request->file('icon')->getClientOriginalExtension();
            $filename = Str::random(40) . ($extension ? '.' . strtolower($extension) : '');
            $request->file('icon')->move($uploadDir, $filename);
            $validated['icon'] = 'ods/uploads/' . $filename;
        }

        $validated['active'] = $request->boolean('active');
        $od->update($validated);

        if ($request->expectsJson() || $request->ajax()) {
            return response()->json([
                'message' => 'ODS atualizado com sucesso!',
                'od' => [
                    'id' => $od->id,
                    'number' => $od->number,
                    'title' => $od->title,
                    'description' => $od->description,
                    'color' => $od->color,
                    'icon_url' => $od->icon_url ? $od->icon_url . '?v=' . now()->timestamp : null,
                    'active' => $od->active,
                ],
            ]);
        }

        return redirect()->route('admin.ods.index')->with('success', 'ODS atualizado com sucesso!');
    }

    public function create() { return redirect()->route('admin.ods.index'); }
    public function store(Request $request) { return redirect()->route('admin.ods.index'); }
    public function show(Ods $od) { return $this->edit($od); }
    public function destroy(Ods $od) { return redirect()->route('admin.ods.index'); }
}
