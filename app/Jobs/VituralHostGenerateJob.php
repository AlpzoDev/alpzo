<?php

namespace App\Jobs;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Native\Laravel\Notification;

class VituralHostGenerateJob implements ShouldQueue
{
use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

public function __construct(public Project $project)
{
}

public function handle(): void
{
$path = Storage::disk('etc')->path('apache/vhosts/auto.' .$this->project->url . '.conf');
$virtualHost = '
<VirtualHost *:80>
    ServerName ' . $this->project->url . '
    ServerAlias ' . $this->project->url . '
    DocumentRoot "' . $this->project->path . ($this->project->type==='laravel' ? '\\public' : '') . '"
    <Directory "' . $this->project->path . '">
        Options +FollowSymLinks
        AllowOverride All
        Require all granted
        FCGIWrapper "'.Storage::disk('php')->path($this->project->php_version.'\\php-cgi.exe').'" php
    </Directory>
   AddHandler application/x-httpd-php .php
</VirtualHost>';
if ($this->project->is_secure) {
$virtualHost .= '
<VirtualHost *:443>
    ServerName ' . $this->project->url . '
    ServerAlias ' . $this->project->url . '
    DocumentRoot "' . ($this->project->path). ($this->project->type==='laravel' ? '\\public' : '') . '"
    <Directory "' . $this->project->path . '">
        Options +FollowSymLinks
        AllowOverride All
        Require all granted
        FCGIWrapper "'.Storage::disk('php')->path($this->project->php_version.'\\php-cgi.exe').'" php

    </Directory>

    SSLEngine on
    SSLCertificateFile ' . Storage::disk('etc')->path('ssl/certs/'.$this->project->url.'.crt') . '
    SSLCertificateKeyFile ' . Storage::disk('etc')->path('ssl/private/'.$this->project->url.'.key') . '

 </VirtualHost>
';
}
File::put($path, $virtualHost);
Notification::new()->title('Virtual Host Generate')
->message('The virtual host was generated successfully.')->show();
$hostsFile = 'C:\\Windows\\System32\\drivers\\etc\\hosts';
if (!File::exists($hostsFile)) {
File::put($hostsFile, '');
}
if (!Str::contains(File::get($hostsFile), $this->project->url)) {
if (file_put_contents($hostsFile, '127.0.0.1 ' . $this->project->url . "\n", FILE_APPEND | LOCK_EX)) {
Notification::new()->title('Hosts File Update')
->message('The hosts file was updated successfully.')->show();
} else {
Notification::new()->title('Hosts File Update Failed')
->message('The hosts file update failed.')->show();
}
}
}
}
