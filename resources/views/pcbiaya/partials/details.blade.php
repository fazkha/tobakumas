@php
    $i = 0;
@endphp
@if ($details->count() > 0)
    @foreach ($details as $detail)
        <tr>
            <td class="align-top">
                <x-text-span>{{ $detail->jenis_nama }}</x-text-span>
            </td>
            <td class="align-top text-right">
                <x-text-span>{{ $detail->harga }}</x-text-span>
            </td>
            <td class="align-top">
                <x-text-span>{{ $detail->jenis_nama }}</x-text-span>
            </td>
            <td class="align-top text-center">
                <span class="">
                    <label class="cursor-pointer flex flex-row gap-2 items-center">
                        <input type="checkbox" name="approved_fin[]" value="{{ $detail->approved_fin }}"
                            @php if ($i < count($details)) {
                        if ($details[$i]->approved_fin == $detail->approved_fin) {
                            echo 'checked';
                        }
                    } @endphp
                            class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md">
                    </label>
                </span>
            </td>
        </tr>
        @php
            if ($i < count($details)) {
                if ($details[$i]->approved_fin == $detail->approved_fin) {
                    $i++;
                }
            }
        @endphp
    @endforeach
@endif
