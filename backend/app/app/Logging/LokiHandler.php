<?php

namespace App\Logging;

use Monolog\Handler\AbstractProcessingHandler;
use Monolog\Logger;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use Monolog\LogRecord;

class LokiHandler extends AbstractProcessingHandler
{
    protected $client;
    protected $lokiUrl;
    protected $labels;

    public function __construct($level = Logger::DEBUG, bool $bubble = true)
    {
        parent::__construct($level, $bubble);
        // Inisialisasi Guzzle HTTP client
        $this->client = new Client();
        // URL Loki. Pastikan dapat diakses dari container/mesin yang menjalankan Laravel.
        $this->lokiUrl = env('LOKI_URL', 'https://loki.mediakolaborasi.com/loki/api/v1/push');
        // Label tambahan yang bisa kamu gunakan untuk identifikasi log
        $this->labels = ['app' => 'laravel'];
    }

    protected function write(LogRecord $record): void
    {
        $timestamp = strval((int)(microtime(true) * 1e9)); // timestamp dalam nanodetik
        $logLine = $record['formatted'];

        try {
            $this->client->post($this->lokiUrl, [
                'json' => [
                    'streams' => [
                        [
                            'stream' => $this->labels,
                            'values' => [
                                [$timestamp, $logLine]
                            ]
                        ]
                    ]
                ],
            ]);
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            // Tangani exception jika pengiriman log gagal. Kamu bisa log ke file atau error monitoring lain.
        }
    }
}
