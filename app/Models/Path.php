<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Path extends Model
{
protected $fillable = [
'name',
'path',
'is_default',
'description',
];
protected function casts(): array
{
return [
'is_default' => 'boolean',
];
}
}
