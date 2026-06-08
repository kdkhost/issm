<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCmsPublicPageRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null && $this->user()->is_admin;
    }

    public function rules(): array
    {
        return [
            'fields' => 'nullable|array',
            'fields.*' => 'nullable|string|max:65535',
            'hero' => 'nullable|array',
            'hero.*' => 'nullable|string|max:65535',
        ];
    }
}
