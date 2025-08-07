@foreach ($targets as $target)
    <tr class="border-t border-primary-100 dark:border-primary-700">
        <td class="py-2"><span>{{ $target->mitra }}</span></td>
        <td><span>{{ $target->barang }}</span></td>
        <td class="text-right">{{ number_format($target->jumlah, 2, ',', '.') }}</td>
        <td><span class="pl-2">{{ $target->satuan }}</span></td>
    </tr>
@endforeach
