<?php

namespace Tests\Unit\Quote\UseCase\SearchQuote;

use IEXApp\Quote\Contracts\QuoteRepository;
use IEXApp\Quote\Contracts\StockIntegration;
use IEXApp\Quote\UseCase\SearchQuote\InputSearchQuote;
use IEXApp\Quote\UseCase\SearchQuote\OutputSearchQuote;
use IEXApp\Quote\UseCase\SearchQuote\SearchQuoteUseCase;
use IEXApp\Quote\UseCase\StoreQuote\StoreQuoteUseCase;
use IEXApp\Shared\Exceptions\InvalidInputException;
use Mockery\Mock;
use PHPUnit\Framework\TestCase;

class SearchQuoteUseCaseTest extends TestCase
{

    private StockIntegration|\Mockery\LegacyMockInterface|\Mockery\MockInterface $stockIntegration;
    private \Mockery\LegacyMockInterface|StoreQuoteUseCase|\Mockery\MockInterface $storeQuoteUseCase;
    private QuoteRepository|\Mockery\LegacyMockInterface|\Mockery\MockInterface $quoteRepository;

    public function setUp(): void
    {
        $this->stockIntegration = \Mockery::mock(StockIntegration::class);
        $this->quoteRepository = \Mockery::mock(QuoteRepository::class);
        $this->storeQuoteUseCase = \Mockery::mock(StoreQuoteUseCase::class);
    }

    public function test_empty_symbol()
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('empty symbol');

        $input = new InputSearchQuote('');

        $useCase = new SearchQuoteUseCase(
            $this->stockIntegration,
            $this->quoteRepository,
            $this->storeQuoteUseCase
        );

        $useCase->handle($input);
    }

    public function test_symbol_from_database()
    {
        $input = new InputSearchQuote('aapl');

        $this->quoteRepository->shouldReceive('findBySymbol')
            ->andReturn([
                'companyName' => 'Apple',
                'latestPrice' => 140.99
            ]);

        $useCase = new SearchQuoteUseCase(
            $this->stockIntegration,
            $this->quoteRepository,
            $this->storeQuoteUseCase
        );

        $output = $useCase->handle($input);

        $this->assertInstanceOf(OutputSearchQuote::class, $output);
        $this->assertEquals([
            'companyName' => 'Apple',
            'latestPrice' => 140.99
        ], $output->getData());
    }

    public function test_empty_from_database()
    {
        $input = new InputSearchQuote('aapl');

        $this->quoteRepository->shouldReceive('findBySymbol')
            ->andReturnNull();

        $this->stockIntegration->shouldReceive('getQuoteBySymbol')
            ->andReturn([
                'companyName' => 'Twitter',
                'latestPrice' => 45.99
            ]);

        $this->storeQuoteUseCase->shouldReceive('handle')
            ->once();

        $useCase = new SearchQuoteUseCase(
            $this->stockIntegration,
            $this->quoteRepository,
            $this->storeQuoteUseCase
        );

        $output = $useCase->handle($input);

        $this->assertInstanceOf(OutputSearchQuote::class, $output);
        $this->assertEquals([
            'companyName' => 'Twitter',
            'latestPrice' => 45.99
        ], $output->getData());
    }

    public function test_empty_from_database_and_from_integration()
    {
        $this->expectException(InvalidInputException::class);
        $this->expectExceptionMessage('invalid symbol');

        $input = new InputSearchQuote('aapl');

        $this->quoteRepository->shouldReceive('findBySymbol')
            ->andReturnNull();

        $this->stockIntegration->shouldReceive('getQuoteBySymbol')
            ->andReturnNull();

        $this->storeQuoteUseCase->shouldReceive('handle')
            ->never();

        $useCase = new SearchQuoteUseCase(
            $this->stockIntegration,
            $this->quoteRepository,
            $this->storeQuoteUseCase
        );

        $output = $useCase->handle($input);
    }
}
