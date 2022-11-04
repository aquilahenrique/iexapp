<?php

namespace IEXApp\Quote\UseCase\StoreQuote;

use IEXApp\Shared\UseCase\InputUseCase;

class InputStoreQuote implements InputUseCase
{
    public function __construct(
        public readonly string $symbol,
        public readonly string $companyName,
        public readonly float $latestPrice,
        public readonly \DateTime $latestUpdate,
    ) {
    }
}
