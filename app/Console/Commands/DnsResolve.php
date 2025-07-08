<?php
declare(strict_types=1);
namespace App\Console\Commands;

use Illuminate\Console\Command;

class DnsResolve extends Command
{
/**
     * The name and signature of the console command.
     *
     * @var string
     */
protected $signature = 'dns:resolve';

/**
     * The console command description.
     *
     * @var string
     */
protected $description = 'DNS Resolve';




public function handle()
{

$socket = socket_create(AF_INET, SOCK_DGRAM, SOL_UDP);
if (!$socket) {
die("Socket oluşturulamadı: " . socket_strerror(socket_last_error()) . PHP_EOL);
}


if (!socket_bind($socket, '127.0.0.1', 53)) {
die("Porta bağlanılamadı: " . socket_strerror(socket_last_error()) . PHP_EOL);
}

echo "DNS sunucusu 127.0.0.1:53 üzerinde çalışıyor...\n";


while (true) {
$buffer = '';
$from = '';
$port = 0;


socket_recvfrom($socket, $buffer, 512, 0, $from, $port);

echo "Sorgu alındı: $from:$port\n";


$response = $this->generateDnsResponse($buffer);


socket_sendto($socket, $response, strlen($response), 0, $from, $port);

echo "Yanıt gönderildi: $from:$port\n";
}
}

function generateDnsResponse($buffer): string
{

$transactionId = substr($buffer, 0, 2); 
$flags = "\x81\x80"; 
$questions = substr($buffer, 4, 2); 


$answer = "\xc0\x0c"; 
$answer .= "\x00\x01"; 
$answer .= "\x00\x01"; 
$answer .= "\x00\x00\x00\x3c"; 
$answer .= "\x00\x04"; 
$answer .= "\x7f\x00\x00\x01"; 



return $transactionId . $flags . $questions . "\x00\x01\x00\x00\x00\x00" . substr($buffer, 12) . $answer;

}

}
