@if ($details->count() > 0)
    @php
        $i = 0;
    @endphp
    @foreach ($details as $detail)
        <tr>
            <td class="align-top">
                <input type="hidden" name="items[{{ $detail->id }}][id]" />
                <x-text-span>{{ $detail->barang->nama }}</x-text-span>
            </td>
            <td class="align-top text-right">
                <x-text-input type="number" min="0" name="items[{{ $detail->id }}][harga_satuan]"
                    value="{{ $detail->harga_satuan }}" required tabindex="11" />
            </td>
            <td class="align-top">
                <select name="items[{{ $detail->id }}][satuan_id]" required tabindex="12"
                    class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                    <option value="">@lang('messages.choose')...</option>
                    @foreach ($satuans as $id => $name)
                        <option value="{{ $id }}" {{ $detail->satuan_id == $id ? 'selected' : '' }}>
                            {{ $name }}</option>
                    @endforeach
                </select>
            </td>
            <td class="align-top text-right">
                <x-text-input type="number" min="0" name="items[{{ $detail->id }}][kuantiti]"
                    value="{{ $detail->kuantiti }}" required tabindex="13" />
            </td>
            <td class="align-top text-right">
                <x-text-input type="number" min="0" name="items[{{ $detail->id }}][discount]"
                    value="{{ $detail->discount }}" tabindex="14" />
            </td>
            <td class="align-top text-right">
                <x-text-input type="number" min="0" name="items[{{ $detail->id }}][pajak]"
                    value="{{ $detail->pajak }}" tabindex="15" />
            </td>
            <td class="align-top text-right">
                <x-text-span id="disp-sub_harga"
                    class="text-right">{{ number_format($detail->harga_satuan * (1 + $detail->pajak / 100 - $detail->discount / 100) * $detail->kuantiti, 0, ',', '.') }}</x-text-span>
            </td>
            @if ($viewMode == false)
                @if ($detail->kuantiti_terima > 0)
                    <td class="align-middle" title="@lang('messages.goodsreceived')">🚫</td>
                @else
                    <td class="align-top">
                        <x-anchor-danger id="a-delete-detail-{{ $detail->id }}"
                            onclick="deleteDetail({{ $detail->id }})" class="!px-1"
                            title="{{ __('messages.delete') }}">
                            <svg class="size-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round"
                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                            </svg>
                        </x-anchor-danger>
                    </td>
                @endif
            @endif
        </tr>
        @php
            $i++;
        @endphp
    @endforeach
@endif
