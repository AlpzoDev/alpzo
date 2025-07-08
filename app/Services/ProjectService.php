<?php

namespace App\Services;

use App\Jobs\Nginx\ProjectConfJob;
use App\Jobs\Nginx\StartJob;
use App\Jobs\SSL\SSLCertificaGeneratorJob;
use App\Models\Project;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Native\Laravel\Facades\Notification;

class ProjectService
{
    public string $sites = "";

    public function __construct(public ?Project $project = null)
    {
        $this->sites = Storage::disk('etc')->path('sites');
    }

    public function getProject(): ?Project
    {
        return $this->project;
    }

    public function setProject(Project $project): self
    {
        $this->project = $project;
        return $this;
    }

    public function sslCertificateDelete(): self
    {
        if (File::exists(Storage::disk('etc')->path('ssl\\certs\\' . $this->project->url . '.crt'))) {
            File::delete(Storage::disk('etc')->path('ssl\\certs\\' . $this->project->url . '.crt'));
        }

        if (File::exists(Storage::disk('etc')->path('ssl\\private\\' . $this->project->url . '.key'))) {
            File::delete(Storage::disk('etc')->path('ssl\\private\\' . $this->project->url . '.key'));
        }

//        $this->removeSSSLSecure();
        return $this;
    }

    public function sslCertificateCreate(): self
    {
        dispatch_sync(new SSLCertificaGeneratorJob($this->project));
//        $this->addSSSLSecure();
        return $this;
    }

    public function projectDelete(): self
    {
        PHP::stop('nginx');
//        $this->sslCertificateDelete();
        $this->siteDelete();
        $this->removeHost();
        $this->removeConf();
        $this->project->delete();
        dispatch_sync(new StartJob());
        return $this;
    }

    public function projectCreate(): void
    {
//        $this->sslCertificateCreate();
        $this->siteGenerate();
        $this->addHost();
        $this->projectConfCreate();
        PHP::restart('nginx');
    }

    public function projectConfCreate(): self
    {
        dispatch_sync(new ProjectConfJob($this->project));
        return $this;
    }

    public function siteGenerate(): self
    {
        $path = Storage::disk('etc')->path('sites');
        if (!File::exists($path)) {
            File::makeDirectory($path);
        }
        $path = Storage::disk('etc')->path('sites\\' . $this->project->url);
        if (!File::exists($path)) {
            File::makeDirectory($path, 0755, true);
        }
        if (!File::exists($path . '\\logs')) {
            File::makeDirectory($path . '\\logs', 0755, true);
        }
        if (!File::exists($path . '\\logs\\error.log')) {
            File::put($path . '\\logs\\error.log', '');
        }
        if (!File::exists($path . '\\logs\\access.log')) {
            File::put($path . '\\logs\\access.log', '');
        }
        return $this;
    }

    public function siteDelete(): self
    {
        $path = Storage::disk('etc')->path('sites\\' . $this->project->url);
        if (File::exists($path)) {
                Process::run('rmdir /s /q  ' . $path);
        }
        return $this;
    }

    public function addHost(): self
    {
        $hostsFile = 'C:\\Windows\\System32\\drivers\\etc\\hosts';
        if (!File::exists($hostsFile)) {
            File::put($hostsFile, '');
        }
        $hosts = File::get($hostsFile);
        if (!Str::contains($hosts, $this->project->url)) {
            $hosts .= "127.0.0.1\t" . $this->project->url . "\t # Added by Alpzo \n";
            File::put($hostsFile, $hosts);
        }

        return $this;
    }

    public function removeHost(): self
    {
        $hostsFile = 'C:\\Windows\\System32\\drivers\\etc\\hosts';
        $hosts = File::get($hostsFile);
        $hosts = str_replace("127.0.0.1\t" . $this->project->url . "\t # Added by Alpzo \n", '', $hosts);
        File::put($hostsFile, $hosts);
        return $this;
    }

    public function removeConf(): void
    {
        $path = Storage::disk('etc')->path('nginx\\sites-enabled\\' . $this->project->url . '.conf');
        if (File::exists($path)) {
            File::delete($path);
            Notification::new()->title('Delete Success')->message('nginx configuration delete was successful.')->show();
        }
    }

    public function checkProjectConf(): bool
    {
        return File::exists(Storage::disk('etc')->path('nginx\\sites-enabled\\' . $this->project->url . '.conf'));
    }

    public function addSSSLSecure(): self
    {
        if (!$this->checkSSSLSecure()) {
            Process::run([
                'certutil',
                '-addstore',
                'root',
                '"' . Storage::disk('etc')->path('ssl\\certs\\' . $this->project->url . '.crt') . '"',

            ]);
        }

        return $this;
    }

    public function removeSSSLSecure(): self
    {
        if ($this->checkSSSLSecure()) {
            Process::run([
                'certutil',
                '-delstore',
                'root',
                Storage::disk('etc')->path('ssl\\certs\\' . $this->project->url . '.crt')
            ]);

        }
        return $this;
    }

    public function checkSSSLSecure(): bool
    {

        $certPath = Storage::disk('etc')->path('ssl\\certs\\' . $this->project->url . '.crt');


        $process = Process::run([
            'certutil',
            '-verify',
            $certPath
        ]);

        if ($process->failed()) {

            return false;
        }


        preg_match('/\b[0-9A-F]{40}\b/', $process->output(), $matches);
        if (empty($matches)) {
            return false;
        }

        $thumbprint = $matches[0];


        $checkProcess = Process::run([
            'certutil',
            '-store',
            'root'
        ]);

        if ($checkProcess->failed()) {
            throw new \Exception("Kök depo sorgulanamadı: " . $checkProcess->errorOutput());
        }
        return str_contains($checkProcess->output(), $thumbprint);
    }


}
