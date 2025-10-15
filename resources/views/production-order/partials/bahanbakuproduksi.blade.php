<table class="w-full">
    <tr>
        <th colspan="3" class="text-right">&nbsp;</th>
        <th class="text-right">Persediaan</th>
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
                <td class="text-right">-</td>
            @endif
        </tr>
    @endforeach
</table>
