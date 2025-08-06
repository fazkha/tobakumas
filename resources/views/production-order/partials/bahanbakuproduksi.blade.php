<table class="w-full">
    @foreach ($bahans as $bahan)
        <tr class="border-t border-primary-100 dark:border-primary-700">
            <td class="py-2"><span class="pl-4">{{ $bahan->bahan }}</span></td>
            <td class="text-right">{{ number_format($bahan->jumlah, 2, ',', '.') }}</td>
            <td><span class="pl-2">{{ $bahan->satuan }}</span></td>
        </tr>
    @endforeach
</table>
