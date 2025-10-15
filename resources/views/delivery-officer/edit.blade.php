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
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <div x-data="{ buttonDisabled: {{ $datas->isdone == 1 ? 'true' : 'false' }} }" class="py-2 flex flex-col">

        <div class="w-full px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('delivery-officer.partials.feedback')
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
                                        <span for="no_order"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliveryordernumber')</span>
                                        <x-text-span>{{ $datas->no_order }}</x-text-span>
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="tanggal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliverydate')</label>
                                        <x-text-input type="date" name="tanggal" id="tanggal"
                                            x-bind:disabled="buttonDisabled" data-date-format="dd-mm-yyyy"
                                            tabindex="1" autofocus required value="{{ $datas->tanggal }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('tanggal')" />
                                    </div>

                                    <div class="flex flex-row gap-4">
                                        <div class="w-1/2 pb-4">
                                            <label for="jam_awal"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.departure')</label>
                                            <x-text-input type="time" name="jam_awal" id="jam_awal"
                                                x-bind:disabled="buttonDisabled" tabindex="1" required
                                                value="{{ old('jam_awal', $datas->jam_awal ? $datas->jam_awal : '') }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('jam_awal')" />
                                        </div>

                                        <div class="w-1/2 pb-4">
                                            <label for="jam_akhir"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.arrival')</label>
                                            <x-text-input type="time" name="jam_akhir" id="jam_akhir"
                                                x-bind:disabled="buttonDisabled" tabindex="1" required
                                                value="{{ old('jam_akhir', $datas->jam_akhir ? $datas->jam_akhir : '') }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('jam_akhir')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <label for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                        <x-text-input type="text" name="keterangan" id="keterangan" tabindex="3"
                                            x-bind:disabled="buttonDisabled"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.description') }}"
                                            value="{{ $datas->keterangan }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                                    </div>

                                    <div class="w-auto pb-4 lg:pb-12">
                                        <span for="pegawai_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliveryman')</span>
                                        <x-text-span>{{ $datas->pegawai_id ? $datas->pegawai->nama_lengkap : '???' }}</x-text-span>
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        @if ($datas->isdone == 1)
                                            <div class="pr-2">
                                                <div class="inline-flex items-center">
                                                    <span x-show="buttonDisabled">✔️</span>
                                                    <span x-show="!buttonDisabled">❌</span>
                                                    <label class='pl-2'>@lang('messages.deliveryfinish')</label>
                                                </div>
                                            </div>
                                        @else
                                            <div class="w-auto">
                                                <label
                                                    class="cursor-pointer flex flex-col items-center md:flex-row md:gap-2">
                                                    <input type="checkbox" id="isdone" name="isdone"
                                                        class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md"
                                                        {{ $datas->isdone == '1' ? 'checked' : '' }}>
                                                    <span
                                                        class="pr-4 group-hover:text-blue-500 transition-colors duration-300 text-right w-1/2 md:w-full">
                                                        @lang('messages.deliveryfinish')
                                                    </span>
                                                </label>
                                            </div>
                                        @endif

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

                    <form id="package-form" method="POST" enctype="multipart/form-data" class="w-full">
                        @csrf

                        {{-- Package --}}
                        <div
                            class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                            <div class="p-4 space-y-2">
                                <div class="flex flex-row items-center gap-2">
                                    <svg class="size-5" version="1.1" id="Capa_1"
                                        xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                        x="0px" y="0px" viewBox="0 0 458.868 458.868"
                                        style="enable-background:new 0 0 458.868 458.868;" xml:space="preserve">
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
                                                    'viewMode' => false,
                                                ])
                                            </tbody>

                                            <tbody>
                                                <tr>
                                                    <td class="align-top">
                                                        <input type="hidden" id="delivery_officer_id"
                                                            name="delivery_officer_id" value="{{ $datas->id }}" />
                                                        <select id="barang_id" name="barang_id" required
                                                            tabindex="9"
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
                                                            name="harga_satuan" required tabindex="10" readonly />
                                                    </td>
                                                    <td class="align-top">
                                                        <select id="satuan_id" name="satuan_id" required
                                                            tabindex="11" disabled
                                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                            <option value="">@lang('messages.choose')...</option>
                                                            @foreach ($satuans as $id => $name)
                                                                <option value="{{ $id }}">
                                                                    {{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-input type="number" min="0" step="0.01"
                                                            id="kuantiti" name="kuantiti" required tabindex="12" />
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-span id="disp-sub_harga"
                                                            class="text-right">0</x-text-span>
                                                    </td>
                                                </tr>
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
                                            <x-primary-button id="submit-detail" tabindex="13"
                                                x-bind:disabled="buttonDisabled">
                                                <div id="icon-save" class="block">
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                                    </svg>
                                                </div>
                                                <span class="pl-1">@lang('messages.save')</span>
                                            </x-primary-button>
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
                    </form>
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

                $("#submit-detail").on("click", function(e) {
                    e.preventDefault();
                    $('#satuan_id').removeAttr('disabled');
                    let key = $('#delivery_officer_id').val();

                    $.ajax({
                        url: '{{ url('/delivery/order/store-package') }}' + '/' + key,
                        type: 'post',
                        dataType: 'json',
                        data: $('form#package-form').serialize(),
                        success: function(result) {
                            if (result.status !== 'Not Found') {
                                $('#packageBody').html(result.view);
                                $('#disp-total_harga-detail').html(result.total_harga_detail
                                    .toLocaleString('de-DE'));
                                $('#package-form')[0].reset();
                                $("#disp-sub_harga").html(0);
                                $('#satuan_id').attr('disabled', true);
                                flasher.success("{{ __('messages.successsaved') }}!", "Success");
                            }
                        }
                    });

                    if (isFormDirty('master-form', myFormInitialValues)) {
                        $('form#master-form').submit();
                    }
                });

                $("#harga_satuan, #kuantiti").on("change keyup paste", function() {
                    var _xhs = $('#harga_satuan').val();
                    var _xku = $('#kuantiti').val();
                    var xhs = (_xhs > 0) ? _xhs : 0;
                    var xku = (_xku > 0) ? _xku : 0;
                    var xsub = xhs * xku;
                    var formattedNumber = new Intl.NumberFormat('de-DE').format(xsub);

                    $("#disp-sub_harga").html(formattedNumber);
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
                            $('#harga_satuan').val(p1);
                            $('#satuan_id').val(p2);
                            $('#kuantiti').focus();
                        }
                    });
                });

                deleteDetail = function(detailId) {
                    let idname = '#a-delete-detail-' + detailId;

                    var confirmation = confirm("Are you sure you want to delete this?");
                    if (confirmation) {
                        $(idname).closest("tr").remove();
                        $.ajax({
                            url: '{{ url('/delivery/order/delete-package') }}' + '/' + detailId,
                            type: 'delete',
                            dataType: 'json',
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(result) {
                                $('#packageBody').html(result.view);
                                $('#disp-total_harga-detail').html(result.total_harga_detail
                                    .toLocaleString('de-DE'));
                                $('#package-form')[0].reset();
                                $("#disp-sub_harga").html(0);
                                flasher.error("{{ __('messages.successdeleted') }}!", "Success");
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                };

            });
        </script>
    @endpush
</x-app-layout>
