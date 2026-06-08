<?php

use App\Models\TransparencyCategory;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        $defaults = ['Financeiro', 'Administrativo', 'Atas', 'Relatórios', 'Estatuto'];
        $order = TransparencyCategory::max('sort_order') ?? -1;

        foreach ($defaults as $catName) {
            $existing = TransparencyCategory::where('name', $catName)->first();
            if (! $existing) {
                TransparencyCategory::create([
                    'name' => $catName,
                    'sort_order' => ++$order,
                    'active' => true,
                ]);
            }
        }
    }

    public function down(): void
    {
        // Irreversivel
    }
};
