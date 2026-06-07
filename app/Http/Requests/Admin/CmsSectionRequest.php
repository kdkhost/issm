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

class CmsSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'cms_page_id' => 'required|integer|exists:cms_pages,id',
            'title' => 'required|string|max:255',
            'slug' => 'nullable|string|max:200',
            'description' => 'nullable|string',
            'is_active' => 'nullable|boolean',
            'sort_order' => 'nullable|integer',
            'css_class' => 'nullable|string|max:255',
            'settings' => 'nullable|json',
        ];
    }

    public function messages(): array
    {
        return [
            'cms_page_id.required' => 'A página é obrigatória.',
            'cms_page_id.exists' => 'A página informada não existe.',
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título deve ter no máximo 255 caracteres.',
            'slug.max' => 'O slug deve ter no máximo 200 caracteres.',
            'is_active.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',
            'sort_order.integer' => 'A ordem deve ser um número inteiro.',
            'css_class.max' => 'A classe CSS deve ter no máximo 255 caracteres.',
            'settings.json' => 'As configurações devem ser um JSON válido.',
        ];
    }
}
