@if ($details->count() > 0)
    @foreach ($details as $detail)
        <tr>
            <td class="align-top">
                <x-text-span>{{ $detail->barang->nama }}</x-text-span>
            </td>
            <td class="align-top">
                <x-text-span>{{ $detail->satuan->singkatan }}</x-text-span>
            </td>
            <td class="align-top">
                <x-text-span>{{ $detail->kuantiti }}</x-text-span>
            </td>
        </tr>
    @endforeach
@endif
