<?php

namespace App\Http\Requests\Project;

use App\Models\Path;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use function Pest\Support\Str;

class ProjectUpdateRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'type' => ['required', 'string', 'max:255'],
            'url' => ['required', 'string', 'max:255'],
            'is_secure' => ['nullable', 'boolean'],
            'is_favorite' => ['nullable', 'boolean'],
            'path' => ['required', 'string', 'max:255', function ($attribute, $value, $fail) {
                $paths = Path::all('path')->toArray();
                foreach ($paths as $item) {
                    if (Str::startsWith($value, $item)) {
                        return true;
                    }
                }
                return $fail(function () {
                    return 'The path is not valid.';
                });
            }],
            'php_version' => ['required', 'string', 'max:255','in:'.implode(',',Storage::disk('php')->directories())],
            'node_version' => ['required', 'string', 'max:255','in:'.implode(',',Storage::disk('node')->directories())],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
