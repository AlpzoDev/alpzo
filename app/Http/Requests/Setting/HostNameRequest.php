<?php

namespace App\Http\Requests\Setting;

use Illuminate\Foundation\Http\FormRequest;

class HostNameRequest extends FormRequest
{
public function rules(): array
{
return [
'hostName' => ['required', 'string', 'max:10'],
];
}

public function authorize(): bool
{
return true;
}
}
