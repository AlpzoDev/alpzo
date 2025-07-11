<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NewPHPVersionRequest extends FormRequest
{
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'version' => ['required', 'string', 'max:255'],
            'date' => ['required', 'string'],
        ];
    }

    public function authorize(): bool
    {
        return true;
    }
}
