<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class PathUpdateRequest extends FormRequest
{
public function rules(): array
{
return [
'name' => 'required|string|max:255',
'description' => 'nullable|string |max:255'
];
}

public function authorize(): bool
{
return true;
}
}
