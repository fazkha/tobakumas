@if ($details->count() > 0)
    @foreach ($details as $detail)
        <tr>
            <td class="align-top">
                <x-text-span>{{ $detail->barang->nama }}</x-text-span>
            </td>
            <td class="align-top text-right">
                <x-text-span>{{ number_format($detail->harga_satuan, 0, ',', '.') }}</x-text-span>
            </td>
            <td class="align-top">
                <x-text-span>{{ $detail->satuan->singkatan }}</x-text-span>
            </td>
            <td class="align-top text-right">
                <x-text-span>{{ $detail->kuantiti }}</x-text-span>
            </td>
            <td class="align-top text-right">
                <x-text-span>{{ $detail->pajak }}</x-text-span>
            </td>
            <td class="align-top text-right">
                <x-text-span>{{ number_format($detail->harga_satuan * (1 + $detail->pajak / 100) * $detail->kuantiti, 0, ',', '.') }}</x-text-span>
            </td>
            @if ($viewMode == false)
                <td>
                    <div class="flex items-center justify-center space-x-3">
                        <div class="dark:bg-black/10">
                            <label class="cursor-pointer">
                                <input type="checkbox" onclick="approveDetail({{ $detail->id }})"
                                    id="approved-{{ $detail->id }}" name="approved-{{ $detail->id }}"
                                    class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md"
                                    {{ $detail->approved == 1 ? 'checked' : '' }}>
                            </label>
                        </div>
                    </div>
                </td>
            @endif
        </tr>
    @endforeach
@endif
