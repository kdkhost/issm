<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use Illuminate\Http\Request;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::latest()->paginate(20);
        return view('admin.contatos.index', compact('contacts'));
    }

    public function show(Contact $contato)
    {
        if ($contato->status === 'new') {
            $contato->update(['status' => 'read']);
        }
        return view('admin.contatos.show', ['contact' => $contato]);
    }

    public function update(Request $request, Contact $contato)
    {
        $validated = $request->validate([
            'status' => 'required|in:new,read,replied',
            'reply' => 'nullable|string',
        ]);

        if (!empty($validated['reply'])) {
            $validated['replied_at'] = now();
            $validated['status'] = 'replied';
        }

        $contato->update($validated);

        return redirect()->route('admin.contatos.show', $contato)->with('success', 'Contato atualizado!');
    }

    public function destroy(Contact $contato)
    {
        $contato->delete();
        return redirect()->route('admin.contatos.index')->with('success', 'Mensagem excluida!');
    }

    public function create() { return redirect()->route('admin.contatos.index'); }
    public function store(Request $request) { return redirect()->route('admin.contatos.index'); }
    public function edit(Contact $contato) { return $this->show($contato); }
}
