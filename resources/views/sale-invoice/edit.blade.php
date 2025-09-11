@php
    use Illuminate\Support\Facades\Crypt;
    if (session('totals')) {
        $totals = session('totals');
    }
@endphp
@section('title', __('messages.saleorder'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('sale-order.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="w-7 h-7" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    data-name="Layer 1">
                    <path
                        d="M21.22,12A3,3,0,0,0,22,10a3,3,0,0,0-3-3H13.82A3,3,0,0,0,11,3H5A3,3,0,0,0,2,6a3,3,0,0,0,.78,2,3,3,0,0,0,0,4,3,3,0,0,0,0,4A3,3,0,0,0,2,18a3,3,0,0,0,3,3H19a3,3,0,0,0,2.22-5,3,3,0,0,0,0-4ZM11,19H5a1,1,0,0,1,0-2h6a1,1,0,0,1,0,2Zm0-4H5a1,1,0,0,1,0-2h6a1,1,0,0,1,0,2Zm0-4H5A1,1,0,0,1,5,9h6a1,1,0,0,1,0,2Zm0-4H5A1,1,0,0,1,5,5h6a1,1,0,0,1,0,2Zm8.69,11.71A.93.93,0,0,1,19,19H13.82a2.87,2.87,0,0,0,0-2H19a1,1,0,0,1,1,1A1,1,0,0,1,19.69,18.71Zm0-4A.93.93,0,0,1,19,15H13.82a2.87,2.87,0,0,0,0-2H19a1,1,0,0,1,1,1A1,1,0,0,1,19.69,14.71Zm0-4A.93.93,0,0,1,19,11H13.82a2.87,2.87,0,0,0,0-2H19a1,1,0,0,1,1,1A1,1,0,0,1,19.69,10.71Z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.sale')</span>
                    <span>@lang('messages.order')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <div class="py-2 flex flex-col">

        <div class="w-full px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('sale-order.partials.feedback')
                </div>

                <form id="master-form" action="{{ route('sale-order.update', Crypt::Encrypt($datas->id)) }}"
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
                                        <input type="hidden" name="branch_id" value="{{ $branch_id }}" />
                                        <span for="customer_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.customer')</span>
                                        <x-text-span>{{ $datas->customer->nama }}</x-text-span>
                                        <div class="hidden">
                                            <select name="customer_id" id="customer_id" tabindex="1" required
                                                autofocus
                                                class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                <option value="">@lang('messages.choose')...</option>
                                                @foreach ($customers as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $datas->customer_id == $id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('customer_id')" />
                                        </div>
                                    </div>

                                    <div class="w-auto pb-4">
                                        <span for="tanggal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.transactiondate')</span>
                                        <x-text-span>{{ date_format(date_create($datas->tanggal), 'd/m/Y') }}</x-text-span>
                                        <div class="hidden">
                                            <x-text-input type="date" name="tanggal" id="tanggal"
                                                data-date-format="dd-mm-yyyy" tabindex="2" required
                                                value="{{ old('tanggal', $datas->tanggal) }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('tanggal')" />
                                        </div>
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="tunai"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.payment')</label>
                                        <select name="tunai" id="tunai" tabindex="3" required
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            <option value="1" {{ $datas->tunai == 1 ? 'selected' : '' }}>
                                                @lang('messages.cash')</option>
                                            <option value="2" {{ $datas->tunai == 2 ? 'selected' : '' }}>
                                                @lang('messages.credit')</option>
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('tunai')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="biaya_angkutan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliverycost')
                                            (Rp.)</label>
                                        <x-text-input type="text" name="biaya_angkutan" id="biaya_angkutan"
                                            tabindex="4"
                                            value="{{ old('biaya_angkutan', $datas->biaya_angkutan) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('biaya_angkutan')" />
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <label for="pajak"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.tax')
                                            (%)</label>
                                        <x-text-input type="number" min="0" step="0.01" name="pajak"
                                            id="pajak" tabindex="4" value="{{ old('pajak', $datas->pajak) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('pajak')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <span for="total_harga"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.totalprice')
                                            (Rp.)</span>
                                        <x-text-span
                                            id="disp-total_harga-master">{{ number_format($totals['total_price'], 0, ',', '.') }}</x-text-span>
                                        <x-text-input type="hidden" name="total_harga" id="total_harga"
                                            value="{{ $totals['total_price'] }}" class="sr-only" />

                                        <x-input-error class="mt-2" :messages="$errors->get('total_harga')" />
                                    </div>

                                    <div class="w-auto pb-4 lg:pb-12">
                                        <span for="no_order"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.ordernumber')</span>
                                        <x-text-span
                                            id="disp-no_order">{{ old('no_order', $datas->no_order) }}</x-text-span>
                                        <x-text-input type="hidden" name="no_order" id="no_order"
                                            value="{{ old('no_order', $datas->no_order) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('no_order')" />
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="dark:bg-black/10">
                                            <label
                                                class="cursor-pointer flex flex-col items-center md:flex-row md:gap-2">
                                                <input type="checkbox" id="isactive" name="isactive"
                                                    class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md"
                                                    {{ $datas->isactive == '1' ? 'checked' : '' }}>
                                                <span
                                                    class="pr-4 group-hover:text-blue-500 transition-colors duration-300 text-right w-1/2 md:w-full">
                                                    @lang('messages.active')
                                                </span>
                                            </label>
                                        </div>

                                        <x-primary-button type="submit" class="block" tabindex="6">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('sale-order.index') }}" tabindex="7">
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

                    <form id="form-order" method="POST" enctype="multipart/form-data" class="w-full">
                        @csrf

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
                                                    <th class="w-1/6">@lang('messages.unitprice') (Rp.)</th>
                                                    <th class="w-auto">@lang('messages.unit')</th>
                                                    <th class="w-auto">@lang('messages.quantity') &amp; @lang('messages.stock')</th>
                                                    {{-- <th class="w-auto">@lang('messages.tax') (%)</th> --}}
                                                    <th class="w-auto">@lang('messages.description')</th>
                                                    <th class="w-1/6">@lang('messages.subtotalprice') (Rp.)</th>
                                                    <th class="w-auto">&nbsp;</th>
                                                </tr>
                                            </thead>

                                            <tbody id="detailBody">
                                                @include('sale-order.partials.details', [
                                                    $details,
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
                                                        <select id="barang_id" name="barang_id" required
                                                            tabindex="10"
                                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                            <option value="">@lang('messages.choose')...</option>
                                                            @foreach ($barangs as $id => $name)
                                                                <option value="{{ $id }}">
                                                                    {{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-input type="number" min="0" id="harga_satuan"
                                                            name="harga_satuan" required tabindex="11" />
                                                    </td>
                                                    <td class="align-top">
                                                        <select id="satuan_id" name="satuan_id" required
                                                            tabindex="12"
                                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                            <option value="">@lang('messages.choose')...</option>
                                                            @foreach ($satuans as $id => $name)
                                                                <option value="{{ $id }}">
                                                                    {{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="align-top">
                                                        <div class="flex flex-row gap-1">
                                                            <x-text-input type="number" min="0"
                                                                id="kuantiti" name="kuantiti" required
                                                                tabindex="13" />
                                                            <input type="hidden" id="stock" name="stock" />
                                                            <x-text-span id="disp-stock"
                                                                class="text-right text-gray-900 bg-primary-50 dark:text-white dark:bg-primary-800" />
                                                        </div>
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-input type="text" id="keterangan"
                                                            name="keterangan" required tabindex="14" />
                                                    </td>
                                                    {{-- <td class="align-top">
                                                        <x-text-input type="number" min="0" id="pajak" name="pajak"
                                                            tabindex="14" disabled />
                                                    </td> --}}
                                                    <td class="align-top">
                                                        <x-text-span id="disp-sub_harga"
                                                            class="text-right">0</x-text-span>
                                                    </td>
                                                </tr>
                                            </tbody>

                                            <tfoot>
                                                <tr>
                                                    <td class="align-top text-center" colspan="5">
                                                        <x-text-span class="font-extrabold">@lang('messages.totalprice')
                                                            (Rp.)</x-text-span>
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-span id="disp-total_harga-detail"
                                                            class="font-extrabold text-right">{{ number_format($totals['sub_price'], 0, ',', '.') }}</x-text-span>
                                                    </td>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>

                                    <div class="mt-4 mb-4 mr-4 flex flex-row flex-wrap justify-end gap-2 md:gap-4">
                                        <x-primary-button id="submit-detail" tabindex="15">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('sale-order.index') }}" tabindex="16">
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

            <div id="scanner" class="fixed bottom-0 left-0">
                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    @php $element = ['el' => 'barang_id']; @endphp
                    {{-- @include('qrcode.partials.scanner', $element) --}}
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-4 px-4 py-2">
            <div class="w-full">
                <div class="flex flex-col items-center">

                    <form id="adonan-form" method="POST" enctype="multipart/form-data" class="w-full">
                        @csrf

                        {{-- Adonan --}}
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
                                                    <th class="w-1/6">@lang('messages.partner')</th>
                                                    <th class="w-1/6">@lang('messages.goods')</th>
                                                    <th class="w-auto">@lang('messages.unitprice') (Rp.)</th>
                                                    <th class="w-1/12">@lang('messages.unit')</th>
                                                    <th class="w-auto">@lang('messages.quantity')</th>
                                                    {{-- <th class="w-auto">@lang('messages.tax') (%)</th> --}}
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
                                                            @foreach ($pegawais as $pegawai)
                                                                <option value="{{ $pegawai->id }}">
                                                                    {{ $pegawai->nama }}</option>
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
                                                        <x-text-input type="number" min="0"
                                                            id="harga_satuan_adonan" name="harga_satuan_adonan"
                                                            required tabindex="19" />
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
                                                        <x-text-input type="number" min="0"
                                                            id="kuantiti_adonan" name="kuantiti_adonan" required
                                                            tabindex="21" />
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-input type="text" id="keterangan_adonan"
                                                            name="keterangan_adonan" tabindex="22" />
                                                    </td>
                                                    {{-- <td class="align-top">
                                                        <x-text-input type="number" min="0" id="pajak_adonan" name="pajak_adonan"
                                                            tabindex="23" disabled />
                                                    </td> --}}
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
        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="{{ url('js/jquery.maskMoney.min.js') }}"></script>
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

                $(function() {
                    $('#total_harga').maskMoney({
                        prefix: 'Rp. ',
                        allowNegative: false,
                        allowZerro: true,
                        thousands: '.',
                        decimal: ',',
                        precision: 0,
                    });
                    $('#biaya_angkutan').maskMoney({
                        prefix: 'Rp. ',
                        allowNegative: false,
                        allowZerro: true,
                        thousands: '.',
                        decimal: ',',
                        precision: 0,
                    });

                    $('#gambar').change(function() {
                        let reader = new FileReader();
                        reader.onload = (e) => {
                            $('#image-preview').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(this.files[0]);
                    });
                })

                deleteAdonan = function(detailId) {
                    let idname = '#a-delete-adonan-' + detailId;

                    var confirmation = confirm("Are you sure you want to delete this?");
                    if (confirmation) {
                        $(idname).closest("tr").remove();
                        $.ajax({
                            url: '{{ url('/sale/order/delete-adonan') }}' + '/' + detailId,
                            type: 'delete',
                            dataType: 'json',
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(result) {
                                if (result.status !== 'Not Found') {
                                    $('#adonanBody').html(result.view);
                                    flasher.error("{{ __('messages.successdeleted') }}!", "Success");
                                }
                                $('#adonan-form')[0].reset();
                                $('#disp-total_harga-master').html(result.total_harga_master
                                    .toLocaleString('de-DE'));
                                $('#disp-total_harga-adonan').html(result.total_harga_adonan
                                    .toLocaleString('de-DE'));
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                };

                deleteDetail = function(detailId) {
                    let idname = '#a-delete-detail-' + detailId;

                    var confirmation = confirm("Are you sure you want to delete this?");
                    if (confirmation) {
                        $(idname).closest("tr").remove();
                        $.ajax({
                            url: '{{ url('/sale/order/delete-detail') }}' + '/' + detailId,
                            type: 'delete',
                            dataType: 'json',
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(result) {
                                if (result.status !== 'Not Found') {
                                    $('#detailBody').html(result.view);
                                    flasher.error("{{ __('messages.successdeleted') }}!", "Success");
                                }
                                $('#form-order')[0].reset();
                                $('#disp-total_harga-master').html(result.total_harga_master
                                    .toLocaleString('de-DE'));
                                $('#disp-total_harga-detail').html(result.total_harga_detail
                                    .toLocaleString('de-DE'));
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                };

                $("#harga_satuan_adonan, #kuantiti_adonan, #pajak_adonan").on("change keyup paste", function() {
                    var _xhs = $('#harga_satuan_adonan').val();
                    var _xku = $('#kuantiti_adonan').val();
                    var _xpj = $('#pajak_adonan').val();
                    var xhs = (_xhs > 0) ? _xhs : 0;
                    var xku = (_xku > 0) ? _xku : 0;
                    var xpj = (_xpj > 0) ? _xpj : 0;
                    var xsub = (xhs * (1 + (xpj / 100))) * xku;
                    var formattedNumber = new Intl.NumberFormat('de-DE').format(xsub);

                    $("#disp-sub_harga-adonan").html(formattedNumber);
                });

                $("#harga_satuan, #kuantiti, #pajak").on("change keyup paste", function() {
                    var _xhs = $('#harga_satuan').val();
                    var _xku = $('#kuantiti').val();
                    var _xst = $('#stock').val();
                    var _xpj = $('#pajak').val();
                    var xhs = (_xhs > 0) ? _xhs : 0;
                    var xku = (_xku > 0) ? _xku : 0;
                    var xst = (_xst > 0) ? _xst : 0;
                    var xpj = (_xpj > 0) ? _xpj : 0;
                    var xsub = (xhs * (1 + (xpj / 100))) * xku;
                    var formattedNumber = new Intl.NumberFormat('de-DE').format(xsub);

                    $("#disp-sub_harga").html(formattedNumber);

                    if ((xku * 1) > (xst * 1)) {
                        $("#disp-stock")
                            .removeClass("text-gray-900 bg-primary-50 dark:text-white dark:bg-primary-800")
                            .addClass("text-white bg-red-700 dark:text-white dark:bg-red-700");
                    } else {
                        $("#disp-stock")
                            .removeClass("text-white bg-red-700 dark:text-white dark:bg-red-700")
                            .addClass("text-gray-900 bg-primary-50 dark:text-white dark:bg-primary-800");
                    }
                });

                $("#barang_id_adonan").on("change keyup paste", function() {
                    var xbar = $('#barang_id_adonan option:selected').val();

                    $.ajax({
                        url: '{{ url('/warehouse/goods/get-goods-sell') }}' + "/" + xbar,
                        type: "GET",
                        dataType: 'json',
                        success: function(result) {
                            var p1 = result.p1;
                            var p2 = result.p2;
                            $('#harga_satuan_adonan').val(p1);
                            $('#satuan_id_adonan').val(p2);
                            $('#kuantiti_adonan').focus();
                        }
                    });
                });

                $("#barang_id").on("change keyup paste", function() {
                    var xbar = $('#barang_id option:selected').val();

                    $.ajax({
                        url: '{{ url('/warehouse/goods/get-goods-sell') }}' + "/" + xbar,
                        type: "GET",
                        dataType: 'json',
                        success: function(result) {
                            var p1 = result.p1;
                            var p2 = result.p2;
                            var p3 = result.p3;
                            $('#harga_satuan').val(p1);
                            $('#satuan_id').val(p2);
                            $('#stock').val(p3);
                            $('#disp-stock').html(p3.toLocaleString('de-DE'));
                            $('#kuantiti').focus();
                        }
                    });
                });

                $("#submit-adonan").on("click", function(e) {
                    e.preventDefault();
                    let key = $('#order_id').val();

                    $.ajax({
                        url: '{{ url('/sale/order/store-adonan') }}' + '/' + key,
                        type: 'post',
                        dataType: 'json',
                        data: $('form#adonan-form').serialize(),
                        success: function(result) {
                            if (result.status !== 'Not Found') {
                                $('#adonanBody').html(result.view);
                                $('#disp-total_harga-master').html(result.total_harga_master
                                    .toLocaleString('de-DE'));
                                $('#disp-total_harga-adonan').html(result.total_harga_adonan
                                    .toLocaleString('de-DE'));
                                $('#adonan-form')[0].reset();
                                $("#disp-sub_harga-adonan").html(0);
                                flasher.success("{{ __('messages.successsaved') }}!", "Success");
                            }
                        }
                    });

                    if (isFormDirty('master-form', myFormInitialValues)) {
                        $('form#master-form').submit();
                    }
                });

                $("#submit-detail").on("click", function(e) {
                    e.preventDefault();
                    let key = $('#order_id').val();

                    // let data = $("form#form-order").serializeArray();
                    // let key = data[2].value;
                    // jQuery.each(data, function(i, data) {});

                    $.ajax({
                        url: '{{ url('/sale/order/store-detail') }}' + '/' + key,
                        type: 'post',
                        dataType: 'json',
                        data: $('form#form-order').serialize(),
                        success: function(result) {
                            if (result.status !== 'Not Found') {
                                $('#detailBody').html(result.view);
                                $('#disp-total_harga-master').html(result.total_harga_master
                                    .toLocaleString('de-DE'));
                                $('#disp-total_harga-detail').html(result.total_harga_detail
                                    .toLocaleString('de-DE'));
                                $('#form-order')[0].reset();
                                $("#disp-stock").html(0);
                                $("#disp-sub_harga").html(0);
                                flasher.success("{{ __('messages.successsaved') }}!", "Success");
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

{{-- const mySelect = document.getElementById('mySelectElement');
const indexToSelect = 1; // The index of the option to select (0-based)

mySelect.selectedIndex = indexToSelect; --}}
