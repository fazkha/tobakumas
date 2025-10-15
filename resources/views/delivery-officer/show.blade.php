@php
    use App\Models\ViewDeliveryOpen;
    $pro = '';
    $kab = '';
@endphp
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

    <div x-data="{ buttonDisabled: {{ $datas->isdone == 1 ? 'true' : 'false' }} }" class="py-2 flex flex-col">

        <div class="w-full px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('delivery-officer.partials.feedback')
                </div>

                {{-- Master --}}
                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-4 space-y-2">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <span for="no_order"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliveryordernumber')</span>
                                    <x-text-span>{{ $datas->no_order }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="tanggal"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliverydate')</span>
                                    <x-text-span>{{ date('d/m/Y', strtotime($datas->tanggal)) }}</x-text-span>
                                </div>

                                <div class="flex flex-row gap-4">
                                    <div class="w-1/2 pb-4">
                                        <span for="jam_awal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.departure')</span>
                                        <x-text-span>{{ old('jam_awal', $datas->jam_awal ? $datas->jam_awal : '') }}</x-text-span>
                                    </div>

                                    <div class="w-1/2 pb-4">
                                        <span for="jam_akhir"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.arrival')</span>
                                        <x-text-span>{{ old('jam_akhir', $datas->jam_akhir ? $datas->jam_akhir : '') }}</x-text-span>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="w-auto pb-4">
                                    <span for="keterangan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</span>
                                    <x-text-span>{{ $datas->keterangan }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4 lg:pb-12">
                                    <span for="pegawai_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliveryman')</span>
                                    <x-text-span>{{ $datas->pegawai_id ? $datas->pegawai->nama_lengkap : '???' }}</x-text-span>
                                </div>

                                <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                    <div class="pr-2">
                                        <div class="inline-flex items-center">
                                            <span x-show="buttonDisabled">✔️</span>
                                            <span x-show="!buttonDisabled">❌</span>
                                            <label class='pl-2'>@lang('messages.deliveryfinish')</label>
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

                    {{-- Package --}}
                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">
                            <div class="flex flex-row items-center gap-2">
                                <svg class="size-5" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                    viewBox="0 0 458.868 458.868" style="enable-background:new 0 0 458.868 458.868;"
                                    xml:space="preserve">
                                    <path
                                        d="M451.986,36.005c-5.813-2.55-12.599,0.093-15.152,5.91l-41.289,94.088H63.323L22.034,41.915 c-2.552-5.816-9.338-8.46-15.152-5.91c-5.816,2.552-8.462,9.336-5.91,15.152L46.36,154.584v249.972 c0,10.63,8.648,19.278,19.278,19.278H394.26c10.63,0,19.278-8.648,19.278-19.278V152.237l44.358-101.08 C460.448,45.341,457.802,38.558,451.986,36.005z M195.003,159.003h66v149.538h-66V159.003z M390.538,400.835H69.36V159.003h102.643 v161.038c0,6.351,5.149,11.5,11.5,11.5h89c6.351,0,11.5-5.149,11.5-11.5V159.003h106.535V400.835z" />
                                </svg>
                                <span class="block font-medium text-primary-600 dark:text-primary-500">
                                    @lang('messages.packaging')
                                </span>
                            </div>

                            <div
                                class="border rounded-md border-primary-100 bg-primary-100 dark:border-primary-800 dark:bg-primary-850">
                                <div class="p-2 overflow-scroll md:overflow-auto lg:overflow-hidden">
                                    <table id="order_table" class="w-full border-separate border-spacing-2">
                                        <thead>
                                            <tr>
                                                <th class="w-auto">@lang('messages.package')</th>
                                                <th class="w-1/6">@lang('messages.unitprice') (@lang('messages.currencysymbol'))</th>
                                                <th class="w-auto">@lang('messages.unit')</th>
                                                <th class="w-1/6">@lang('messages.quantity')</th>
                                                <th class="w-1/5">@lang('messages.subtotalprice') (@lang('messages.currencysymbol'))</th>
                                                <th class="w-auto">&nbsp;</th>
                                            </tr>
                                        </thead>

                                        <tbody id="packageBody">
                                            @include('delivery-officer.partials.details', [
                                                $details,
                                                'viewMode' => true,
                                            ])
                                        </tbody>

                                        <tfoot>
                                            <tr>
                                                <td class="align-top text-center" colspan="4">
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

                                    <div class="mt-4 mb-4 mr-4 flex flex-row flex-wrap justify-end gap-2 md:gap-4">
                                        <x-anchor-secondary href="{{ route('delivery-order.index') }}"
                                            tabindex="14">
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
                </div>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-4 px-4 py-2">
            <div class="w-full">
                <div class="flex flex-col items-center">

                    {{-- Delivery Items --}}
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
                                    @lang('messages.delivery')
                                </span>
                            </div>

                            <div
                                class="border rounded-md shadow-md border-primary-100 bg-primary-20 dark:border-primary-800 dark:bg-primary-850">
                                <div class="p-2 overflow-scroll md:overflow-auto lg:overflow-hidden">
                                    <div class="p-2 flex flex-col gap-2">
                                        @foreach ($customers as $customer)
                                            @if ($pro !== $customer->namapropinsi)
                                                @php
                                                    $pro = $customer->namapropinsi;
                                                @endphp
                                                <span class="font-bold">{{ $customer->namapropinsi }}</span>
                                            @endif

                                            @if ($kab !== $customer->namakabupaten)
                                                @php
                                                    $kab = $customer->namakabupaten;
                                                @endphp
                                                <span class="font-bold pl-8">{{ $customer->namakabupaten }}</span>
                                            @endif
                                            <span class="pl-16">
                                                🏠{{ ' ' . $customer->nama }}
                                            </span>
                                            @php
                                                $pesanans = ViewDeliveryOpen::where('pegawai_id', $datas->pegawai_id)
                                                    ->where('customer_id', $customer->id)
                                                    ->get();
                                            @endphp
                                            @foreach ($pesanans as $pesanan)
                                                <span class="pl-24">
                                                    📦{{ ' ' . $pesanan->barang }}
                                                </span>
                                            @endforeach
                                        @endforeach
                                    </div>

                                </div>
                            </div>

                            <div class="my-4 flex flex-row flex-wrap justify-end gap-2 md:gap-4">
                                <x-anchor-secondary href="{{ route('delivery-order.index') }}" tabindex="14">
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
