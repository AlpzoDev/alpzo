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
use Illuminate\Support\Str;

class SSLCertificaGeneratorJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct(public Project $project)
    {
    }

    public function handle(): void
    {
        dispatch_sync(new OpensslFoldersCheckJob());
        dispatch_sync(new OpensslConfGenerateJob($this->project));

        $certificatePath = Storage::disk('etc')->path(
            Str::of('ssl/certs')->append('/', $this->project->url, '.crt')->toString()
        );
        $keyPath = Storage::disk('etc')->path(
            Str::of('ssl/private')->append('/', $this->project->url, '.key')->toString()
        );

        $opensslConfigPath = Storage::disk('etc')->path('openssl.cnf');
        if (!File::exists($opensslConfigPath)) {
            throw new \Exception("OpenSSL configuration file not found at: {$opensslConfigPath}");
        }


        $config = [

            'private_key_bits' => 2048,
            'private_key_type' => OPENSSL_KEYTYPE_RSA,
            'config' => Str::of($opensslConfigPath)->replace('\\', '/')->toString()
        ];

        try {

            $privateKey = openssl_pkey_new($config) ?: throw new \Exception(
                "Private key generation failed: " . openssl_error_string()
            );


            $dn = [
                'commonName' => $this->project->url,
                'emailAddress' => "info@{$this->project->url}",
            ];


            $csr = openssl_csr_new($dn, $privateKey, $config) ?: throw new \Exception(
                "CSR generation failed: " . openssl_error_string()
            );


            $certificate = openssl_csr_sign($csr, null, $privateKey, 365, $config) ?: throw new \Exception(
                "Certificate signing failed: " . openssl_error_string()
            );


            if (!openssl_pkey_export($privateKey, $privateKeyOut, null, $config)) {
                throw new \Exception("Private key export failed: " . openssl_error_string());
            }


            if (!openssl_x509_export($certificate, $certificateOut)) {
                throw new \Exception("Certificate export failed: " . openssl_error_string());
            }


            File::put($keyPath, $privateKeyOut);
            File::put($certificatePath, $certificateOut);

        } catch (\Exception $e) {

            throw $e;
        }
    }
}
