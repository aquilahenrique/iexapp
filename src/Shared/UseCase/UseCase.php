<?php

namespace IEXApp\Shared\UseCase;

interface UseCase
{
    function handle(InputUseCase $input): OutputUseCase;
}
