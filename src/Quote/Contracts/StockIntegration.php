<?php

namespace IEXApp\Quote\Contracts;

interface StockIntegration
{
    public function getQuoteBySymbol(string $symbol): ?array;
}
