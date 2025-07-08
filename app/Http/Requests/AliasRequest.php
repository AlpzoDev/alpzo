<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class AliasRequest extends FormRequest
{
public function rules(): array
{
return [
'name' => ['required'],
'command' => ['required'],
'description' => ['nullable'],
'is_global' => ['boolean'],
];
}

public function authorize(): bool
{
return true;
}
}
