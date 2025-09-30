<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class TemplateImportRequest extends FormRequest
{
    public function authorize(): bool
    {
        return $this->user() !== null; // Basic auth check; policies handled in controller if needed
    }

    public function rules(): array
    {
        return [
            'file' => 'required_without:template_file|file|mimes:json,txt,zip,html,htm|max:10240',
            'template_file' => 'required_without:file|file|mimes:json,txt,zip,html,htm|max:10240',
            'template_name' => 'nullable|string|max:255',
            'activate_after_import' => 'nullable|boolean',
        ];
    }

    public function messages(): array
    {
        return [
            'file.required_without' => 'File template wajib diunggah.',
            'template_file.required_without' => 'File template wajib diunggah.',
            'file.mimes' => 'Format file tidak didukung. Gunakan: JSON, TXT, ZIP, atau HTML.',
            'template_file.mimes' => 'Format file tidak didukung. Gunakan: JSON, TXT, ZIP, atau HTML.',
        ];
    }
}
