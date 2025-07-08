<?php

namespace App\Models;

use App\Enums\ProjectType;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Project extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'path',
        'url',
        'php_version',
        'node_version',
        'type',
        'is_secure',
        'is_favorite',
        'type',
        'server',
    ];

    protected function casts(): array
    {
        return [
            'is_secure' => 'boolean',
            'is_favorite' => 'boolean',
            'type' => ProjectType::class
        ];
    }
}
