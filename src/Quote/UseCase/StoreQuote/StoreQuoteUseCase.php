<?php

namespace IEXApp\Quote\UseCase\StoreQuote;

use IEXApp\Quote\Contracts\QuoteRepository;
use IEXApp\Shared\Exceptions\InvalidInputException;
use IEXApp\Shared\UseCase\InputUseCase;
use IEXApp\Shared\UseCase\UseCase;

class StoreQuoteUseCase implements UseCase
{

    public function __construct(private QuoteRepository $quoteRepository)
    {
    }

    /**
     * @throws InvalidInputException
     */
    function handle(InputUseCase $input): OutputStoreQuote
    {
        if (empty($input->symbol)) {
            throw new InvalidInputException('empty symbol');
        }

        if (empty($input->companyName)) {
            throw new InvalidInputException('empty company name');
        }

        if (empty($input->latestPrice)) {
            throw new InvalidInputException('empty latest price');
        }
        if (empty($input->latestUpdate)) {
            throw new InvalidInputException('empty latest update');
        }

        $quote = $this->quoteRepository->save($input->symbol, $input->companyName, $input->latestPrice, $input->latestUpdate);

        return new OutputStoreQuote([
            'companyName' => $quote['companyName'],
            'latestPrice' => $quote['latestPrice'],
            'latestUpdate' => $quote['latestUpdate'],
        ]);
    }
}
