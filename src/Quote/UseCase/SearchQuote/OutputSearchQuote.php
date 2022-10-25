<?php

namespace IEXApp\Quote\UseCase\SearchQuote;

use IEXApp\Shared\UseCase\OutputUseCase;

class OutputSearchQuote implements OutputUseCase
{
    public function __construct(private array $quote)
    {

    }

    public function getData(): array
    {
        return $this->quote;
    }
}
