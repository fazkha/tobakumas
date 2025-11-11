@php
    $total = 0;
@endphp
<table class="w-full">
    <tr>
        <th colspan="3" class="text-right">&nbsp;</th>
        @if (!$isready == 1)
            <th class="text-right">@lang('messages.stock')</th>
        @else
            <th class="text-right">@lang('messages.price') (@lang('messages.currencysymbol'))</th>
        @endif
    </tr>
    @foreach ($bahans as $bahan)
        <tr class="border-t border-primary-100 dark:border-primary-700">
            <td class="py-2"><span class="pl-4">{{ $bahan->bahan }}</span></td>
            <td><span class="pl-2">{{ $bahan->satuan }}</span></td>
            <td class="text-right">{{ number_format($bahan->jumlah, 2, ',', '.') }}</td>
            @if (!$isready == 1)
                @if ($bahan->stock < $bahan->jumlah)
                    <td class="text-right text-red-600">{{ number_format($bahan->stock, 2, ',', '.') }}</td>
                @else
                    <td class="text-right">{{ number_format($bahan->stock, 2, ',', '.') }}</td>
                @endif
            @else
                <td class="text-right">{{ number_format($bahan->jumlah * $bahan->harga_satuan_jual, 0, ',', '.') }}</td>
            @endif
        </tr>
        @php
            $total = $total + $bahan->jumlah * $bahan->harga_satuan_jual;
        @endphp
    @endforeach
    <tr class="border-t border-primary-100 dark:border-primary-700">
        <th colspan="5" class="py-2 text-right">{{ number_format($total, 0, ',', '.') }}</th>
    </tr>
</table>
