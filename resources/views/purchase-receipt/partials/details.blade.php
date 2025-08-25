@if ($details->count() > 0)
    @php
        $i = 0;
    @endphp
    @foreach ($details as $detail)
        <tr>
            <td class="align-top">
                <x-text-span>{{ $detail->barang->nama }}</x-text-span>
            </td>
            <td class="align-top">
                <x-text-span>{{ $detail->satuan->singkatan }}</x-text-span>
            </td>
            <td class="align-top text-right">
                <x-text-span>{{ $detail->kuantiti }}</x-text-span>
            </td>
            <td class="align-top text-center">
                <input type="hidden" name="items[{{ $i }}][id]" value="{{ $detail->id }}" />
                @if ($viewMode == true)
                    <div class="inline-flex items-center py-2">
                        @if ($detail->isaccepted == '1')
                            <span>✔️</span>
                        @else
                            @if ($detail->isaccepted == '0')
                                <span>❌</span>
                            @else
                                <span>❓</span>
                            @endif
                        @endif
                    </div>
                @else
                    <input type="checkbox" name="items[{{ $i }}][isaccepted]" tabindex="6"
                        class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md py-2"
                        {{ $detail->isaccepted == '1' ? 'checked' : '' }} />
                @endif
            </td>
            <td class="align-top">
                @if ($viewMode == true)
                    <x-text-span>{{ $detail->satuan_terima_id ? $detail->satuan_terima->singkatan : '-' }}</x-text-span>
                @else
                    <select name="items[{{ $i }}][satuan_terima_id]" tabindex="7"
                        class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                        <option value="">@lang('messages.choose')...</option>
                        @foreach ($satuans as $id => $name)
                            <option value="{{ $id }}"
                                {{ $detail->satuan_terima_id == $id ? 'selected' : '' }}>
                                {{ $name }}</option>
                        @endforeach
                    </select>
                @endif
            </td>
            <td class="align-top text-right">
                @if ($viewMode == true)
                    <x-text-span>{{ $detail->kuantiti_terima ? $detail->kuantiti_terima : '-' }}</x-text-span>
                @else
                    <x-text-input type="number" min="0" step="0.01"
                        name="items[{{ $i }}][kuantiti_terima]" tabindex="8"
                        value="{{ $detail->kuantiti_terima }}" />
                @endif
            </td>
            <td class="align-top">
                @if ($viewMode == true)
                    <x-text-span>{{ $detail->keterangan_terima ? $detail->keterangan_terima : '-' }}</x-text-span>
                @else
                    <x-text-input type="text" name="items[{{ $i }}][keterangan_terima]" tabindex="9"
                        value="{{ $detail->keterangan_terima }}" />
                @endif
            </td>
        </tr>
        @php
            $i++;
        @endphp
    @endforeach
@endif
