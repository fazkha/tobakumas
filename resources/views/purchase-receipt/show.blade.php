@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@section('title', __('messages.goodsreceipt'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('purchase-receipt.index') }}" class="flex items-center justify-center">
                <svg class="size-7" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
                    <style type="text/css">
                        .st0 {
                            fill: currentColor;
                        }
                    </style>
                    <g>
                        <path class="st0"
                            d="M447.77,33.653c-36.385-5.566-70.629,15.824-82.588,49.228h-44.038v37.899h40.902 c5.212,31.372,29.694,57.355,62.855,62.436c41.278,6.316,79.882-22.042,86.222-63.341C517.428,78.575,489.07,39.969,447.77,33.653z" />
                        <path class="st0"
                            d="M162.615,338.222c0-6.88-5.577-12.468-12.468-12.468H96.16c-6.891,0-12.467,5.588-12.467,12.468 c0,6.868,5.576,12.467,12.467,12.467h53.988C157.038,350.689,162.615,345.091,162.615,338.222z" />
                        <path class="st0"
                            d="M392.999,237.965L284.273,340.452l-37.966,9.398v-86.619H0v215.996h246.307v-59.454l35.547-5.732 c16.95-2.418,29.396-6.692,44.336-15.018l46.302-24.228v104.432h132.435V270.828C504.927,202.618,428.016,202.43,392.999,237.965z M215.996,448.913H30.313v-155.37h185.683v63.805l-36.419,9.01c-15.968,4.395-25.708,20.518-22.174,36.696l0.298,1.247 c3.478,15.912,18.651,26.436,34.785,24.14l23.51-3.788V448.913z" />
                    </g>
                </svg>
                <span class="px-2">@lang('messages.goodsreceipt')</span>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.view')</span>
        </h1>
    </div>

    <div class="py-2 flex flex-col">

        <div class="w-full px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('purchase-receipt.partials.feedback')
                </div>

                {{-- Master --}}
                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-4 space-y-2">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <input type="hidden" name="supplier_id" value="{{ $datas->supplier_id }}" />
                                    <input type="hidden" id="order_id" value="{{ $datas->id }}" />
                                    <label for="supplier_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.supplier')</label>
                                    <x-text-span>{{ $datas->supplier->nama }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="no_order"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.ordernumber')</label>
                                    <x-text-span
                                        id="disp-no_order">{{ old('no_order', $datas->no_order) }}</x-text-span>
                                    <x-text-input type="hidden" name="no_order" id="no_order"
                                        value="{{ old('no_order', $datas->no_order) }}" />

                                    <x-input-error class="mt-2" :messages="$errors->get('no_order')" />
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="tanggal"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.transactiondate')</label>
                                    <x-text-span>{{ $datas->tanggal ? date_format(date_create($datas->tanggal), 'd/m/Y') : '' }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="tunai"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.payment')</label>
                                    <x-text-span>{{ $datas->tunai == 1 ? __('messages.cash') : __('messages.credit') }}</x-text-span>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="w-auto pb-4">
                                    <label for="tanggal_terima"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.receiptdate')</label>
                                    <x-text-span>{{ $datas->tanggal_terima ? date_format(date_create($datas->tanggal_terima), 'd/m/Y') : '-' }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4 hidden">
                                    <label for="isaccepted"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.arethegoodsreceived')</label>
                                    <div class="pr-2 py-2">
                                        <div class="inline-flex items-center">
                                            @if ($datas->isaccepted == '1')
                                                <span>✔️</span>
                                            @else
                                                @if ($datas->isaccepted == '0')
                                                    <span>❌</span>
                                                @else
                                                    <span>❓</span>
                                                @endif
                                            @endif
                                            <label
                                                class='pl-2'>{{ $datas->isaccepted == '0' ? __('messages.not') . ' ' : '' }}@lang('messages.isaccepted')</label>
                                        </div>
                                    </div>
                                </div>

                                <div class="w-auto pb-4 lg:pb-12">
                                    <label for="keterangan_terima"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.receiptdescription')</label>
                                    <x-text-span>{{ $datas->keterangan_terima ? $datas->keterangan_terima : '-' }}</x-text-span>
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

                                    <x-anchor-secondary href="{{ route('purchase-receipt.index') }}" tabindex="5">
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

        <div class="relative flex flex-col lg:flex-row gap-4 px-4 py-2">
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
                                    @lang('messages.goodsreceived')
                                </span>
                            </div>

                            <div
                                class="border rounded-md border-primary-100 bg-primary-100 dark:border-primary-800 dark:bg-primary-850">
                                <div class="p-2 overflow-scroll md:overflow-auto lg:overflow-hidden">
                                    <table id="order_table" class="w-full border-separate border-spacing-2">
                                        <thead>
                                            <tr>
                                                <th class="w-1/4">@lang('messages.goods')</th>
                                                <th class="w-1/12">@lang('messages.unit')</th>
                                                <th class="w-1/12">@lang('messages.quantity')</th>
                                                <th class="w-1/12 hidden">@lang('messages.isaccepted')</th>
                                                <th class="w-1/6">@lang('messages.receiptunit')</th>
                                                <th class="w-1/6">@lang('messages.receiptquantity')</th>
                                                <th class="w-1/6">@lang('messages.receiptdescription')</th>
                                            </tr>
                                        </thead>

                                        <tbody id="detailBody">
                                            @include('purchase-receipt.partials.details', [
                                                $details,
                                                'viewMode' => true,
                                            ])
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-4 mb-4 mr-4 flex flex-row flex-wrap justify-end gap-2 md:gap-4">
                                    <x-anchor-secondary href="{{ route('purchase-receipt.index') }}" tabindex="11">
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
    </div>

    @push('scripts')
    @endpush
</x-app-layout>
