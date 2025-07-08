<?php

namespace App\Jobs\Nginx;

use App\Models\Project;
use App\Services\PHP;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ProjectConfJob
{
use Dispatchable, InteractsWithQueue, SerializesModels;

public function __construct(public Project $project)
{
}

public function handle(): void
{
$project = $this->project;
$nginxSitesPath = Storage::disk('etc')->path('nginx/sites-enabled');
$projectConfPath = Storage::disk('etc')->path('nginx/sites-enabled/' . $project->url . '.conf');
$sitesPath = Str::replace('\\', '/', Storage::disk('etc')->path('sites\\' . $project->url));
$projectPath= Str::replace('\\', '/', $project->path) ;
$projectPath .= File::exists($project->path . '\\artisan') ? '/public' : '';

if (!Storage::exists($nginxSitesPath)) {
Storage::makeDirectory($nginxSitesPath);
}

$conf = 'server {
    listen 80;
    server_name ' . $project->url . ';';
if ($project->is_secure) {
$conf .= '
    return 302 https://' . $project->url . '$request_uri;
    ';
} else {
$conf .= '
  error_log ' . $sitesPath . '/logs/error.log;
  access_log ' . $sitesPath . '/logs/access.log;
    root ' . $projectPath . ';
    index index.html index.htm index.php;
   location / {
        try_files $uri /index.php$is_args$args;
	}

    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:' . PHP::getPort($project->php_version) . ';
          fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
';

}
$conf .= '}';

if ($project->is_secure) {
$conf .= '

server {
  listen 443 ssl;
  server_name ' . $project->url . ';
  root ' . $projectPath . ';
  index index.html index.htm index.php;

  ssl_certificate ' .Str::replace('\\', '/', Storage::disk('etc')->path('ssl\\certs\\' . $project->url . '.crt')) . ';
  ssl_certificate_key ' . Str::replace('\\', '/', Storage::disk('etc')->path('ssl\\private\\' . $project->url . '.key')) . ';

  error_log ' . $sitesPath . '/logs/error.log;
  access_log ' . $sitesPath . '/logs/access.log;

  location / {
        try_files $uri /index.php$is_args$args;
  }
  location ~ \.php$ {
    fastcgi_pass 127.0.0.1:' . PHP::getPort($project->php_version) . ';
     fastcgi_index index.php;
     fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
     include fastcgi_params;
  }
}';
}

File::put($projectConfPath, $conf);
}
}
