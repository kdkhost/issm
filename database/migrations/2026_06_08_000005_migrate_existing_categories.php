<?php

use App\Models\TransparencyCategory;
use App\Models\TransparencyDocument;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        // Coletar categorias unicas dos documentos existentes
        $categories = TransparencyDocument::whereNotNull('category')
            ->where('category', '!=', '')
            ->distinct('category')
            ->pluck('category');

        $order = 0;
        foreach ($categories as $catName) {
            $existing = TransparencyCategory::where('name', $catName)->first();
            if (! $existing) {
                $newCat = TransparencyCategory::create([
                    'name' => $catName,
                    'sort_order' => $order,
                    'active' => true,
                ]);
            } else {
                $newCat = $existing;
            }

            // Vincular documentos a esta categoria
            TransparencyDocument::where('category', $catName)
                ->whereNull('category_id')
                ->update(['category_id' => $newCat->id]);

            $order++;
        }
    }

    public function down(): void
    {
        // Nao reversivel sem risco — deixa vazio
    }
};
