<div style="text-align: center">
    <input wire:model="symbol" type="text" />
    <button wire:click="search">Search</button>

    @if($errorMessage)
        <br>
        <b>Erro</b>: {{ $errorMessage }}
    @endif
    <ul style="list-style-type: none">
        <li>Company Name: {{ $quote['companyName'] ?? '' }}</li>
        <li>Latest Price: {{ $quote['latestPrice'] ?? '' }}</li>
    </ul>

</div>
