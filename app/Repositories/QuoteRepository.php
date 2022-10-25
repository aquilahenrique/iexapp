<?php

namespace App\Repositories;

use App\Models\Quote;

class QuoteRepository implements \IEXApp\Quote\Contracts\QuoteRepository
{

    public function findBySymbol(string $symbol): ?array
    {
        $quote = Quote::where('symbol', $symbol)->first();

        if (!$quote) {
            return null;
        }

        return [
            'companyName' => $quote->company_name,
            'latestPrice' => $quote->latest_price
        ];
    }

    public function save(string $symbol, string $companyName, float $latestPrice): array
    {
        $quote = new Quote();
        $quote->symbol = $symbol;
        $quote->company_name = $companyName;
        $quote->latest_price = $latestPrice;
        $quote->save();

        return [
            'companyName' => $quote->company_name,
            'latestPrice' => $quote->latest_price
        ];
    }
}
