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
            'latestPrice' => $quote['latestPrice'],
            'latestUpdate' => $quote['latestUpdate']
        ]);
    }

    private function getQuoteByIntegration(string $symbol): ?array
    {
        $quote = $this->stockIntegration->getQuoteBySymbol($symbol);

        if ($quote) {
            $latestUpdate = (new \DateTime())->setTimestamp($quote['latestUpdate'] / 1000);
            $this->storeQuote($symbol, $quote['companyName'], $quote['latestPrice'], $latestUpdate);
            $quote['latestUpdate'] = $latestUpdate;
        }

        return $quote;
    }

    private function storeQuote(string $symbol, string $companyName, float $latestPrice, \DateTime $latestUpdate): void
    {
        $input = new InputStoreQuote($symbol, $companyName, $latestPrice, $latestUpdate);
        $this->storeQuoteUseCase->handle($input);
    }
}
