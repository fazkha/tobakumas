@php
    use Illuminate\Support\Facades\Crypt;
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
                <span class="px-2">@lang('messages.saleorder')</span>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.delete')</span>
        </h1>
    </div>

    <div class="py-4 flex flex-col">

        <div class="container mx-auto px-2 sm:px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-1/2 shadow mb-5" role="alert">
                    <form action="{{ route('sale-order.destroy', Crypt::Encrypt($datas->id)) }}" class="block"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('DELETE')

                        <div class="flex">
                            <div class="bg-red-600 w-16 text-center p-2">
                                <div class="flex justify-center h-full items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                            </div>
                            <div class="bg-white border-r-4 border-red-600 w-full p-4">
                                <div>
                                    <p class="text-gray-600 font-bold">@lang('messages.confirm')</p>
                                    <p class="text-gray-600 font-bold text-sm">@lang('messages.deleteitemwarning').</p>
                                    <p class="text-gray-600 text-sm mb-5">@lang('messages.deleteitemconfirm')?</p>
                                    <div class="flex justify-between">
                                        <x-primary-button type="submit"
                                            class="bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.delete')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('sale-order.index') }}" tabindex="1"
                                            autofocus>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.cancel')</span>
                                        </x-anchor-secondary>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                {{-- Master --}}
                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <label for="customer_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.customer')</label>
                                    <x-text-span>{{ $datas->customer->nama }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="tanggal"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.transactiondate')</label>
                                    <x-text-span>{{ date('d/m/Y', strtotime($datas->tanggal)) }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="tunai"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.payment')</label>
                                    <x-text-span>{{ $datas->tunai === 1 ? __('messages.cash') : __('messages.credit') }}</x-text-span>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="w-auto pb-4">
                                    <label for="biaya_angkutan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliverycost')
                                        (Rp.)</label>
                                    <x-text-span>{{ number_format($datas->biaya_angkutan, 0, ',', '.') }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="total_harga"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.totalprice')
                                        (Rp.)</label>
                                    <x-text-span
                                        id="disp-total_harga-master">{{ number_format($totals['total_price'], 0, ',', '.') }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4 lg:pb-12">
                                    <label for="no_order"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.ordernumber')</label>
                                    <x-text-span id="disp-no_order">{{ $datas->no_order }}</x-text-span>
                                </div>

                                {{-- <div class="pb-4 lg:pb-12">
                                            <label for="gambar"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Picture</label>
                                            <x-text-input type="file" name="gambar" id="gambar" tabindex="6"
                                                accept=".jpg,.jpeg" placeholder="Choose Image"
                                                class="!rounded-none border" />
    
                                            <x-input-error class="mt-2" :messages="$errors->get('gambar')" />
    
                                            <div class="mt-2 flex justify-center">
                                                <img id="image-preview" class="w-full lg:w-3/4 h-auto border rounded-lg"
                                                    src="/images/0cd6be830e32f80192d496e50cfa9dbc.jpg" alt="o.o">
                                            </div>
                                        </div> --}}

                                <div class="flex flex-row items-center justify-end gap-4">
                                    <div class="pr-2">
                                        <div class="inline-flex items-center">
                                            @if ($datas->isactive == '1')
                                                <span>✔️</span>
                                            @endif
                                            @if ($datas->isactive == '0')
                                                <span>❌</span>
                                            @endif
                                            <label class='pl-2'>@lang('messages.active')</label>
                                        </div>
                                    </div>

                                    <x-anchor-secondary href="{{ route('sale-order.index') }}" tabindex="2">
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

        <div class="flex flex-col lg:flex-row justify-evenly">
            <div class="w-full px-2 sm:px-4 py-2">
                <div class="flex flex-col items-center">

                    {{-- Detail --}}
                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2 sm:p-6">
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
                                <div class="p-4 overflow-scroll md:overflow-hidden">
                                    <table id="order_table" class="w-full border-separate border-spacing-2">
                                        <thead>
                                            <tr>
                                                <th class="w-1/4">@lang('messages.goods')</th>
                                                <th class="w-1/6">@lang('messages.unitprice') (Rp.)</th>
                                                <th class="w-auto">@lang('messages.unit')</th>
                                                <th class="w-auto">@lang('messages.quantity') &amp; @lang('messages.stock')</th>
                                                <th class="w-auto">@lang('messages.short-packaging')</th>
                                                <th class="w-auto">@lang('messages.description')</th>
                                                {{-- <th class="w-auto">@lang('messages.tax') (%)</th> --}}
                                                <th class="w-1/6">@lang('messages.subtotalprice') (Rp.)</th>
                                            </tr>
                                        </thead>

                                        <tbody id="detailBody">
                                            @include('sale-order.partials.details-status', [
                                                $details,
                                                'viewMode' => true,
                                            ])
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <td class="align-top text-center" colspan="6">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row justify-evenly">
            <div class="w-full px-2 sm:px-4 py-2">
                <div class="flex flex-col items-center">

                    {{-- Adonan --}}
                    <div
                        class="w-full shadow-lg rounded-md border bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-6 space-y-2 sm:p-8">
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
                                <div class="p-4 overflow-hidden">
                                    <table id="order_table" class="w-full border-separate border-spacing-2">
                                        <thead>
                                            <tr>
                                                <th class="w-1/6">@lang('messages.partner')</th>
                                                <th class="w-1/6">@lang('messages.goods')</th>
                                                <th class="w-auto">@lang('messages.unitprice') (Rp.)</th>
                                                <th class="w-1/12">@lang('messages.unit')</th>
                                                <th class="w-auto">@lang('messages.quantity')</th>
                                                {{-- <th class="w-auto">@lang('messages.tax') (%)</th> --}}
                                                <th class="w-auto">@lang('messages.short-production')</th>
                                                <th class="w-auto">@lang('messages.short-packaging')</th>
                                                <th class="w-auto">@lang('messages.description')</th>
                                                <th class="w-1/6">@lang('messages.subtotalprice') (Rp.)</th>
                                            </tr>
                                        </thead>

                                        <tbody id="adonanBody">
                                            @include('sale-order.partials.details-adonan-status', [
                                                $adonans,
                                                'viewMode' => true,
                                            ])
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <td class="align-top text-center" colspan="8">
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
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
