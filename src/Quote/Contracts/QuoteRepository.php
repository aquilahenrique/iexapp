<?php

namespace IEXApp\Quote\Contracts;

use App\Models\Quote;

interface QuoteRepository
{
    public function findBySymbol(string $symbol): ?array;

    public function save(string $symbol, string $companyName, float $latestPrice, \DateTime $latestUpdate): array;

}
