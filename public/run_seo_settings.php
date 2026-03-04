<?php
/**
 * Script para inserir/atualizar campos SEO completos no banco.
 * Acesse: https://issm.org.br/run_seo_settings.php
 * APAGUE este arquivo após executar.
 */

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use App\Models\Setting;

$settings = [
    // ── SEO Básico ──
    ['key' => 'meta_title', 'value' => 'ISSM - Instituto Socioambiental Serra do Mendanha', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Title'],
    ['key' => 'meta_description', 'value' => 'O Instituto Socioambiental Serra do Mendanha (ISSM) atua na preservacao ambiental, educacao ecologica e desenvolvimento sustentavel alinhado com os ODS 2030, na regiao da Serra do Mendanha, Rio de Janeiro.', 'type' => 'textarea', 'group' => 'seo', 'label' => 'Meta Description'],
    ['key' => 'meta_keywords', 'value' => 'ISSM, Instituto Socioambiental, Serra do Mendanha, meio ambiente, sustentabilidade, ODS 2030, preservacao ambiental, educacao ecologica, Rio de Janeiro, desenvolvimento sustentavel, conservacao, natureza', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Keywords'],
    ['key' => 'meta_author', 'value' => 'ISSM - Instituto Socioambiental Serra do Mendanha', 'type' => 'text', 'group' => 'seo', 'label' => 'Meta Author'],
    ['key' => 'canonical_url', 'value' => 'https://issm.org.br', 'type' => 'text', 'group' => 'seo', 'label' => 'URL Canonica'],
    ['key' => 'robots_meta', 'value' => 'index, follow', 'type' => 'text', 'group' => 'seo', 'label' => 'Robots (Meta Tag)'],

    // ── Open Graph (Facebook / WhatsApp / LinkedIn) ──
    ['key' => 'og_title', 'value' => 'ISSM - Instituto Socioambiental Serra do Mendanha', 'type' => 'text', 'group' => 'seo', 'label' => 'OG Title (Redes Sociais)'],
    ['key' => 'og_description', 'value' => 'Preservacao ambiental e desenvolvimento sustentavel alinhado com os ODS 2030 na Serra do Mendanha, Rio de Janeiro.', 'type' => 'textarea', 'group' => 'seo', 'label' => 'OG Description'],
    ['key' => 'og_image', 'value' => '', 'type' => 'image', 'group' => 'seo', 'label' => 'OG Image (1200x630px)'],
    ['key' => 'og_type', 'value' => 'website', 'type' => 'text', 'group' => 'seo', 'label' => 'OG Type'],
    ['key' => 'og_locale', 'value' => 'pt_BR', 'type' => 'text', 'group' => 'seo', 'label' => 'OG Locale'],
    ['key' => 'og_site_name', 'value' => 'ISSM - Instituto Socioambiental Serra do Mendanha', 'type' => 'text', 'group' => 'seo', 'label' => 'OG Site Name'],

    // ── Twitter Card ──
    ['key' => 'twitter_card', 'value' => 'summary_large_image', 'type' => 'text', 'group' => 'seo', 'label' => 'Twitter Card Type'],
    ['key' => 'twitter_title', 'value' => 'ISSM - Instituto Socioambiental Serra do Mendanha', 'type' => 'text', 'group' => 'seo', 'label' => 'Twitter Title'],
    ['key' => 'twitter_description', 'value' => 'Preservacao ambiental e desenvolvimento sustentavel alinhado com os ODS 2030 na Serra do Mendanha, RJ.', 'type' => 'textarea', 'group' => 'seo', 'label' => 'Twitter Description'],
    ['key' => 'twitter_image', 'value' => '', 'type' => 'image', 'group' => 'seo', 'label' => 'Twitter Image (1200x600px)'],
    ['key' => 'twitter_handle', 'value' => '', 'type' => 'text', 'group' => 'seo', 'label' => 'Twitter @handle'],

    // ── Verificação e Analytics ──
    ['key' => 'google_analytics', 'value' => '', 'type' => 'text', 'group' => 'seo', 'label' => 'Google Analytics ID'],
    ['key' => 'google_tag_manager', 'value' => '', 'type' => 'text', 'group' => 'seo', 'label' => 'Google Tag Manager ID'],
    ['key' => 'google_site_verification', 'value' => '', 'type' => 'text', 'group' => 'seo', 'label' => 'Google Search Console (Verificacao)'],
    ['key' => 'bing_site_verification', 'value' => '', 'type' => 'text', 'group' => 'seo', 'label' => 'Bing Webmaster (Verificacao)'],
];

$created = 0;
$updated = 0;

foreach ($settings as $s) {
    $existing = Setting::where('key', $s['key'])->first();
    if ($existing) {
        // Atualiza type, group e label, mas preserva o value existente
        $existing->update([
            'type'  => $s['type'],
            'group' => $s['group'],
            'label' => $s['label'],
        ]);
        $updated++;
    } else {
        Setting::create($s);
        $created++;
    }
}

// Limpar cache
\Illuminate\Support\Facades\Cache::flush();

echo "<!DOCTYPE html><html><head><meta charset='utf-8'><title>SEO Settings</title>";
echo "<style>body{font-family:Inter,sans-serif;background:#14532d;color:#fff;display:flex;align-items:center;justify-content:center;min-height:100vh;margin:0}";
echo ".card{background:#1f2937;border-radius:1rem;padding:2rem;max-width:500px;width:100%;box-shadow:0 8px 32px rgba(0,0,0,.3)}";
echo "h1{color:#4ade80;margin:0 0 1rem;font-size:1.5rem}p{margin:.5rem 0;color:#d1d5db}";
echo ".n{color:#4ade80;font-weight:700;font-size:1.25rem}.warn{color:#fbbf24;font-size:.875rem;margin-top:1.5rem;padding:1rem;background:rgba(251,191,36,.1);border-radius:.5rem;border:1px solid rgba(251,191,36,.3)}</style></head><body>";
echo "<div class='card'>";
echo "<h1>✅ SEO Settings Configurados!</h1>";
echo "<p>Criados: <span class='n'>{$created}</span></p>";
echo "<p>Atualizados: <span class='n'>{$updated}</span></p>";
echo "<p>Total: <span class='n'>" . count($settings) . "</span> configurações SEO</p>";
echo "<div class='warn'>⚠️ <strong>APAGUE este arquivo imediatamente!</strong><br>Acesse o painel admin → Configurações → SEO para gerenciar.</div>";
echo "</div></body></html>";
