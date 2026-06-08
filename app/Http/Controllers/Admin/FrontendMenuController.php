<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FrontendMenuItem;
use Illuminate\Http\Request;

class FrontendMenuController extends Controller
{
    public function index()
    {
        $items = FrontendMenuItem::getOrdered();
        return view('admin.frontend-menu.index', compact('items'));
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:frontend_menu_items,id',
            'order.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->input('order') as $item) {
            FrontendMenuItem::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Ordem atualizada com sucesso!']);
    }
}
