@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@section('title', __('messages.purchaseorder'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('purchase-order.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="w-7 h-7" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M533.959 424.126v242.812c0 12.162-9.773 22.022-21.829 22.022s-21.829-9.859-21.829-22.022V424.126h-6.654c-1.886.2-3.8.303-5.737.303h-82.373c-156.731 0-283.783-128.17-283.783-286.28 0-76.3 61.313-138.152 136.947-138.152 118.246 0 219.599 72.954 262.243 176.679C553.588 72.951 654.941-.003 773.187-.003c75.634 0 136.947 61.852 136.947 138.152 0 158.11-127.052 286.28-283.783 286.28h-82.373a54.39 54.39 0 01-5.737-.303h-4.28zm-53.538-44.043c4.774-1.168 8.403-5.572 8.403-10.708v-83.098c0-133.785-107.505-242.237-240.124-242.237-51.522 0-93.288 42.133-93.288 94.109 0 132.025 104.695 239.379 234.903 242.18a21.87 21.87 0 013.278-.247h86.828zm145.322.303h.608c132.619 0 240.124-108.451 240.124-242.237 0-51.975-41.766-94.109-93.288-94.109-132.619 0-240.124 108.451-240.124 242.237v83.098c0 5.136 3.628 9.54 8.403 10.708h80.65c1.236 0 2.448.104 3.628.303zM937.456 751.78c-74.665 64.718-237.417 105.999-425.511 105.999-188.128 0-350.904-41.296-425.551-106.034v76.504c0 .55-.02 1.095-.059 1.634.087.801.132 1.614.132 2.439 0 74.167 189.814 145.089 425.423 145.089s425.423-70.922 425.423-145.089c0-.854.048-1.696.142-2.525V751.78zm43.452-85.135c.137.996.207 2.014.207 3.048v162.959c0 1.036-.071 2.055-.208 3.053-4.256 108.638-213.251 185.747-469.016 185.747-258.413 0-469.082-78.714-469.082-189.132 0-.55.02-1.095.059-1.634a22.571 22.571 0 01-.132-2.439V672.992a86 86 0 010-6.614v-3.293c0-2.187.316-4.3.905-6.295 12.455-82.401 143.918-144.902 327.226-166.509a21.682 21.682 0 015.379.034c22.28-2.544 45.28-4.477 68.873-5.761 12.039-.655 22.324 8.659 22.974 20.803s-8.583 22.521-20.622 23.176C240.48 539.799 86.567 605.201 86.567 670.262c0 7.083 1.777 14.139 5.2 21.106 32.344 64.67 205.219 121.467 414.783 121.467 232.727 0 420.217-70.052 420.217-143.14 0-56.645-118.34-115.768-291.269-135.863a21.762 21.762 0 01-4.332-.956 1097.148 1097.148 0 00-54.572-4.332c-12.038-.657-21.269-11.035-20.618-23.179s10.939-21.456 22.977-20.799c226.148 12.347 397.817 84.304 401.956 182.077z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.purchase')</span>
                    <span>@lang('messages.order')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.delete')</span>
        </h1>
    </div>

    <div class="py-2 flex flex-col">

        <div class="container mx-auto px-2 sm:px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-3/4 lg:w-1/2 shadow mb-5" role="alert">
                    <form action="{{ route('purchase-order.destroy', Crypt::Encrypt($datas->id)) }}" class="block"
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
                                    <div class="flex flex-col md:flex-row gap-2 justify-between">
                                        <x-primary-button type="submit"
                                            class="bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.delete')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('purchase-order.index') }}" tabindex="1"
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
                    <div class="p-4 space-y-2">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <span for="supplier_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.supplier')</span>
                                    <x-text-span>{{ $datas->supplier->nama }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="tanggal"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.transactiondate')</span>
                                    <x-text-span>{{ date('d/m/Y', strtotime($datas->tanggal)) }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="tunai"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.payment')</span>
                                    <x-text-span>{{ $datas->tunai == 1 ? __('messages.cash') : __('messages.credit') }}</x-text-span>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="w-auto pb-4">
                                    <span for="biaya_angkutan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliverycost')
                                        (@lang('messages.currencysymbol'))</span>
                                    <x-text-span>{{ number_format($datas->biaya_angkutan, 0, ',', '.') }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="total_harga"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.totalprice')
                                        (@lang('messages.currencysymbol'))</label>
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

                                    <x-anchor-secondary href="{{ route('purchase-order.index') }}" tabindex="2">
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
                                        <path
                                            d="M24.3,36.5c0.7,0,1.4,0.1,2,0.3L15.5,6.2c0,0,0,0,0,0l-1-3c-0.3-0.9-1.2-1.3-2-1L3.1,5.3 c-0.9,0.3-1.3,1.2-1,2l1,3c0.3,0.9,1.2,1.3,2,1L10,9.7l9.9,28.1C21.2,37,22.7,36.5,24.3,36.5z" />
                                        <path
                                            d="M41.2,29.2l-9.9,3.5c-1,0.4-2.2-0.2-2.5-1.2l-3.5-9.9c-0.4-1,0.2-2.2,1.2-2.5l9.9-3.5 c1-0.4,2.2,0.2,2.5,1.2l3.5,9.9C42.8,27.7,42.2,28.8,41.2,29.2z" />
                                        <path
                                            d="M31.8,12.9l-6.7,2.3c-1,0.4-2.2-0.2-2.5-1.2l-2.3-6.7c-0.4-1,0.2-2.2,1.2-2.5l6.7-2.3 c1-0.4,2.2,0.2,2.5,1.2l2.3,6.7C33.4,11.3,32.9,12.5,31.8,12.9z" />
                                        <path
                                            d="M49.9,35.5l-1-3c-0.3-0.9-1.2-1.3-2-1l-18.2,6.3c1.9,1.2,3.2,3.2,3.6,5.5l16.7-5.7 C49.8,37.3,50.2,36.4,49.9,35.5z" />
                                        <path
                                            d="M24.3,39.1c-3,0-5.5,2.5-5.5,5.5c0,3,2.5,5.5,5.5,5.5s5.5-2.5,5.5-5.5C29.8,41.5,27.3,39.1,24.3,39.1z" />
                                    </g>
                                </svg>
                                <span class="block font-medium text-primary-600 dark:text-primary-500">
                                    @lang('messages.purchasedgoods')
                                </span>
                            </div>

                            <div
                                class="border rounded-md border-primary-100 bg-primary-100 dark:border-primary-800 dark:bg-primary-850">
                                <div class="p-2 overflow-scroll md:overflow-auto lg:overflow-hidden">
                                    <table id="order_table" class="w-full border-separate border-spacing-2">
                                        <thead>
                                            <tr>
                                                <th class="w-1/4">@lang('messages.goods')</th>
                                                <th class="w-1/6">@lang('messages.unitprice') (@lang('messages.currencysymbol'))</th>
                                                <th class="w-auto">@lang('messages.unit')</th>
                                                <th class="w-auto">@lang('messages.quantity')</th>
                                                <th class="w-auto">@lang('messages.discount') (%)</th>
                                                <th class="w-auto">@lang('messages.tax') (%)</th>
                                                <th class="w-1/5">@lang('messages.subtotalprice') (@lang('messages.currencysymbol'))</th>
                                            </tr>
                                        </thead>

                                        <tbody id="detailBody">
                                            @include('purchase-order.partials.details', [
                                                $details,
                                                'viewMode' => true,
                                            ])
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <td class="align-top text-center" colspan="6">
                                                    <x-text-span class="font-extrabold">@lang('messages.totalprice')
                                                        (@lang('messages.currencysymbol'))</x-text-span>
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
    </div>
</x-app-layout>
