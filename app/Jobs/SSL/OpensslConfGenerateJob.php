<?php

namespace App\Jobs\SSL;

use App\Models\Project;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;

class OpensslConfGenerateJob implements ShouldQueue
{
use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

public function __construct( public Project $project)
{
}

public function handle(): void
{
$sslcnf = Storage::disk('etc')->path('openssl.cnf');

File::put($sslcnf, "
[req]
default_bits       = 2048
distinguished_name = req_distinguished_name
req_extensions     = v3_req
prompt             = no

[req_distinguished_name]
C  = SG
ST = Turkey
L  = Turkey
O  = Alpzo
OU = IT
CN = Alpzo

[v3_req]
extendedKeyUsage            = serverAuth
subjectAltName              = @alt_names

[alt_names]
DNS.1 = localhost
DNS.2 = {$this->project->url}
DNS.3 = *.{$this->project->url}

");
}
}
