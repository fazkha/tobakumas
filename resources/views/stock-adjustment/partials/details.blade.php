@if ($details->count() > 0)
    @php
        $di = 0;
    @endphp

    @foreach ($details as $detail)
        <tr>
            <td class="align-top">
                <input type="hidden" name="stocks[{{ $di }}][id]" value="{{ $detail->id }}" />
                <x-text-span>{{ $detail->barang->nama }}</x-text-span>
            </td>
            <td class="align-top">
                <x-text-span>{{ $detail->satuan->singkatan }}</x-text-span>
            </td>
            <td class="align-top text-right">
                <x-text-span>{{ number_format($detail->before_stock, 2, ',', '.') }}</x-text-span>
            </td>
            <td class="align-top text-right">
                <x-text-span>{{ number_format($detail->stock, 2, ',', '.') }}</x-text-span>
            </td>
            <td class="align-top text-right">
                <x-text-span>{{ number_format($detail->selisih_stock, 2, ',', '.') }}</x-text-span>
            </td>
            @if ($viewMode)
                <td class="align-top text-right">
                    <x-text-span>{{ number_format($detail->adjust_stock, 2, ',', '.') }}</x-text-span>
                </td>
                <td class="align-top">
                    <x-text-span>{{ $detail->keterangan_adjustment ? $detail->keterangan_adjustment : '-' }}</x-text-span>
                </td>
            @else
                <td class="align-top">
                    <x-text-input type="number" min="0" step="0.01"
                        name="stocks[{{ $di }}][adjust_stock]" value="{{ $detail->adjust_stock }}" required
                        tabindex="9" />
                </td>
                <td class="align-top">
                    <x-text-input type="text" name="stocks[{{ $di }}][keterangan_adjustment]"
                        tabindex="10" value="{{ $detail->keterangan_adjustment }}" />
                </td>
            @endif
        </tr>

        @php
            $di++;
        @endphp
    @endforeach
@endif
