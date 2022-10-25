<?php

namespace IEXApp\Quote\UseCase\SearchQuote;

use IEXApp\Quote\Contracts\QuoteRepository;
use IEXApp\Quote\Contracts\StockIntegration;
use IEXApp\Quote\UseCase\StoreQuote\InputStoreQuote;
use IEXApp\Quote\UseCase\StoreQuote\StoreQuoteUseCase;
use IEXApp\Shared\Exceptions\InvalidInputException;
use IEXApp\Shared\UseCase\InputUseCase;
use IEXApp\Shared\UseCase\UseCase;

class SearchQuoteUseCase implements UseCase
{
    public function __construct(
        private StockIntegration $stockIntegration,
        private QuoteRepository $quoteRepository,
        private StoreQuoteUseCase $storeQuoteUseCase
    ) {
    }

    /**
     * @throws InvalidInputException
     */
    function handle(InputUseCase $input): OutputSearchQuote
    {
        if (empty($input->symbol)) {
            throw new InvalidInputException('empty symbol');
        }
        $quote = $this->quoteRepository->findBySymbol($input->symbol);

        if (!$quote) {
            $quote = $this->getQuoteByIntegration($input->symbol);
        }

        if (!$quote) {
            throw new InvalidInputException('invalid symbol');
        }

        return new OutputSearchQuote([
            'companyName' => $quote['companyName'],
            'latestPrice' => $quote['latestPrice']
        ]);
    }

    private function getQuoteByIntegration(string $symbol): ?array
    {
        $quote = $this->stockIntegration->getQuoteBySymbol($symbol);

        if ($quote) {
            $this->storeQuote($symbol, $quote['companyName'], $quote['latestPrice']);
        }

        return $quote;
    }

    private function storeQuote(string $symbol, string $companyName, float $latestPrice): void
    {
        $input = new InputStoreQuote($symbol, $companyName, $latestPrice);
        $this->storeQuoteUseCase->handle($input);
    }
}
