<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cms_original_pages', function (Blueprint $table) {
            $table->id();
            $table->string('route_name')->nullable()->index();
            $table->string('route_uri')->nullable();
            $table->string('controller')->nullable();
            $table->string('method')->default('index');
            $table->string('view_path')->nullable();
            $table->string('page_key')->unique();
            $table->string('title');
            $table->string('admin_label');
            $table->boolean('is_editable')->default(true);
            $table->boolean('is_active')->default(true);
            $table->integer('sort_order')->default(0);
            $table->boolean('seo_enabled')->default(true);
            $table->boolean('cache_enabled')->default(true);
            $table->boolean('needs_review')->default(false);
            $table->timestamp('last_mapped_at')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });

        Schema::create('cms_original_page_sections', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('cms_original_pages')->cascadeOnDelete();
            $table->string('section_key');
            $table->string('section_label');
            $table->integer('sort_order')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
            $table->unique(['page_id', 'section_key']);
        });

        Schema::create('cms_original_page_fields', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('cms_original_pages')->cascadeOnDelete();
            $table->string('section_key');
            $table->string('field_key');
            $table->string('field_type')->default('text');
            $table->string('field_label');
            $table->text('field_value')->nullable();
            $table->text('default_value')->nullable();
            $table->boolean('is_required')->default(false);
            $table->boolean('is_editable')->default(true);
            $table->integer('sort_order')->default(0);
            $table->string('validation_rules')->nullable();
            $table->text('help_text')->nullable();
            $table->boolean('is_synced')->default(false);
            $table->timestamps();
            $table->unique(['page_id', 'section_key', 'field_key']);
        });

        Schema::create('cms_original_page_media', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('cms_original_pages')->cascadeOnDelete();
            $table->string('section_key');
            $table->string('media_key');
            $table->string('media_url');
            $table->string('media_alt')->nullable();
            $table->integer('sort_order')->default(0);
            $table->timestamps();
            $table->unique(['page_id', 'section_key', 'media_key']);
        });

        Schema::create('cms_original_page_seo', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->unique()->constrained('cms_original_pages')->cascadeOnDelete();
            $table->string('meta_title')->nullable();
            $table->text('meta_description')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('og_title')->nullable();
            $table->text('og_description')->nullable();
            $table->string('og_image')->nullable();
            $table->string('canonical_url')->nullable();
            $table->string('robots_meta')->nullable();
            $table->timestamps();
        });

        Schema::create('cms_original_page_versions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->constrained('cms_original_pages')->cascadeOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->json('snapshot');
            $table->string('change_summary');
            $table->timestamps();
        });

        Schema::create('cms_original_page_audit_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('page_id')->nullable()->constrained('cms_original_pages')->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
            $table->string('action');
            $table->string('entity_type')->nullable();
            $table->unsignedBigInteger('entity_id')->nullable();
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->string('ip_address')->nullable();
            $table->text('user_agent')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cms_original_page_audit_logs');
        Schema::dropIfExists('cms_original_page_versions');
        Schema::dropIfExists('cms_original_page_seo');
        Schema::dropIfExists('cms_original_page_media');
        Schema::dropIfExists('cms_original_page_fields');
        Schema::dropIfExists('cms_original_page_sections');
        Schema::dropIfExists('cms_original_pages');
    }
};
