<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Testimonial;
use App\Models\Setting;
use Illuminate\Http\Request;

class TestimonialController extends Controller
{
    public function index()
    {
        $testimonials = Testimonial::orderBy('order')->paginate(15);
        return view('admin.testimonials.index', compact('testimonials'));
    }

    public function create()
    {
        return view('admin.testimonials.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'role'    => 'nullable|string|max:255',
            'content' => 'required|string',
            'photo'   => 'nullable|image|max:' . Setting::uploadLimitKb('image'),
            'order'   => 'nullable|integer',
            'active'  => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('testimonials', 'public');
        }

        $validated['active'] = $request->boolean('active');
        Testimonial::create($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Depoimento criado com sucesso!');
    }

    public function edit(Testimonial $testimonial)
    {
        return view('admin.testimonials.edit', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $validated = $request->validate([
            'name'    => 'required|string|max:255',
            'role'    => 'nullable|string|max:255',
            'content' => 'required|string',
            'photo'   => 'nullable|image|max:' . Setting::uploadLimitKb('image'),
            'order'   => 'nullable|integer',
            'active'  => 'nullable|boolean',
        ]);

        if ($request->hasFile('photo')) {
            $validated['photo'] = $request->file('photo')->store('testimonials', 'public');
        }

        $validated['active'] = $request->boolean('active');
        $testimonial->update($validated);

        return redirect()->route('admin.testimonials.index')->with('success', 'Depoimento atualizado com sucesso!');
    }

    public function destroy(Testimonial $testimonial)
    {
        $testimonial->delete();
        return redirect()->route('admin.testimonials.index')->with('success', 'Depoimento excluido com sucesso!');
    }

    public function toggleActive(Testimonial $testimonial)
    {
        $testimonial->update(['active' => !$testimonial->active]);
        return response()->json(['active' => $testimonial->active]);
    }
}
