<?php

namespace App\Http\Requests\Admin;

/**
 * @autor marcelo-brad rj
 * @contato Tel: 21 981325441
 * Email: contato@kdkhost.com.br
 * Telegram: @MARCELO_BRAD
 * Instagram: @marcelobradrj
 * WhatsApp: 21981325441
 */

use Illuminate\Foundation\Http\FormRequest;

class CmsSeoRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'meta_title' => 'nullable|string|max:255',
            'meta_description' => 'nullable|string|max:500',
            'meta_keywords' => 'nullable|string|max:255',
            'canonical_url' => 'nullable|url',
            'og_title' => 'nullable|string|max:255',
            'og_description' => 'nullable|string|max:500',
            'og_image' => 'nullable|string|max:255',
            'og_type' => 'nullable|string|max:50',
            'robots_index' => 'nullable|boolean',
            'robots_follow' => 'nullable|boolean',
            'sitemap_priority' => 'nullable|numeric|min:0|max:1',
            'sitemap_frequency' => 'nullable|in:always,hourly,daily,weekly,monthly,yearly,never',
            'sitemap_enabled' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'meta_title.max' => 'O meta título deve ter no máximo 255 caracteres.',
            'meta_description.max' => 'A meta descrição deve ter no máximo 500 caracteres.',
            'meta_keywords.max' => 'As meta palavras-chave devem ter no máximo 255 caracteres.',
            'canonical_url.url' => 'A URL canônica deve ser uma URL válida.',
            'og_title.max' => 'O título OG deve ter no máximo 255 caracteres.',
            'og_description.max' => 'A descrição OG deve ter no máximo 500 caracteres.',
            'og_image.max' => 'A imagem OG deve ter no máximo 255 caracteres.',
            'og_type.max' => 'O tipo OG deve ter no máximo 50 caracteres.',
            'robots_index.boolean' => 'O campo robots index deve ser verdadeiro ou falso.',
            'robots_follow.boolean' => 'O campo robots follow deve ser verdadeiro ou falso.',
            'sitemap_priority.numeric' => 'A prioridade do sitemap deve ser um número.',
            'sitemap_priority.min' => 'A prioridade do sitemap deve ser no mínimo 0.',
            'sitemap_priority.max' => 'A prioridade do sitemap deve ser no máximo 1.',
            'sitemap_frequency.in' => 'A frequência do sitemap é inválida.',
            'sitemap_enabled.boolean' => 'O campo sitemap habilitado deve ser verdadeiro ou falso.',
        ];
    }
}
