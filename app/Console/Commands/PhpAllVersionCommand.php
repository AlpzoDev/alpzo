<?php
declare(strict_types=1);

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Native\Laravel\Notification;

class PhpAllVersionCommand extends Command
{
    protected $signature = 'alpzo:php-all-version';

    protected $description = 'Fetch and store all PHP versions (NTS only)';

    public function handle(): void
    {
        $url = config('alpzo.downloads.php.url');
        $response = Http::get($url);

        if ($response->ok()) {
            $response = $response->body();
            $data = collect(Str::of(Str::replace([
                '<html>', '</html>', '<body>', '</body>', '<head>', '</head>',
                '<title>windows.php.net - /downloads/releases/archives/</title>', '<pre>', '</pre>', '<hr>',
                '<A href="/downloads/releases/">[To Parent Directory]</A><br><br>',
                '<H1>windows.php.net - /downloads/releases/archives/</H1>'
            ], '', $response))
                ->explode("<br>"))
                ->filter()
                ->map(function ($item) use ($url) {
                    if (
                        !Str::contains($item, 'pack') &&
                        preg_match('/(\d{2}\/\d{2}\/\d{4}\s+\d{1,2}:\d{2}\s+(?:AM|PM))\s+\d+\s+<A HREF="([^"]+\.zip)">php-([\d.]+)-nts-Win32-([^<]+\.zip)<\/A>/', $item, $matches)
                    ) {
                        $name = Str::replace('.zip', '', $matches[3] . '-nts-win32-' . $matches[4]);
                        if (version_compare($matches[3], '7.0.0', '>=')) {
                            return [
                                'date' => $matches[1],
                                'version' => $matches[3],
                                'name' => $name,
                                'url' => $url . 'php-' . $name . '.zip',
                            ];
                        }
                    }
                    return null;
                })
                ->filter()
                ->values()
                ->toArray();

            $data = json_encode(array_values($data), JSON_PRETTY_PRINT);
            Storage::put('alpzo/php/php-all-version.json', $data);
        } else {
            Notification::new()->title('PHP Versions Fetch Failed')->message('No Internet Connection');
        }
    }
}
