<?php

namespace IEXApp\Quote\UseCase\StoreQuote;

use IEXApp\Shared\UseCase\OutputUseCase;

class OutputStoreQuote implements OutputUseCase
{
    public function __construct(private array $quote)
    {

    }

    public function getData(): array
    {
        return $this->quote;
    }
}
