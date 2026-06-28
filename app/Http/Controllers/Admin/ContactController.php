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

    public function notifications()
    {
        $contacts = Contact::new()
            ->latest()
            ->take(5)
            ->get(['id', 'name', 'email', 'subject', 'created_at']);

        return response()->json([
            'count' => Contact::new()->count(),
            'sound_enabled' => \App\Models\Setting::get('contact_notification_sound_enabled', '1') === '1',
            'items' => $contacts->map(fn (Contact $contact) => [
                'id' => $contact->id,
                'name' => $contact->name,
                'subject' => $contact->subject,
                'email' => $contact->email,
                'time' => optional($contact->created_at)->format('d/m/Y H:i'),
                'url' => route('admin.contatos.show', $contact),
            ])->values(),
        ]);
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
        return redirect()->route('admin.contatos.index')->with('success', 'Mensagem excluída!');
    }

    public function create() { return redirect()->route('admin.contatos.index'); }
    public function store(Request $request) { return redirect()->route('admin.contatos.index'); }
    public function edit(Contact $contato) { return $this->show($contato); }
}
