<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class NodeVersionRequest extends FormRequest
{
public function rules(): array
{
return [
'version' => ['required', 'string'],
'date' => ['required', 'date'],
'download_url' => ['required', 'array'],
];
}

public function authorize(): bool
{
return true;
}
}
