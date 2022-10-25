<?php

namespace IEXApp\Quote\UseCase\SearchQuote;

use IEXApp\Shared\UseCase\InputUseCase;

class InputSearchQuote implements InputUseCase
{
    public function __construct(public readonly string $symbol)
    {
    }
}
