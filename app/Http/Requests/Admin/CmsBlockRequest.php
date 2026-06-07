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

class CmsBlockRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cms_section_id' => 'nullable|integer|exists:cms_sections,id',
            'type' => 'required|string|max:100',
            'title' => 'nullable|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'nullable|string',
            'image' => 'nullable|string|max:255',
            'video_url' => 'nullable|string|max:500',
            'link_url' => 'nullable|string|max:500',
            'link_text' => 'nullable|string|max:255',
            'link_target' => 'nullable|in:_self,_blank',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'settings' => 'nullable|json',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
        ];
    }

    public function messages(): array
    {
        return [
            'cms_section_id.exists' => 'A seção informada não existe.',
            'type.required' => 'O tipo do bloco é obrigatório.',
            'type.max' => 'O tipo deve ter no máximo 100 caracteres.',
            'title.max' => 'O título deve ter no máximo 255 caracteres.',
            'subtitle.max' => 'O subtítulo deve ter no máximo 255 caracteres.',
            'image.max' => 'A imagem deve ter no máximo 255 caracteres.',
            'video_url.max' => 'A URL do vídeo deve ter no máximo 500 caracteres.',
            'link_url.max' => 'A URL do link deve ter no máximo 500 caracteres.',
            'link_text.max' => 'O texto do link deve ter no máximo 255 caracteres.',
            'link_target.in' => 'O alvo do link deve ser _self ou _blank.',
            'is_active.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',
            'sort_order.integer' => 'A ordem deve ser um número inteiro.',
            'settings.json' => 'As configurações devem ser um JSON válido.',
            'published_at.date' => 'A data de publicação deve ser uma data válida.',
            'expires_at.date' => 'A data de expiração deve ser uma data válida.',
            'expires_at.after' => 'A data de expiração deve ser posterior à data de publicação.',
        ];
    }
}
