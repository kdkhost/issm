<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Storage;

class SettingsController extends Controller
{
    public function index()
    {
        $this->ensureSystemSettings();
        $settings = Setting::all()->groupBy('group');
        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $this->ensureSystemSettings();
        $data = $request->except(['_token', '_method']);
        $globalImageLimitKb = Setting::uploadLimitKb('image');

        // Process image removals (fields ending with _remove)
        foreach ($data as $removeKey => $removeValue) {
            if (str_ends_with($removeKey, '_remove') && $removeValue == '1') {
                $settingKey = substr($removeKey, 0, -7); // remove "_remove" suffix
                $oldValue = Setting::get($settingKey);
                if ($oldValue && Storage::disk('public')->exists($oldValue)) {
                    Storage::disk('public')->delete($oldValue);
                }
                $data[$settingKey] = '';
                unset($data[$removeKey]);
            }
        }

        // Upload do arquivo JSON de credenciais do Google Drive
        if ($request->hasFile('google_drive_credentials_file')) {
            $credFile = $request->file('google_drive_credentials_file');
            if ($credFile->isValid() && $credFile->getMimeType() === 'application/json') {
                $credFile->storeAs('google', 'credentials.json', 'local');
            }
        }

        // Process image file uploads (fields ending with _file)
        foreach ($request->allFiles() as $fileKey => $file) {
            if ($fileKey === 'google_drive_credentials_file') {
                continue; // handled separately above
            }
            if (str_ends_with($fileKey, '_file')) {
                $settingKey = substr($fileKey, 0, -5); // remove "_file" suffix
                // Skip upload if remove was requested
                if (isset($data[$settingKey]) && $data[$settingKey] === '') {
                    unset($data[$fileKey]);
                    continue;
                }
                if (($file->getSize() / 1024) > $globalImageLimitKb) {
                    return redirect()->back()
                        ->withInput()
                        ->withErrors([$fileKey => 'A imagem excede o limite global de ' . Setting::uploadLimitMb('image') . 'MB configurado no sistema.']);
                }
                if ($file->isValid()) {
                    // Delete old file if exists
                    $oldValue = Setting::get($settingKey);
                    if ($oldValue && Storage::disk('public')->exists($oldValue)) {
                        Storage::disk('public')->delete($oldValue);
                    }
                    $path = $file->store('settings', 'public');
                    $data[$settingKey] = $path;
                }
                unset($data[$fileKey]); // remove the _file key
            }
        }

        foreach ($data as $key => $value) {
            // Skip _file keys that might remain
            if (str_ends_with($key, '_file')) continue;
            if (in_array($key, ['global_image_max_upload_mb', 'global_video_max_upload_mb'], true)) {
                $value = (string) max(1, min((int) $value, 512));
            }
            if ($key === 'ods_card_image_opacity') {
                $value = (string) max(0, min((int) $value, 100));
            }
            if (in_array($key, ['home_stat_1_base', 'home_stat_3_base'], true)) {
                $value = (string) max(0, min((int) $value, 999999));
            }
            Setting::set($key, $value);
        }

        // Handle boolean checkboxes (unchecked = not submitted)
        $booleanKeys = Setting::where('type', 'boolean')->pluck('key');
        foreach ($booleanKeys as $key) {
            if (!array_key_exists($key, $data)) {
                Setting::set($key, '0');
            }
        }

        Cache::flush();

        return redirect()->back()->with('success', 'Configurações salvas com sucesso!');
    }

    public function uploadImage(Request $request)
    {
        $request->validate(['image' => 'required|image|max:' . Setting::uploadLimitKb('image')]);

        $path = $request->file('image')->store('settings', 'public');

        return response()->json(['path' => $path, 'url' => asset('media/' . $path)]);
    }

