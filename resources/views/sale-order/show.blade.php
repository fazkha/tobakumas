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
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.sale')</span>
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
                    @include('sale-order.partials.feedback')
                </div>

                {{-- Master --}}
                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-4 space-y-2">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <label for="customer_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.customer')</label>
                                    <x-text-span>{{ $datas->customer->nama }}</x-text-span>
                                </div>

                                <div class="flex flex-row gap-2">
                                    <div class="w-1/3 pb-4">
                                        <label for="hke"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.hke')</label>
                                        <x-text-span>{{ $datas->hke }}</x-text-span>
                                    </div>

                                    <div class="w-2/3 pb-4">
                                        <label for="tanggal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.transactiondate')</label>
                                        <x-text-span>{{ date('d/m/Y', strtotime($datas->tanggal)) }}</x-text-span>
                                    </div>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="tunai"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.payment')</label>
                                    <x-text-span>{{ $datas->tunai == 1 ? __('messages.cash') : __('messages.credit') }}</x-text-span>
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

                                <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                    <div class="pr-2">
                                        <div class="inline-flex items-center">
                                            @if ($datas->isactive == '1')
                                                <span>✔️</span>
                                            @endif
                                            @if ($datas->isactive == '0')
                                                <span>❌</span>
                                            @endif
                                            <span class='pl-2'>@lang('messages.active')</span>
                                        </div>
                                    </div>

                                    <x-anchor-secondary href="{{ route('sale-order.index') }}" tabindex="1" autofocus>
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
                                                <th class="w-auto text-center">
                                                    <div class="flex items-center justify-center">
                                                        <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 256 256"
                                                            id="Flat" xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M128,20A108,108,0,1,0,236,128,108.12186,108.12186,0,0,0,128,20Zm0,192a84,84,0,1,1,84-84A84.09562,84.09562,0,0,1,128,212ZM144,84v92a12,12,0,0,1-24,0V106.417l-5.3457,3.5625a12.00027,12.00027,0,1,1-13.3086-19.97265l24-15.99317A12.00071,12.00071,0,0,1,144,84Z" />
                                                        </svg>
                                                    </div>
                                                </th>
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
                                            <tr>
                                                <td>
                                                    <div class="flex flex-row gap-6 items-center">
                                                        <div class="flex flex-row gap-2 items-center">
                                                            <svg fill="currentColor" class="w-5 h-5"
                                                                viewBox="0 0 256 256" id="Flat"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M128,20A108,108,0,1,0,236,128,108.12186,108.12186,0,0,0,128,20Zm0,192a84,84,0,1,1,84-84A84.09562,84.09562,0,0,1,128,212ZM144,84v92a12,12,0,0,1-24,0V106.417l-5.3457,3.5625a12.00027,12.00027,0,1,1-13.3086-19.97265l24-15.99317A12.00071,12.00071,0,0,1,144,84Z" />
                                                            </svg>
                                                            <span>@lang('messages.packaging')</span>
                                                        </div>
                                                    </div>
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

        <div class="flex flex-col lg:flex-row gap-4 px-4 py-2">
            <div class="w-full">
                <div class="flex flex-col items-center">

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
                                    <table id="adonan_table" class="w-full border-separate border-spacing-2">
                                        <thead>
                                            <tr>
                                                <th class="w-1/6">@lang('messages.partner')</th>
                                                <th class="w-1/6">@lang('messages.goods')</th>
                                                <th class="w-auto">@lang('messages.unitprice') (Rp.)</th>
                                                <th class="w-1/12">@lang('messages.unit')</th>
                                                <th class="w-auto">@lang('messages.quantity')</th>
                                                {{-- <th class="w-auto">@lang('messages.tax') (%)</th> --}}
                                                <th class="w-auto text-center">
                                                    <div class="flex items-center justify-center">
                                                        <svg fill="currentColor" class="w-5 h-5"
                                                            viewBox="0 0 256 256" id="Flat"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M128,20A108,108,0,1,0,236,128,108.12186,108.12186,0,0,0,128,20Zm0,192a84,84,0,1,1,84-84A84.09562,84.09562,0,0,1,128,212ZM144,84v92a12,12,0,0,1-24,0V106.417l-5.3457,3.5625a12.00027,12.00027,0,1,1-13.3086-19.97265l24-15.99317A12.00071,12.00071,0,0,1,144,84Z" />
                                                        </svg>
                                                    </div>
                                                </th>
                                                <th class="w-auto text-center">
                                                    <div class="flex items-center justify-center">
                                                        <svg fill="currentColor" class="w-5 h-5"
                                                            viewBox="0 0 256 256" id="Flat"
                                                            xmlns="http://www.w3.org/2000/svg">
                                                            <path
                                                                d="M128,20A108,108,0,1,0,236,128,108.12186,108.12186,0,0,0,128,20Zm0,192a84,84,0,1,1,84-84A84.09562,84.09562,0,0,1,128,212Zm29.50391-87.38477-29.51075,39.37891H152a12,12,0,0,1,0,24H104.39648c-.13281.00488-.26464.00684-.39843.00684a12.00272,12.00272,0,0,1-9.47168-19.36914l43.56543-58.13379a12.00426,12.00426,0,1,0-21.1543-11.165A11.9998,11.9998,0,0,1,94.834,89.9834a36.00408,36.00408,0,1,1,63.01172,34.15234C157.73535,124.29883,157.62207,124.458,157.50391,124.61523Z" />
                                                        </svg>
                                                    </div>
                                                </th>
                                                <th class="w-1/12">@lang('messages.description')</th>
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
                                            <tr>
                                                <td>
                                                    <div class="flex flex-row gap-6 items-center">
                                                        <div class="flex flex-row gap-2 items-center">
                                                            <svg fill="currentColor" class="w-5 h-5"
                                                                viewBox="0 0 256 256" id="Flat"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M128,20A108,108,0,1,0,236,128,108.12186,108.12186,0,0,0,128,20Zm0,192a84,84,0,1,1,84-84A84.09562,84.09562,0,0,1,128,212ZM144,84v92a12,12,0,0,1-24,0V106.417l-5.3457,3.5625a12.00027,12.00027,0,1,1-13.3086-19.97265l24-15.99317A12.00071,12.00071,0,0,1,144,84Z" />
                                                            </svg>
                                                            <span>@lang('messages.production')</span>
                                                        </div>
                                                        <div class="flex flex-row gap-2 items-center">
                                                            <svg fill="currentColor" class="w-5 h-5"
                                                                viewBox="0 0 256 256" id="Flat"
                                                                xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M128,20A108,108,0,1,0,236,128,108.12186,108.12186,0,0,0,128,20Zm0,192a84,84,0,1,1,84-84A84.09562,84.09562,0,0,1,128,212Zm29.50391-87.38477-29.51075,39.37891H152a12,12,0,0,1,0,24H104.39648c-.13281.00488-.26464.00684-.39843.00684a12.00272,12.00272,0,0,1-9.47168-19.36914l43.56543-58.13379a12.00426,12.00426,0,1,0-21.1543-11.165A11.9998,11.9998,0,0,1,94.834,89.9834a36.00408,36.00408,0,1,1,63.01172,34.15234C157.73535,124.29883,157.62207,124.458,157.50391,124.61523Z" />
                                                            </svg>
                                                            <span>@lang('messages.packaging')</span>
                                                        </div>
                                                    </div>
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

    @push('scripts')
    @endpush
</x-app-layout>
