<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
         \App\Models\Setting::create([
             'name' => 'global_php_version_path',
             'data' => 'php-8.3.2-Win32-vs16-x64',
         ]);
    }
}
