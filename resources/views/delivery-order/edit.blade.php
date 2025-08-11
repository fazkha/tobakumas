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
                <span class="px-2">@lang('messages.deliveryorder')</span>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <div x-data="{ buttonDisabled: {{ $datas->order->ispackaged == 1 ? 'true' : 'false' }} }" class="py-2 flex flex-col">

        <div class="w-full px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('delivery-order.partials.feedback')
                </div>

                <form id="master-form" action="{{ route('delivery-order.update', Crypt::Encrypt($datas->id)) }}"
                    method="POST" enctype="multipart/form-data" class="w-full">
                    @csrf
                    @method('PUT')

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
                                        <x-text-input type="date" name="tanggal" id="tanggal"
                                            data-date-format="dd-mm-yyyy" tabindex="1" autofocus required
                                            value="{{ $datas->tanggal }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('tanggal')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="alamat"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliveryaddress')</label>
                                        <x-text-input type="text" name="alamat" id="alamat" tabindex="2"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.address') }}"
                                            value="{{ $datas->alamat }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                        <x-text-input type="text" name="keterangan" id="keterangan" tabindex="3"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.description') }}"
                                            value="{{ $datas->keterangan }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <label for="petugas_1_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.officer')
                                            1</label>
                                        <select name="petugas_1_id" id="petugas_1_id" tabindex="4"
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($petugas as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $datas->petugas_1_id === $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('petugas_1_id')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="petugas_2_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.officer')
                                            2</label>
                                        <select name="petugas_2_id" id="petugas_2_id" tabindex="5"
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($petugas as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $datas->petugas_2_id === $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('petugas_2_id')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="pengirim_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliveryman')</label>
                                        <select name="pengirim_id" id="pengirim_id" tabindex="5"
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($petugas as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $datas->pengirim_id === $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('pengirim_id')" />
                                    </div>

                                    <div class="w-auto pb-4 lg:pb-12">
                                        <label for="tanggungjawab_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.supervisor')</label>
                                        <select name="tanggungjawab_id" id="tanggungjawab_id" tabindex="6"
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($petugas as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $datas->tanggungjawab_id === $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('tanggungjawab_id')" />
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="pr-2">
                                            <div class="inline-flex items-center">
                                                <span x-show="buttonDisabled">✔️</span>
                                                <span x-show="!buttonDisabled">❌</span>
                                                <label class='pl-2'>@lang('messages.packagedfinish')</label>
                                            </div>
                                        </div>

                                        <x-primary-button type="submit" class="block" tabindex="7"
                                            x-bind:disabled="buttonDisabled">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('delivery-order.index') }}"
                                            tabindex="8">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
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
                </form>
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
                                        $kemasans = App\Models\PaketDetail::where('paket_id', $id)->get();
                                    @endphp
                                    <div
                                        class="flex flex-row items-center justify-start shadow rounded-md border border-solid border-primary-100 dark:border-primary-800">
                                        <div
                                            class="px-4 py-2 border border-primary-100 bg-primary-20 dark:border-primary-800 dark:bg-primary-850">
                                            <span class="text-sm font-bold">{{ $name }}</span>
                                        </div>
                                        <div class="px-4 py-2 flex flex-col gap-2">
                                            @foreach ($kemasans as $kemasan)
                                                <span
                                                    class="text-sm">{{ '📦 ' . $kemasan->barang->nama . ': ' . number_format($kemasan->kuantiti, 0, ',', '.') . ' ' . $kemasan->satuan->singkatan }}</span>
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

                    <form id="detail-form"
                        action="{{ route('delivery-order-detail.update', Crypt::Encrypt($datas->id)) }}"
                        method="POST" enctype="multipart/form-data" class="w-full">
                        @csrf
                        @method('PUT')

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
                                                    <th class="w-1/4">@lang('messages.goods')</th>
                                                    <th class="w-auto">@lang('messages.quantity')</th>
                                                    <th class="w-auto">@lang('messages.unit')</th>
                                                    <th class="w-auto">@lang('messages.description')</th>
                                                    <th class="w-1/6">@lang('messages.packagegroup')</th>
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
                                                                <input type="hidden" id="detail_id"
                                                                    name="detail_id[]" value="{{ $detail->id }}" />
                                                                <x-text-span>{{ $detail->order_detail->barang->nama }}</x-text-span>
                                                            </td>
                                                            <td class="align-top text-right">
                                                                <x-text-span>{{ number_format($detail->order_detail->kuantiti, 2, ',', '.') }}</x-text-span>
                                                            </td>
                                                            <td class="align-top">
                                                                <x-text-span>{{ $detail->order_detail->satuan->singkatan }}</x-text-span>
                                                            </td>
                                                            <td class="align-top">
                                                                <x-text-span>{{ $detail->order_detail->keterangan ? $detail->order_detail->keterangan : '-' }}</x-text-span>
                                                            </td>
                                                            <td class="align-top">
                                                                <select name="paket_id[]" tabindex="9"
                                                                    class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                                    <option value="">@lang('messages.choose')...
                                                                    </option>
                                                                    @foreach ($pakets as $id => $name)
                                                                        <option value="{{ $id }}"
                                                                            {{ $detail->paket_id === $id ? 'selected' : '' }}>
                                                                            {{ $name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="align-top">
                                                                <select name="barang_id[]" tabindex="10"
                                                                    class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                                    <option value="">@lang('messages.choose')...
                                                                    </option>
                                                                    @foreach ($barangs as $id => $name)
                                                                        <option value="{{ $id }}"
                                                                            {{ $detail->barang_id === $id ? 'selected' : '' }}>
                                                                            {{ $name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="align-top">
                                                                <select name="satuan_id[]" tabindex="11"
                                                                    class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                                    <option value="">@lang('messages.choose')...
                                                                    </option>
                                                                    @foreach ($satuans as $id => $name)
                                                                        <option value="{{ $id }}"
                                                                            {{ $detail->satuan_id === $id ? 'selected' : '' }}>
                                                                            {{ $name }}</option>
                                                                    @endforeach
                                                                </select>
                                                            </td>
                                                            <td class="align-top">
                                                                <x-text-input type="number" min="0"
                                                                    name="kuantiti" id="kuantiti" tabindex="3"
                                                                    value="{{ $detail->kuantiti }}" />
                                                            </td>
                                                        </tr>
                                                    @endforeach
                                                @endif
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-4 mb-4 mr-4 flex flex-row flex-wrap justify-end gap-2 md:gap-4">
                                        <x-primary-button tabindex="10">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('delivery-order.index') }}"
                                            tabindex="11">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.close')</span>
                                        </x-anchor-secondary>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        {{-- <div class="flex flex-col lg:flex-row gap-4 px-4 py-2">
            <div class="w-full">
                <div class="flex flex-col items-center">

                    <form id="adonan-form" method="POST" enctype="multipart/form-data" class="w-full">
                        @csrf

                        <div
                            class="w-full shadow-lg rounded-md border bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                            <div class="p-4 space-y-2">
                                <div class="flex flex-row
                            items-center gap-2">
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
                                                    <th class="w-1/6">@lang('messages.partner')</th>
                                                    <th class="w-1/6">@lang('messages.goods')</th>
                                                    <th class="w-auto">@lang('messages.unitprice') (Rp.)</th>
                                                    <th class="w-1/12">@lang('messages.unit')</th>
                                                    <th class="w-auto">@lang('messages.quantity')</th>
                                                    <th class="w-auto">@lang('messages.description')</th>
                                                    <th class="w-1/6">@lang('messages.subtotalprice') (Rp.)</th>
                                                </tr>
                                            </thead>

                                            <tbody id="adonanBody">
                                                @include('sale-order.partials.details-adonan', [
                                                    $adonans,
                                                    'viewMode' => false,
                                                ])
                                            </tbody>

                                            <tbody>
                                                <tr>
                                                    <td class="align-top">
                                                        <input type="hidden" name="branch_id"
                                                            value="{{ $branch_id }}" />
                                                        <input type="hidden" id="order_id" name="order_id"
                                                            value="{{ $datas->id }}" />
                                                        <select id="pegawai_id" name="pegawai_id" required
                                                            tabindex="18"
                                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                            <option value="">@lang('messages.choose')...</option>
                                                            @foreach ($pegawais as $id => $name)
                                                                <option value="{{ $id }}">
                                                                    {{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="align-top">
                                                        <select id="barang_id_adonan" name="barang_id_adonan" required
                                                            tabindex="18"
                                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                            <option value="">@lang('messages.choose')...</option>
                                                            @foreach ($barang2s as $id => $name)
                                                                <option value="{{ $id }}">
                                                                    {{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-input type="number" min="0" id="harga_satuan_adonan"
                                                            name="harga_satuan_adonan" required tabindex="19" />
                                                    </td>
                                                    <td class="align-top">
                                                        <select id="satuan_id_adonan" name="satuan_id_adonan" required
                                                            tabindex="20"
                                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                            <option value="">@lang('messages.choose')...</option>
                                                            @foreach ($satuans as $id => $name)
                                                                <option value="{{ $id }}">
                                                                    {{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-input type="number" min="0" id="kuantiti_adonan"
                                                            name="kuantiti_adonan" required tabindex="21" />
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-input type="text" id="keterangan_adonan"
                                                            name="keterangan_adonan" tabindex="22" />
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-span id="disp-sub_harga-adonan"
                                                            class="text-right">0</x-text-span>
                                                    </td>
                                                </tr>
                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                    <td class="align-top text-center" colspan="6">
                                                        <x-text-span class="font-extrabold">@lang('messages.totalprice')
                                                            (Rp.)</x-text-span>
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-span id="disp-total_harga-adonan"
                                                            class="font-extrabold text-right">{{ number_format($totals['sub_price_adonan'], 0, ',', '.') }}</x-text-span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="mt-4 mb-4 mr-4 flex flex-row flex-wrap justify-end gap-2 md:gap-4">
                                        <x-primary-button id="submit-adonan" tabindex="23">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('sale-order.index') }}" tabindex="24">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.close')</span>
                                        </x-anchor-secondary>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div> --}}
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function(e) {
                function getInitialFormValues(formId) {
                    const form = document.getElementById(formId);
                    const initialValues = {};
                    for (let i = 0; i < form.elements.length; i++) {
                        const element = form.elements[i];
                        if (element.name) {
                            if (element.type === 'checkbox' || element.type === 'radio') {
                                initialValues[element.name] = element.checked;
                            } else {
                                initialValues[element.name] = element.value;
                            }
                        }
                    }
                    return initialValues;
                }

                function isFormDirty(formId, initialValues) {
                    const form = document.getElementById(formId);
                    for (let i = 0; i < form.elements.length; i++) {
                        const element = form.elements[i];
                        if (element.name) {
                            let currentValue;
                            if (element.type === 'checkbox' || element.type === 'radio') {
                                currentValue = element.checked;
                            } else {
                                currentValue = element.value;
                            }

                            if (initialValues[element.name] !== currentValue) {
                                return true;
                            }
                        }
                    }
                    return false;
                }

                const myFormInitialValues = getInitialFormValues('master-form');
                const status_order_packaged = '{{ $datas->order->ispackaged }}';

                if (status_order_packaged === '1') {
                    $('#targetDiv').removeClass('hidden');
                    $('#targetDiv').addClass('block');
                }

                $("#submit-combine").on("click", function(e) {
                    e.preventDefault();
                    var xkey = '{{ $datas->id }}';

                    $("input[name^='order']:checked").map(function() {
                        var xjoin = $(this).val();
                        var xstat = $('#prod_status').val();

                        if (xjoin && xstat !== '0') {
                            $.ajax({
                                url: '{{ url('/delivery/order/combine') }}' + "/" + xkey +
                                    "/" + xjoin,
                                type: "get",
                                dataType: 'json',
                                success: function(result) {
                                    if (result.status !== 'Not Found') {
                                        $('#bahanBody').html(result.view3);
                                        $('#combineBody').html(result.view2);
                                        $('#detailBody').html(result.view);
                                        alert('{{ __('messages.combinesuccess') }}')
                                    }
                                }
                            });
                        }
                    }).get();
                });

                $("#submit-detail").on("click", function(e) {
                    e.preventDefault();
                    let key = '{{ $datas->id }}';

                    $.ajax({
                        url: '{{ url('/delivery/order/finish-order') }}' + '/' + key,
                        type: 'get',
                        dataType: 'json',
                        success: function(result) {
                            if (result.status !== 'Not Found') {
                                $('#targetDiv').removeClass('hidden');
                                $('#targetDiv').addClass('block');
                                alert('{{ __('messages.finisheddelivery') }}')
                            }
                        }
                    });

                    if (isFormDirty('master-form', myFormInitialValues)) {
                        $('form#master-form').submit();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
