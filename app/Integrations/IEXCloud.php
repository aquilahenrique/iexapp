<?php

namespace App\Integrations;

use IEXApp\Quote\Contracts\StockIntegration;
use Illuminate\Support\Facades\Http;

class IEXCloud implements StockIntegration
{
    private string $baseUrl;
    private string $token;

    public function __construct()
    {
        $this->token = env('IEX_CLOUD_TOKEN') ?? '';
        $this->baseUrl = env('IEX_CLOUD_URL') ?? '';
    }

    public function getQuoteBySymbol(string $symbol): ?array
    {
        $url = sprintf('%s/stock/%s/quote', $this->baseUrl, $symbol);
        $response = Http::get($url, [
            'token' => $this->token
        ]);

        return $response->json();
    }
}
