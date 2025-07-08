<?php

namespace App\Http\Requests\Project;

use App\Enums\ProjectType;
use App\Services\SettingService;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;

class ProjectRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255', 'unique:projects,name'],
            'type' => ['required', 'string', 'max:255', 'in:' . ProjectType::validate()],
            'description' => ['nullable', 'string', 'max:255'],
            'path' => ['required', 'string', 'max:255', 'unique:projects,path'],
            'is_favorite' => ['nullable', 'boolean'],
            'is_secure' => ['nullable', 'boolean'],
            'url' => ['required', 'string', 'max:255', 'ends_with:' . SettingService::getHostName()],
            'extra.*' => ['nullable', 'array'],
            'php_version' => ['required', 'string', 'max:255','in:'.implode(',',Storage::disk('php')->directories())],
            'node_version' => ['required', 'string', 'max:255','in:'.implode(',',Storage::disk('node')->directories())],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
