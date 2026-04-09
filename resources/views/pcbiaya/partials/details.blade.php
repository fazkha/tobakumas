@php
    $i = 0;
@endphp
@if ($details->count() > 0)
    @foreach ($details as $detail)
        <tr>
            <td class="align-middle">
                <input type="text" name="detail_id[]" value="{{ $detail->id }}">
                <input type="test" name="approved[]" value="{{ $detail->approved_fin }}">
                <x-text-span>{{ $detail->jenis_nama }}</x-text-span>
            </td>
            <td class="align-middle text-right">
                <x-text-span>{{ $detail->harga }}</x-text-span>
            </td>
            <td class="align-middle">
                <div class="flex items-center justify-center">
                    <img class="zoomable w-auto h-9 rounded-md"
                        src="{{ $detail->image_nama ? asset($detail->image_lokasi . '/' . $detail->image_nama) : asset('images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}"
                        alt="o.o" />
                </div>
            </td>
            <td class="align-middle text-center">
                <label class="cursor-pointer">
                    <input type="checkbox" name="approved_fin[{{ $i }}]" value="{{ $detail->approved_fin }}"
                        @php if ($i < count($details)) {
                        if ($details[$i]->approved_fin == $detail->approved_fin) {
                            echo 'checked';
                        }
                    } @endphp
                        class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md">
                </label>
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
