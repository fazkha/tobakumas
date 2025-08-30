@section('title', __('messages.delivery'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('delivery-order.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M20 33L26 35C26 35 41 32 43 32C45 32 45 34 43 36C41 38 34 44 28 44C22 44 18 41 14 41C10 41 4 41 4 41"
                        stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M4 29C6 27 10 24 14 24C18 24 27.5 28 29 30C30.5 32 26 35 26 35" stroke="currentColor"
                        stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M16 18V10C16 8.89543 16.8954 8 18 8H42C43.1046 8 44 8.89543 44 10V26" stroke="currentColor"
                        stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    <rect x="25" y="8" width="10" height="9" fill="#2F88FF" stroke="currentColor"
                        stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.delivery')</span>
                    <span>@lang('messages.order')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.view')</span>
        </h1>
    </div>

    <div class="py-2 flex flex-col">

        <div class="w-full px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('delivery-order.partials.feedback')
                </div>

                {{-- Master --}}
                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-4 space-y-2">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <label for="no_order"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.salesordernumber')</label>
                                    <x-text-span>{{ $datas->order->no_order }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="tanggal"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliverydate')</label>
                                    <x-text-span>{{ date('d/m/Y', strtotime($datas->tanggal)) }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="alamat"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliveryaddress')</label>
                                    <x-text-span>{{ $datas->alamat }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="keterangan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                    <x-text-span>{{ $datas->keterangan }}</x-text-span>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="w-auto pb-4">
                                    <label for="petugas_1_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.officer')
                                        1</label>
                                    <x-text-span>{{ $datas->petugas_1_id ? $datas->petugas_1->view_pegawai_jabatan->nama_plus : '-' }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="petugas_2_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.officer')
                                        2</label>
                                    <x-text-span>{{ $datas->petugas_2_id ? $datas->petugas_2->view_pegawai_jabatan->nama_plus : '-' }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="pengirim_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliveryman')</label>
                                    <x-text-span>{{ $datas->pengirim_id ? $datas->pengirim->view_pegawai_jabatan->nama_plus : '-' }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4 lg:pb-12">
                                    <label for="tanggungjawab_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.supervisor')</label>
                                    <x-text-span>{{ $datas->tanggungjawab_id ? $datas->tanggungjawab->view_pegawai_jabatan->nama_plus : '-' }}</x-text-span>
                                </div>

                                <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                    <div class="pr-2">
                                        <div class="inline-flex items-center">
                                            <span>✔️</span>
                                            <label class='pl-2'>@lang('messages.packagedfinish')</label>
                                        </div>
                                    </div>

                                    <x-anchor-secondary href="{{ route('delivery-order.index') }}" tabindex="8">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor" class="size-5">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M6 18 18 6M6 6l12 12" />
                                        </svg>
                                        <span class="pl-1">@lang('messages.close')</span>
                                    </x-anchor-secondary>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-4 px-4 py-2">
            <div class="w-full">
                <div class="flex flex-col items-center">
                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">
                            <div class="flex flex-row items-center gap-2">
                                <svg class="size-5" viewBox="0 0 16 16" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <path fill="currentColor"
                                        d="M8 0l-8 2v10l8 4 8-4v-10l-8-2zM8 1l2.1 0.5-5.9 1.9-2.3-0.8 6.1-1.6zM8 14.9l-7-3.5v-8.1l3 1v3.4l1 0.3v-3.3l3 1v9.2zM8.5 4.8l-2.7-0.9 6.2-1.9 2.4 0.6-5.9 2.2z">
                                    </path>
                                </svg>
                                <span class="block font-medium text-primary-600 dark:text-primary-500">
                                    @lang('messages.packagespecification')
                                </span>
                            </div>

                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                @foreach ($pakets as $id => $name)
                                    @php
                                        $paketdetails = App\Models\PaketDetail::where('paket_id', $id)->get();
                                    @endphp
                                    <div
                                        class="flex flex-row items-center justify-start shadow rounded-md border border-solid border-primary-100 dark:border-primary-800">
                                        <div
                                            class="px-4 py-2 border border-primary-100 bg-primary-20 dark:border-primary-800 dark:bg-primary-850">
                                            <span class="text-sm font-bold">{{ $name }}</span>
                                        </div>
                                        <div class="px-4 py-2 flex flex-col gap-2">
                                            @foreach ($paketdetails as $paketdetail)
                                                <span
                                                    class="text-sm">{{ '📦 ' . $paketdetail->barang->nama . ': ' . number_format($paketdetail->kuantiti, 0, ',', '.') . ' ' . $paketdetail->satuan->singkatan }}</span>
                                            @endforeach
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-4 px-4 py-2">
            <div class="w-full">
                <div class="flex flex-col items-center">

                    {{-- Detail --}}
                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">
                            <div class="flex flex-row items-center gap-2">
                                <svg fill="currentColor" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                    viewBox="0 0 52 52" enable-background="new 0 0 52 52" xml:space="preserve">
                                    <g>
                                        <path d="M24.3,36.5c0.7,0,1.4,0.1,2,0.3L15.5,6.2c0,0,0,0,0,0l-1-3c-0.3-0.9-1.2-1.3-2-1L3.1,5.3
  c-0.9,0.3-1.3,1.2-1,2l1,3c0.3,0.9,1.2,1.3,2,1L10,9.7l9.9,28.1C21.2,37,22.7,36.5,24.3,36.5z" />
                                        <path d="M41.2,29.2l-9.9,3.5c-1,0.4-2.2-0.2-2.5-1.2l-3.5-9.9c-0.4-1,0.2-2.2,1.2-2.5l9.9-3.5
  c1-0.4,2.2,0.2,2.5,1.2l3.5,9.9C42.8,27.7,42.2,28.8,41.2,29.2z" />
                                        <path d="M31.8,12.9l-6.7,2.3c-1,0.4-2.2-0.2-2.5-1.2l-2.3-6.7c-0.4-1,0.2-2.2,1.2-2.5l6.7-2.3
  c1-0.4,2.2,0.2,2.5,1.2l2.3,6.7C33.4,11.3,32.9,12.5,31.8,12.9z" />
                                        <path d="M49.9,35.5l-1-3c-0.3-0.9-1.2-1.3-2-1l-18.2,6.3c1.9,1.2,3.2,3.2,3.6,5.5l16.7-5.7
  C49.8,37.3,50.2,36.4,49.9,35.5z" />
                                        <path
                                            d="M24.3,39.1c-3,0-5.5,2.5-5.5,5.5c0,3,2.5,5.5,5.5,5.5s5.5-2.5,5.5-5.5C29.8,41.5,27.3,39.1,24.3,39.1z" />
                                    </g>
                                </svg>
                                <span class="block font-medium text-primary-600 dark:text-primary-500">
                                    @lang('messages.solditem')
                                </span>
                            </div>

                            <div
                                class="border rounded-md border-primary-100 bg-primary-100 dark:border-primary-800 dark:bg-primary-850">
                                <div class="p-2 overflow-scroll md:overflow-auto lg:overflow-hidden">
                                    <table id="order_table" class="w-full border-separate border-spacing-2">
                                        <thead>
                                            <tr>
                                                <th class="w-1/5">@lang('messages.goods')</th>
                                                <th class="w-auto">@lang('messages.description')</th>
                                                <th class="w-1/6">@lang('messages.package')</th>
                                                <th class="w-1/6">@lang('messages.packaging')</th>
                                                <th class="w-1/6">@lang('messages.unit')</th>
                                                <th class="w-1/12">@lang('messages.quantity')</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @if ($details->count() > 0)
                                                @foreach ($details as $detail)
                                                    <tr>
                                                        <td class="align-top">
                                                            <input type="hidden" id="detail_id" name="detail_id[]"
                                                                value="{{ $detail->id }}" />
                                                            <x-text-span>{{ $detail->view_order_detail->barang }}</x-text-span>
                                                        </td>
                                                        <td class="align-top">
                                                            <x-text-span>{{ $detail->order_detail->keterangan ? $detail->order_detail->keterangan : '-' }}</x-text-span>
                                                        </td>
                                                        <td class="align-top">
                                                            <x-text-span>{{ $detail->paket_id ? $detail->paket->nama : '-' }}</x-text-span>
                                                        </td>
                                                        <td class="align-top">
                                                            <x-text-span>{{ $detail->barang_id ? $detail->barang->nama : '-' }}</x-text-span>
                                                        </td>
                                                        <td class="align-top">
                                                            <x-text-span>{{ $detail->satuan_id ? $detail->satuan->singkatan : '-' }}</x-text-span>
                                                        </td>
                                                        <td class="align-top">
                                                            <x-text-span>{{ $detail->kuantiti }}</x-text-span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-4 px-4 py-2">
            <div class="w-full">
                <div class="flex flex-col items-center">

                    <div
                        class="w-full shadow-lg rounded-md border bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">
                            <div class="flex flex-row items-center gap-2">
                                <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none"
                                    xmlns="http://www.w3.org/2000/svg">
                                    <path
                                        d="M12.916 8.48581L17.4943 3.90753C18.1349 3.26696 19.1735 3.26696 19.814 3.90753V3.90753C20.4546 4.5481 20.4546 5.58667 19.814 6.22724L18.4073 7.63391L17.7657 8.27555"
                                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                    <path d="M2.9259 19.3746H21.074" stroke="currentColor" stroke-width="1.7"
                                        stroke-linecap="round" />
                                    <path
                                        d="M20.3149 8.48584H3.68498C3.26575 8.48584 2.9259 8.82569 2.9259 9.24492V10.3006C2.9259 15.3121 6.98849 19.3747 12 19.3747C17.0114 19.3747 21.074 15.3121 21.074 10.3006V9.24492C21.074 8.82569 20.7342 8.48584 20.3149 8.48584Z"
                                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                    <path
                                        d="M12.916 8.48581L17.4943 3.90753C18.1349 3.26696 19.1735 3.26696 19.814 3.90753V3.90753C20.4546 4.5481 20.4546 5.58667 19.814 6.22724L18.4073 7.63391L17.7657 8.27555"
                                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                    <path d="M2.9259 19.3746H21.074" stroke="currentColor" stroke-width="1.7"
                                        stroke-linecap="round" />
                                    <path
                                        d="M20.3149 8.48584H3.68498C3.26575 8.48584 2.9259 8.82569 2.9259 9.24492V10.3006C2.9259 15.3121 6.98849 19.3747 12 19.3747C17.0114 19.3747 21.074 15.3121 21.074 10.3006V9.24492C21.074 8.82569 20.7342 8.48584 20.3149 8.48584Z"
                                        stroke="currentColor" stroke-width="1.7" stroke-linecap="round" />
                                </svg>
                                <span class="block font-medium text-primary-600 dark:text-primary-500">
                                    @lang('messages.dough')
                                </span>
                            </div>

                            <div
                                class="border rounded-md border-primary-100 bg-primary-100 dark:border-primary-800 dark:bg-primary-850">
                                <div class="p-2 overflow-scroll md:overflow-auto lg:overflow-hidden">
                                    <table id="order_table" class="w-full border-separate border-spacing-2">
                                        <thead>
                                            <tr>
                                                <th class="w-1/5">@lang('messages.partner')</th>
                                                <th class="w-1/5">@lang('messages.goods')</th>
                                                <th class="w-auto">@lang('messages.description')</th>
                                                <th class="w-1/6">@lang('messages.package')</th>
                                                <th class="w-1/6">@lang('messages.packaging')</th>
                                                <th class="w-1/6">@lang('messages.unit')</th>
                                                <th class="w-1/12">@lang('messages.quantity')</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @if ($mitras->count() > 0)
                                                @foreach ($mitras as $mitra)
                                                    <tr>
                                                        <td class="align-top">
                                                            <input type="hidden" name="mitra_id[]"
                                                                value="{{ $mitra->id }}" />
                                                            <x-text-span>{{ $mitra->view_order_mitra->mitra }}</x-text-span>
                                                        </td>
                                                        <td class="align-top">
                                                            <x-text-span>{{ $mitra->view_order_mitra->barang }}</x-text-span>
                                                        </td>
                                                        <td class="align-top">
                                                            <x-text-span>{{ $mitra->order_mitra->keterangan ? $mitra->order_mitra->keterangan : '-' }}</x-text-span>
                                                        </td>
                                                        <td class="align-top">
                                                            <x-text-span>{{ $mitra->paket_id ? $mitra->paket->nama : '-' }}</x-text-span>
                                                        </td>
                                                        <td class="align-top">
                                                            <x-text-span>{{ $mitra->barang_id ? $mitra->barang->nama : '-' }}</x-text-span>
                                                        </td>
                                                        <td class="align-top">
                                                            <x-text-span>{{ $mitra->satuan_id ? $mitra->satuan->singkatan : '-' }}</x-text-span>
                                                        </td>
                                                        <td class="align-top">
                                                            <x-text-span>{{ $mitra->kuantiti }}</x-text-span>
                                                        </td>
                                                    </tr>
                                                @endforeach
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-4 px-4 py-2 justify-end">
            <div class="w-full md:w-1/2">
                <div class="flex flex-col items-center">

                    <div
                        class="w-full shadow-lg rounded-md border bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">
                            <div class="flex flex-row items-center gap-2">
                                <svg class="size-5" viewBox="0 0 16 16" version="1.1"
                                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                    <path fill="currentColor"
                                        d="M8 0l-8 2v10l8 4 8-4v-10l-8-2zM8 1l2.1 0.5-5.9 1.9-2.3-0.8 6.1-1.6zM8 14.9l-7-3.5v-8.1l3 1v3.4l1 0.3v-3.3l3 1v9.2zM8.5 4.8l-2.7-0.9 6.2-1.9 2.4 0.6-5.9 2.2z">
                                    </path>
                                </svg>
                                <span class="block font-medium text-primary-600 dark:text-primary-500">
                                    @lang('messages.packaging')
                                </span>
                            </div>

                            <div class="p-2 overflow-scroll md:overflow-auto lg:overflow-hidden">
                                <table id="target_table" class="w-full">
                                    <thead>
                                        <tr>
                                            <th class="w-auto text-left">@lang('messages.goods')</th>
                                            <th class="w-auto text-right">@lang('messages.quantity')</th>
                                            <th class="w-auto">@lang('messages.unit')</th>
                                        </tr>
                                    </thead>

                                    <tbody>
                                        @if (count($kemasans) > 0)
                                            @foreach ($kemasans as $kemasan)
                                                <tr class="border-t border-primary-100 dark:border-primary-700">
                                                    <td class="py-2"><span>{{ $kemasan->barang }}</span></td>
                                                    <td class="text-right">
                                                        {{ number_format($kemasan->kuantiti, 2, ',', '.') }}</td>
                                                    <td><span class="pl-2">{{ $kemasan->satuan }}</span></td>
                                                </tr>
                                            @endforeach
                                        @else
                                            <tr>
                                                <td colspan="3" class="text-sm bg-primary-20 dark:bg-primary-900">
                                                    <div class="flex items-center justify-center p-5">
                                                        @lang('messages.datanotavailable')</div>
                                                </td>
                                            </tr>
                                        @endif
                                    </tbody>
                                </table>
                            </div>

                            <div class="mt-4 mb-4 mr-4 flex flex-row flex-wrap justify-end gap-2 md:gap-4">
                                <x-anchor-secondary href="{{ route('delivery-order.index') }}" tabindex="16">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                    <span class="pl-1">@lang('messages.close')</span>
                                </x-anchor-secondary>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
</x-app-layout>
