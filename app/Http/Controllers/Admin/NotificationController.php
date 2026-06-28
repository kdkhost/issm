<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ProjectSupportRequest;
use App\Services\Admin\AdminNotificationCenter;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    public function index(AdminNotificationCenter $notificationCenter)
    {
        $notifications = collect($notificationCenter->latest(50));
        
        return view('admin.notifications.index', compact('notifications'));
    }

    public function markAsRead(Request $request)
    {
        $type = $request->input('type');
        $id = $request->input('id');

        switch ($type) {
            case 'contact':
                $contact = Contact::find($id);
                if ($contact && $contact->status === 'new') {
                    $contact->update(['status' => 'read']);
                }
                break;
            case 'support':
                $support = ProjectSupportRequest::find($id);
                if ($support && $support->status === 'new') {
                    $support->update(['status' => 'read']);
                }
                break;
            case 'payment':
                // Pagamentos não têm status de leitura, apenas contagem por tempo
                break;
        }

        return response()->json(['success' => true]);
    }

    public function markAllAsRead()
    {
        Contact::new()->update(['status' => 'read']);
        ProjectSupportRequest::new()->update(['status' => 'read']);

        return redirect()->route('admin.notifications.index')->with('success', 'Todas as notificações foram marcadas como lidas.');
    }
}
