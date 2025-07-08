<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alias extends Model
{
use HasFactory;

protected $fillable = [
'name',
'command',
'description',
'is_global',
];

protected function casts(): array
{
return [
'is_global' => 'boolean',
];
}
}
