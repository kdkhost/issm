<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('project_support_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->string('category', 40)->default('outro')->index();
            $table->text('description')->nullable();
            $table->text('instructions')->nullable();
            $table->json('suggested_amounts')->nullable();
            $table->boolean('requires_amount')->default(false);
            $table->boolean('requires_quantity')->default(false);
            $table->boolean('requires_address')->default(false);
            $table->boolean('requires_document')->default(false);
            $table->boolean('active')->default(true)->index();
            $table->integer('sort_order')->default(0)->index();
            $table->timestamps();
        });

        Schema::create('project_support_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('project_support_type_id')->nullable()->constrained('project_support_types')->nullOnDelete();
            $table->string('supporter_type', 40)->default('pessoa_fisica')->index();
            $table->string('name');
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('document')->nullable();
            $table->string('organization')->nullable();
            $table->string('government_agency')->nullable();
            $table->decimal('amount', 12, 2)->nullable();
            $table->string('currency', 3)->default('BRL');
            $table->text('item_description')->nullable();
            $table->decimal('quantity', 12, 2)->nullable();
            $table->string('unit', 40)->nullable();
            $table->string('address')->nullable();
            $table->text('message')->nullable();
            $table->string('preferred_contact', 40)->nullable();
            $table->string('status', 40)->default('new')->index();
            $table->json('metadata')->nullable();
            $table->ipAddress('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamp('contacted_at')->nullable();
            $table->timestamps();

            $table->index(['project_id', 'status']);
            $table->index(['project_support_type_id', 'status'], 'psr_type_status_idx');
        });

        $this->seedDefaultTypes();
        $this->addAdminMenuItem();
    }

    public function down(): void
    {
        Schema::dropIfExists('project_support_requests');
        Schema::dropIfExists('project_support_types');

        if (Schema::hasTable('admin_menu_items')) {
            $item = DB::table('admin_menu_items')->where('route_name', '#')->where('label', 'Conteúdo')->first();

            if ($item && $item->children) {
                $children = collect(json_decode($item->children, true) ?: [])
                    ->reject(fn ($child) => ($child['route_name'] ?? '') === 'admin.project-supports.index')
                    ->values()
                    ->all();

                DB::table('admin_menu_items')->where('id', $item->id)->update([
                    'children' => json_encode($children, JSON_UNESCAPED_UNICODE),
                    'updated_at' => now(),
                ]);
            }
        }
    }

    private function seedDefaultTypes(): void
    {
        $types = [
            [
                'name' => 'Doação monetária',
                'category' => 'monetario',
                'description' => 'Apoio financeiro para execução direta do projeto.',
                'instructions' => 'Informe o valor pretendido. A equipe entrará em contato para orientar a forma de contribuição.',
                'suggested_amounts' => [50, 100, 250, 500],
                'requires_amount' => true,
                'requires_document' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Insumos e materiais',
                'category' => 'insumos',
                'description' => 'Doação de materiais, equipamentos, alimentos, mudas ou outros insumos úteis.',
                'instructions' => 'Descreva o item, quantidade aproximada e condições de entrega ou retirada.',
                'requires_quantity' => true,
                'requires_address' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Serviços ou voluntariado',
                'category' => 'servicos',
                'description' => 'Apoio com mão de obra, oficinas, transporte, consultoria ou voluntariado.',
                'instructions' => 'Descreva a disponibilidade, área de atuação e melhor forma de contato.',
                'sort_order' => 3,
            ],
            [
                'name' => 'Apoio governamental ou institucional',
                'category' => 'governamental',
                'description' => 'Parcerias, cessão de espaço, apoio logístico, editais ou articulação pública.',
                'instructions' => 'Informe o órgão, setor responsável e tipo de apoio possível.',
                'requires_document' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($types as $type) {
            DB::table('project_support_types')->insert([
                'name' => $type['name'],
                'slug' => Str::slug($type['name']),
                'category' => $type['category'],
                'description' => $type['description'],
                'instructions' => $type['instructions'],
                'suggested_amounts' => isset($type['suggested_amounts']) ? json_encode($type['suggested_amounts']) : null,
                'requires_amount' => $type['requires_amount'] ?? false,
                'requires_quantity' => $type['requires_quantity'] ?? false,
                'requires_address' => $type['requires_address'] ?? false,
                'requires_document' => $type['requires_document'] ?? false,
                'active' => true,
                'sort_order' => $type['sort_order'],
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }
    }

    private function addAdminMenuItem(): void
    {
        if (! Schema::hasTable('admin_menu_items')) {
            return;
        }

        $item = DB::table('admin_menu_items')->where('route_name', '#')->where('label', 'Conteúdo')->first();

        if (! $item) {
            return;
        }

        $children = collect(json_decode($item->children, true) ?: []);

        if ($children->contains(fn ($child) => ($child['route_name'] ?? '') === 'admin.project-supports.index')) {
            return;
        }

        $children = $children
            ->push(['label' => 'Apoios aos Projetos', 'route_name' => 'admin.project-supports.index'])
            ->values()
            ->all();

        DB::table('admin_menu_items')->where('id', $item->id)->update([
            'children' => json_encode($children, JSON_UNESCAPED_UNICODE),
            'updated_at' => now(),
        ]);
    }
};
