<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SttingPathRequest extends FormRequest
{
public function rules(): array
{
return [
'name' => 'required|string |max:255|unique:paths,name',
'description' => 'nullable|string |max:255',
'is_default' => 'required|boolean',
];
}

public function authorize(): bool
{
return true;
}
}
