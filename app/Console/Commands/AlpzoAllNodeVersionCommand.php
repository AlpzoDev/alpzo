<?php
declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class AlpzoAllNodeVersionCommand extends Command
{
    protected $signature = 'alpzo:all-node-version';

    protected $description = 'Command description';

    public function handle(): void
    {
        $url = config('alpzo.downloads.nodejs.url');
        $response = Http::get($url);
        if ($response->ok()) {
            $data = $response->json();
            $data = collect($data)->map(function ($item) {
                return array_merge($item, [
                    'download_url' => [
                        'windows' => [
                            'x64' => 'https://nodejs.org/download/release/' . $item['version'] . '/node-' . $item['version'] . '-win-x64.zip',
                            'x86' => 'https://nodejs.org/download/release/' . $item['version'] . '/node-' . $item['version'] . '-win-x86.zip',
                        ]
                    ]
                ]);
            })->toJson();
            Storage::put('alpzo/node/node-all-version.json', $data);
        }
    }
}