    private function ensureSystemSettings(): void
    {
        $legacyImageLimit = Setting::get('ods_image_max_upload_mb');

        $imageSetting = Setting::firstOrCreate(
            ['key' => 'global_image_max_upload_mb'],
            [
                'value' => $legacyImageLimit ?: '5',
                'type' => 'number',
                'group' => 'uploads',
                'label' => 'Limite global de upload para imagens (MB)',
            ]
        );

        $videoSetting = Setting::firstOrCreate(
            ['key' => 'global_video_max_upload_mb'],
            [
                'value' => '50',
                'type' => 'number',
                'group' => 'uploads',
                'label' => 'Limite global de upload para videos (MB)',
            ]
        );

        $odsOpacitySetting = Setting::firstOrCreate(
            ['key' => 'ods_card_image_opacity'],
            [
                'value' => '34',
                'type' => 'number',
                'group' => 'uploads',
                'label' => 'Opacidade da imagem nos cards ODS (%)',
            ]
        );

        $homeStatSettings = [
            ['key' => 'home_stat_1_base', 'value' => '0', 'type' => 'number', 'group' => 'home', 'label' => 'Base da estatística 1 (soma com ODS ativos)'],
            ['key' => 'home_stat_1_suffix', 'value' => '', 'type' => 'text', 'group' => 'home', 'label' => 'Sufixo da estatística 1'],
            ['key' => 'home_stat_1_label', 'value' => 'ODS Alinhados', 'type' => 'text', 'group' => 'home', 'label' => 'Rótulo da estatística 1'],
            ['key' => 'home_stat_2_value', 'value' => '2030', 'type' => 'text', 'group' => 'home', 'label' => 'Valor da estatística 2'],
            ['key' => 'home_stat_2_suffix', 'value' => '', 'type' => 'text', 'group' => 'home', 'label' => 'Sufixo da estatística 2'],
            ['key' => 'home_stat_2_label', 'value' => 'Meta Global', 'type' => 'text', 'group' => 'home', 'label' => 'Rótulo da estatística 2'],
            ['key' => 'home_stat_3_base', 'value' => '10', 'type' => 'number', 'group' => 'home', 'label' => 'Base da estatística 3 (soma com projetos ativos)'],
            ['key' => 'home_stat_3_suffix', 'value' => '+', 'type' => 'text', 'group' => 'home', 'label' => 'Sufixo da estatística 3'],
            ['key' => 'home_stat_3_label', 'value' => 'Projetos Ativos', 'type' => 'text', 'group' => 'home', 'label' => 'Rótulo da estatística 3'],
            ['key' => 'home_stat_4_value', 'value' => 'RJ', 'type' => 'text', 'group' => 'home', 'label' => 'Valor da estatística 4'],
            ['key' => 'home_stat_4_suffix', 'value' => '', 'type' => 'text', 'group' => 'home', 'label' => 'Sufixo da estatística 4'],
            ['key' => 'home_stat_4_label', 'value' => 'Serra do Mendanha', 'type' => 'text', 'group' => 'home', 'label' => 'Rótulo da estatística 4'],
            
            // SEO
            ['key' => 'meta_title', 'value' => 'ISSM - Instituto Socioambiental Serra do Mendanha', 'type' => 'text', 'group' => 'seo', 'label' => 'Título SEO do Site'],
            ['key' => 'meta_description', 'value' => 'Preservação ambiental e desenvolvimento sustentável na Serra do Mendanha.', 'type' => 'textarea', 'group' => 'seo', 'label' => 'Descrição SEO'],
            ['key' => 'meta_keywords', 'value' => 'sustentabilidade, meio ambiente, serra do mendanha, preservação', 'type' => 'text', 'group' => 'seo', 'label' => 'Palavras-chave'],
            
            // Redes Sociais
            ['key' => 'social_linkedin', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'LinkedIn URL'],
            ['key' => 'social_twitter', 'value' => '', 'type' => 'text', 'group' => 'social', 'label' => 'Twitter/X URL'],
            
            // Institucional
            ['key' => 'org_cnpj', 'value' => '', 'type' => 'text', 'group' => 'institucional', 'label' => 'CNPJ'],
            ['key' => 'org_slogan', 'value' => 'Comprometidos com o futuro sustentável.', 'type' => 'text', 'group' => 'institucional', 'label' => 'Slogan da Instituição'],

            // Card ODS na Página Sobre
            ['key' => 'about_ods_title', 'value' => 'ODS 2030', 'type' => 'text', 'group' => 'institucional', 'label' => 'Página Sobre: Título do Card ODS'],
            ['key' => 'about_ods_description', 'value' => 'Nossas ações são norteadas pelos Objetivos de Desenvolvimento Sustentável da ONU para garantir um futuro viável e próspero para a Serra do Mendanha.', 'type' => 'textarea', 'group' => 'institucional', 'label' => 'Página Sobre: Descrição do Card ODS'],
            ['key' => 'about_ods_button_text', 'value' => 'Ver nossos ODS', 'type' => 'text', 'group' => 'institucional', 'label' => 'Página Sobre: Texto do Botão ODS'],

            // Google Drive — Transparência
            ['key' => 'google_drive_enabled', 'value' => '0', 'type' => 'boolean', 'group' => 'google_drive', 'label' => 'Ativar integração com Google Drive'],
            ['key' => 'google_drive_folder_id', 'value' => '', 'type' => 'text', 'group' => 'google_drive', 'label' => 'ID da pasta raiz no Google Drive'],

            // Notificações de contato
            ['key' => 'contact_notification_email_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'contact', 'label' => 'Enviar cópia por e-mail ao receber contato'],
            ['key' => 'contact_notification_to', 'value' => '', 'type' => 'text', 'group' => 'contact', 'label' => 'E-mail do administrador para notificações'],
            ['key' => 'contact_notification_bcc', 'value' => '', 'type' => 'textarea', 'group' => 'contact', 'label' => 'Cópia oculta (BCC) para membros da empresa'],
            ['key' => 'contact_notification_sound_enabled', 'value' => '1', 'type' => 'boolean', 'group' => 'contact', 'label' => 'Ativar alerta sonoro no painel'],
        ];

        foreach ([
            [$imageSetting, 'uploads', 'number', 'Limite global de upload para imagens (MB)', '5'],
            [$videoSetting, 'uploads', 'number', 'Limite global de upload para videos (MB)', '50'],
            [$odsOpacitySetting, 'uploads', 'number', 'Opacidade da imagem nos cards ODS (%)', '34'],
        ] as [$setting, $group, $type, $label, $fallbackValue]) {
            if ($setting->type !== $type || $setting->group !== $group || $setting->label !== $label || !$setting->value) {
                $setting->type = $type;
                $setting->group = $group;
                $setting->label = $label;
                if (!$setting->value) {
                    $setting->value = $fallbackValue;
                }
                $setting->save();
            }
        }

        foreach ($homeStatSettings as $definition) {
            $setting = Setting::firstOrCreate(['key' => $definition['key']], $definition);
            if (
                $setting->type !== $definition['type'] ||
                $setting->group !== $definition['group'] ||
                $setting->label !== $definition['label'] ||
                $setting->value === null ||
                $setting->value === ''
            ) {
                $setting->type = $definition['type'];
                $setting->group = $definition['group'];
                $setting->label = $definition['label'];
                if ($setting->value === null || $setting->value === '') {
                    $setting->value = $definition['value'];
                }
                $setting->save();
            }
        }

        Setting::where('key', 'ods_image_max_upload_mb')->delete();
        Cache::forget('setting_ods_image_max_upload_mb');
    }
}
