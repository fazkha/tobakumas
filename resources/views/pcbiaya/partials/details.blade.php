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
                <div class="flex items-center justify-center">
                    <button
                        @click="openModal = true; modalTitle = '{{ $detail->jenis_nama }}'; $refs.imgRef.src = '{{ $detail->image_nama ? asset($detail->image_lokasi . '/' . $detail->image_nama) : asset('images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}'">
                        <img class="w-20 h-auto rounded-md"
                            src="{{ $detail->image_nama ? asset($detail->image_lokasi . '/' . $detail->image_nama) : asset('images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}"
                            alt="o.o" />
                    </button>
                </div>
            </td>
            <td class="align-top text-center">
                <label class="cursor-pointer">
                    <input type="checkbox" name="approved_fin[]" value="{{ $detail->approved_fin }}"
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
