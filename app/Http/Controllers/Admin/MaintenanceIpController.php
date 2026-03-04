<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MaintenanceIp;
use Illuminate\Http\Request;

class MaintenanceIpController extends Controller
{
    public function index()
    {
        $ips = MaintenanceIp::latest()->paginate(20);
        return view('admin.maintenance-ips.index', compact('ips'));
    }

    public function create()
    {
        return view('admin.maintenance-ips.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'ip_address' => 'required|string|max:45',
            'description' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
        ]);

        $validated['active'] = $request->boolean('active', true);
        MaintenanceIp::create($validated);

        return redirect()->route('admin.ips-manutencao.index')->with('success', 'IP adicionado com sucesso!');
    }

    public function edit(MaintenanceIp $maintenanceIp)
    {
        return view('admin.maintenance-ips.edit', ['ip' => $maintenanceIp]);
    }

    public function update(Request $request, MaintenanceIp $maintenanceIp)
    {
        $validated = $request->validate([
            'ip_address' => 'required|string|max:45',
            'description' => 'nullable|string|max:255',
            'active' => 'nullable|boolean',
        ]);

        $validated['active'] = $request->boolean('active');
        $maintenanceIp->update($validated);

        return redirect()->route('admin.ips-manutencao.index')->with('success', 'IP atualizado com sucesso!');
    }

    public function destroy(MaintenanceIp $maintenanceIp)
    {
        $maintenanceIp->delete();
        return redirect()->route('admin.ips-manutencao.index')->with('success', 'IP removido com sucesso!');
    }

    public function show(MaintenanceIp $maintenanceIp) { return $this->edit($maintenanceIp); }

    public function addCurrentIp(Request $request)
    {
        $ip = $request->ip();
        MaintenanceIp::updateOrCreate(
            ['ip_address' => $ip],
            ['description' => 'Adicionado automaticamente', 'active' => true]
        );
        return redirect()->route('admin.ips-manutencao.index')->with('success', "IP {$ip} adicionado!");
    }
}
