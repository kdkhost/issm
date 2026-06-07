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

class CmsMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        if ($this->isMethod('POST')) {
            return [
                'file' => 'required|file|max:10240',
                'title' => 'required|string|max:255',
                'alt_text' => 'nullable|string|max:255',
                'caption' => 'nullable|string|max:500',
                'credit' => 'nullable|string|max:255',
                'description' => 'nullable|string',
            ];
        }

        return [
            'title' => 'nullable|string|max:255',
            'alt_text' => 'nullable|string|max:255',
            'caption' => 'nullable|string|max:500',
            'credit' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required' => 'O arquivo é obrigatório.',
            'file.file' => 'O envio deve ser um arquivo válido.',
            'file.max' => 'O arquivo deve ter no máximo 10MB.',
            'title.required' => 'O título é obrigatório.',
            'title.max' => 'O título deve ter no máximo 255 caracteres.',
            'alt_text.max' => 'O texto alternativo deve ter no máximo 255 caracteres.',
            'caption.max' => 'A legenda deve ter no máximo 500 caracteres.',
            'credit.max' => 'O crédito deve ter no máximo 255 caracteres.',
        ];
    }
}
