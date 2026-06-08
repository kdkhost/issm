<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminMenuItem;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function index()
    {
        $items = AdminMenuItem::getOrdered();
        return view('admin.menu.index', compact('items'));
    }

    public function updateOrder(Request $request)
    {
        $request->validate([
            'order' => 'required|array',
            'order.*.id' => 'required|integer|exists:admin_menu_items,id',
            'order.*.sort_order' => 'required|integer',
        ]);

        foreach ($request->input('order') as $item) {
            AdminMenuItem::where('id', $item['id'])->update(['sort_order' => $item['sort_order']]);
        }

        return response()->json(['success' => true, 'message' => 'Ordem atualizada com sucesso!']);
    }
}
