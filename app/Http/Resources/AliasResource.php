<?php

namespace App\Http\Resources;

use App\Models\Alias;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

/** @mixin Alias */
class AliasResource extends JsonResource
{
public function toArray(Request $request): array
{
return [
'id' => $this->id,
'name' => $this->name,
'command' => $this->command,
'description' => $this->description,
'is_global' => $this->is_global,
'created_at' => $this->created_at,
'updated_at' => $this->updated_at,
];
}
}
