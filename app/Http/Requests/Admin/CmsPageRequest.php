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
use Illuminate\Support\Facades\Auth;

class CmsPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        if ($this->isMethod('POST')) {
            return Auth::user()->can('cms.pages.create');
        }

        return Auth::user()->can('cms.pages.edit');
    }

    public function rules(): array
    {
        $pageId = $this->route('cms_page')?->id ?? $this->route('page')?->id;

        $rules = [
            'title' => 'required|string|max:255',
            'slug' => 'required|string|max:200|unique:cms_pages,slug' . ($pageId ? ",{$pageId}" : ''),
            'content' => 'nullable|string',
            'status' => 'required|in:draft,published,archived',
            'is_active' => 'nullable|boolean',
            'published_at' => 'nullable|date',
            'expires_at' => 'nullable|date|after:published_at',
            'template' => 'nullable|string|max:100',
            'layout' => 'nullable|string|max:100',
            'css_class' => 'nullable|string|max:255',
        ];

        return $rules;
    }

    public function messages(): array
    {
        return [
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título deve ter no máximo 255 caracteres.',
            'slug.required' => 'O slug é obrigatório.',
            'slug.max' => 'O slug deve ter no máximo 200 caracteres.',
            'slug.unique' => 'Este slug já está em uso.',
            'status.required' => 'O status é obrigatório.',
            'status.in' => 'O status deve ser draft, published ou archived.',
            'is_active.boolean' => 'O campo ativo deve ser verdadeiro ou falso.',
            'published_at.date' => 'A data de publicação deve ser uma data válida.',
            'expires_at.date' => 'A data de expiração deve ser uma data válida.',
            'expires_at.after' => 'A data de expiração deve ser posterior à data de publicação.',
            'template.max' => 'O template deve ter no máximo 100 caracteres.',
            'layout.max' => 'O layout deve ter no máximo 100 caracteres.',
            'css_class.max' => 'A classe CSS deve ter no máximo 255 caracteres.',
        ];
    }
}
