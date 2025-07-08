<?php

namespace App\Enums;

enum ProjectType: string
{
    case PHP = 'php';
    case BLANK = 'blank';
    case LARAVEL = 'laravel';
    case REACT = 'react';
    case VUE = 'vue';
    case ANGULAR = 'angular';
    case SVELTE = 'svelte';

    case VITE = 'vite';

    case Nuxt = 'nuxt';
    case NEXT = 'next';

    public function label(): string
    {
        return match ($this) {
            self::PHP => 'PHP',
            self::BLANK => 'Blank',
            self::LARAVEL => 'Laravel',
            self::REACT => 'React',
            self::VUE => 'Vue',
            self::ANGULAR => 'Angular',
            self::SVELTE => 'Svelte',
            self::Nuxt => 'Nuxt',
            self::VITE => 'Vite',
            self::NEXT => "Next",

        };
    }

    public function createCommand(): string
    {
        return match ($this) {
            self::LARAVEL => 'composer create-project laravel/laravel ',
            self::VITE => 'npx create-vite@latest ',
            self::Nuxt => 'npx create-nuxt-app ',
            self::REACT => 'npx create-react-app ',
            self::VUE => 'npx create-vue@latest ',
            self::ANGULAR => 'npx create-angular@latest ',
            self::SVELTE => 'npx sv create ',
            self::NEXT => 'npx create-next-app ',
            default => throw new \Exception('Unsupported project type'),

        };
    }

    public static function options(): array
    {
        return collect(self::cases())
            ->map(fn($item) => [
                'name' => $item->label(),
                'value' => $item->value,
            ])
            ->toArray();
    }

    public static function validate(): string
    {
        return implode(',', array_column(self::cases(), 'value'));
    }

}
