<?php

namespace App\Http\Livewire\Quote;

use IEXApp\Quote\UseCase\SearchQuote\InputSearchQuote;
use IEXApp\Quote\UseCase\SearchQuote\SearchQuoteUseCase;
use IEXApp\Shared\Exceptions\InvalidInputException;
use Livewire\Component;

class SearchQuote extends Component
{
    public string $symbol = '';
    private array $quote = [];
    private string $errorMessage = '';

    public function search(SearchQuoteUseCase $searchQuoteUseCase): void
    {
        try {
            $input = new InputSearchQuote($this->symbol);
            $output = $searchQuoteUseCase->handle($input)->getData();
            $this->quote = $output;
        } catch (InvalidInputException $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.quote.search-quote', [
            'quote' => $this->quote,
            'errorMessage' => $this->errorMessage
        ]);
    }
}
