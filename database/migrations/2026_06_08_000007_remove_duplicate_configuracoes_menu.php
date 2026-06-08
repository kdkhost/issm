<?php

use App\Models\AdminMenuItem;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    public function up(): void
    {
        AdminMenuItem::where('label', 'Configuracoes')->delete();
    }

    public function down(): void
    {
        // Irreversivel
    }
};
