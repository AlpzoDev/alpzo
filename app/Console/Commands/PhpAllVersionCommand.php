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

        try {
            $response = Http::timeout(30)->get($url);

            if ($response->ok()) {
                $html = $response->body();

                // HTML'i temizle
                $cleanHtml = $this->cleanHtml($html);

                // Satırlara böl ve işle
                $data = collect(explode("\n", $cleanHtml))
                    ->filter()
                    ->map(function ($line) {
                        return $this->parsePhpVersion($line);
                    })
                    ->filter()
                    ->values()
                    ->sortByDesc('version') // En yeni sürüm önce
                    ->values()
                    ->toArray();

                if (!empty($data)) {
                    $this->info("Toplam " . count($data) . " PHP sürümü bulundu.");

                    // JSON olarak kaydet
                    $jsonData = json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
                    Storage::put('alpzo/php/php-all-version.json', $jsonData);

                    Notification::new()
                        ->title('PHP Sürümleri Güncellendi')
                        ->message(count($data) . ' sürüm başarıyla kaydedildi');
                } else {
                    $this->error('Hiçbir PHP sürümü bulunamadı!');
                }

            } else {
                $this->error('HTTP Hatası: ' . $response->status());
                $this->showErrorNotification('HTTP Hatası: ' . $response->status());
            }

        } catch (\Exception $e) {
            $this->error('Hata: ' . $e->getMessage());
            $this->showErrorNotification('Bağlantı hatası: ' . $e->getMessage());
        }
    }

    private function cleanHtml(string $html): string
    {
        // Gereksiz HTML etiketlerini temizle
        $tagsToRemove = [
            '<html>', '</html>', '<body>', '</body>', '<head>', '</head>',
            '<title>windows.php.net - /downloads/releases/archives/</title>',
            '<pre>', '</pre>', '<hr>',
            '<A href="/downloads/releases/">[To Parent Directory]</A><br><br>',
            '<H1>windows.php.net - /downloads/releases/archives/</H1>'
        ];

        $cleaned = str_replace($tagsToRemove, '', $html);

        // <br> etiketlerini \n ile değiştir
        $cleaned = str_replace('<br>', "\n", $cleaned);

        return $cleaned;
    }

    private function parsePhpVersion(string $line): ?array
    {
        // Debug ve pack dosyalarını atla
        if (Str::contains($line, ['debug', 'pack', 'devel'])) {
            return null;
        }

        // Düzeltilmiş regex pattern
        $pattern = '/(\d{1,2}\/\d{1,2}\/\d{4}\s+\d{1,2}:\d{2}\s+(?:AM|PM))\s+\d+\s+<A\s+HREF="([^"]*php-([\d.]+)-nts-Win32-([^"]*\.zip))">/i';

        if (preg_match($pattern, $line, $matches)) {
            $version = $matches[3];
            $architecture = $matches[4];

            // Sadece PHP 7.0.0 ve üzeri sürümleri al
            if (version_compare($version, '7.0.0', '>=')) {

                // Dosya adını düzelt
                $filename = sprintf('php-%s-nts-win32-%s', $version, $architecture);
                $filename = str_replace('.zip', '', $filename);
                $filename = str_replace('php-', '', $filename);

                return [
                    'date' => $matches[1],
                    'version' => $version,
                    'name' => $filename,
                    'architecture' => $architecture,
                    'size' => $this->extractFileSize($line)
                ];
            }
        }

        return null;
    }

    private function extractFileSize(string $line): ?string
    {
        // Dosya boyutunu çıkar
        if (preg_match('/(\d+)\s+<A\s+HREF=/', $line, $matches)) {
            $bytes = (int)$matches[1];
            return $this->formatBytes($bytes);
        }

        return null;
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, 2) . ' ' . $units[$pow];
    }

    private function showErrorNotification(string $message): void
    {
        Notification::new()
            ->title('PHP Sürümleri Hatası')
            ->message($message);
    }
}
