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
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <div class="py-2 flex flex-col">

        <div class="w-full px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('purchase-order.partials.feedback')
                </div>

                <form id="master-form" action="{{ route('purchase-order.update', Crypt::Encrypt($datas->id)) }}"
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
                                        <span for="supplier_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.supplier')</span>
                                        <x-text-span>{{ $datas->supplier->nama }}</x-text-span>
                                        <div class="hidden">
                                            <select name="supplier_id" id="supplier_id" tabindex="1" required
                                                autofocus
                                                class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                <option value="">@lang('messages.choose')...</option>
                                                @foreach ($suppliers as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $datas->supplier_id == $id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('supplier_id')" />
                                        </div>
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="tanggal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.transactiondate')</label>
                                        <x-text-input type="date" name="tanggal" id="tanggal"
                                            data-date-format="dd-mm-yyyy" tabindex="2" required
                                            value="{{ old('tanggal', $datas->tanggal) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('tanggal')" />
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

                                    <div id="div-jatuhtempo"
                                        class="{{ $datas->tunai == 1 ? 'hidden ' : '' }}w-auto pb-4">
                                        <label for="jatuhtempo"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.duedate')</label>
                                        <x-text-input type="date" name="jatuhtempo" id="jatuhtempo"
                                            data-date-format="dd-mm-yyyy" tabindex="4"
                                            placeholder="{{ __('messages.enter') }} {{ __('calendar.date') }}"
                                            value="{{ old('jatuhtempo', $datas->jatuhtempo) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('jatuhtempo')" />
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <label for="biaya_angkutan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.deliverycost')
                                            (@lang('messages.currencysymbol'))</label>
                                        <x-text-input type="text" name="biaya_angkutan" id="biaya_angkutan"
                                            tabindex="5"
                                            value="{{ old('biaya_angkutan', $datas->biaya_angkutan) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('biaya_angkutan')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <span for="total_harga"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.totalprice')
                                            (@lang('messages.currencysymbol'))</span>
                                        <x-text-span
                                            id="disp-total_harga-master">{{ number_format($totals['total_price'], 0, ',', '.') }}</x-text-span>
                                        <input type="hidden" name="total_harga" id="total_harga"
                                            value="{{ $totals['total_price'] }}" class="hidden" />

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
                                        <div class="w-auto">
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
                                        <x-anchor-secondary href="{{ route('purchase-order.index') }}"
                                            tabindex="7">
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

        <div class="relative flex flex-col lg:flex-row gap-4 px-4 py-2">
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
                                                    <th class="w-1/5">@lang('messages.goods')</th>
                                                    <th class="w-1/6">@lang('messages.unitprice') (@lang('messages.currencysymbol'))</th>
                                                    <th class="w-auto">@lang('messages.unit')</th>
                                                    <th class="w-auto">@lang('messages.quantity')</th>
                                                    <th class="w-auto">@lang('messages.discount') (%)</th>
                                                    <th class="w-auto">@lang('messages.tax') (%)</th>
                                                    <th class="w-auto">@lang('messages.subtotalprice') (@lang('messages.currencysymbol'))</th>
                                                    <th class="w-auto">&nbsp;</th>
                                                </tr>
                                            </thead>

                                            <tbody id="detailBody">
                                                @include('purchase-order.partials.details-editable', [
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
                                                            name="harga_satuan" required tabindex="11" readonly />
                                                    </td>
                                                    <td class="align-top">
                                                        <select id="satuan_id" name="satuan_id" required
                                                            tabindex="12"
                                                            class="readonly-select w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                            <option value="">@lang('messages.choose')...</option>
                                                            @foreach ($satuans as $id => $name)
                                                                <option value="{{ $id }}">
                                                                    {{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-input type="number" min="0" id="kuantiti"
                                                            name="kuantiti" required tabindex="13" />
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-input type="number" min="0" id="discount"
                                                            name="discount" tabindex="14" />
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-input type="number" min="0" id="pajak"
                                                            name="pajak" tabindex="15" />
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-span id="disp-sub_harga"
                                                            class="text-right">0</x-text-span>
                                                    </td>
                                                </tr>
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

                                    <div class="mt-4 mb-4 mr-4 flex flex-row flex-wrap justify-end gap-2 md:gap-4">
                                        <x-primary-button id="submit-detail" tabindex="16">
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
                                        <x-anchor-secondary href="{{ route('purchase-order.index') }}"
                                            tabindex="17">
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

    @push('styles')
        <style>
            .readonly-select {
                cursor: not-allowed;
                opacity: 1;
            }
        </style>
    @endpush

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="{{ url('js/jquery.maskMoney.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function(e) {
                $("#satuan_id").on("mousedown", function(e) {
                    e.preventDefault();
                    this.blur();
                    window.focus();
                });

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
                        thousands: '.',
                        decimal: ',',
                        precision: 0,
                        affixesStay: false
                    });
                    $('#biaya_angkutan').maskMoney({
                        prefix: 'Rp. ',
                        allowNegative: false,
                        thousands: '.',
                        decimal: ',',
                        precision: 0,
                        affixesStay: false
                    });
                })

                $('input[name^="items["]').on('change', function() {
                    var inputName = $(this).attr('name');
                    var inputValue = $(this).val();

                    var match = inputName.match(/\[(\d+)\]\[(.*?)\]/);
                    if (match) {
                        var row = match[1];
                        var column = match[2];

                        $.ajax({
                            url: '{{ url('/purchase/order/update-detail') }}' + "/" + row + "/" +
                                column + "/" + inputValue,
                            type: "GET",
                            dataType: 'json',
                            success: function(result) {
                                if (result.status !== 'Not Found') {
                                    var _input_hargasatuan = 'input[name="items[' + row +
                                        '][harga_satuan]"]';
                                    var harga_satuan = $(_input_hargasatuan).val();
                                    var _input_kuantiti = 'input[name="items[' + row +
                                        '][kuantiti]"]';
                                    var kuantiti = $(_input_kuantiti).val();
                                    var _input_discount = 'input[name="items[' + row +
                                        '][discount]"]';
                                    var discount = $(_input_discount).val();
                                    var _input_pajak = 'input[name="items[' + row +
                                        '][pajak]"]';
                                    var pajak = $(_input_pajak).val();
                                    var xsub = (harga_satuan * (1 + (pajak / 100) - (discount /
                                        100))) * kuantiti;
                                    var formattedNumber = new Intl.NumberFormat('de-DE').format(
                                        xsub);
                                    var _subtotal = '#subtotal_' + row;
                                    $(_subtotal).html(formattedNumber);
                                    flasher.success("{{ __('messages.successsaved') }}!",
                                        "Success");
                                }
                            }
                        });
                    }
                });

                deleteDetail = function(detailId) {
                    let idname = '#a-delete-detail-' + detailId;

                    var confirmation = confirm("Are you sure you want to delete this?");
                    if (confirmation) {
                        $(idname).closest("tr").remove();
                        $.ajax({
                            url: '{{ url('/purchase/order/delete-detail') }}' + '/' + detailId,
                            type: 'delete',
                            dataType: 'json',
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(result) {
                                if (result.status !== 'Not Found') {
                                    $('#detailBody').html(result.view);
                                }
                                $('#form-order')[0].reset();
                                $('#disp-total_harga-master').html(result.total_harga_master
                                    .toLocaleString('de-DE'));
                                $('#disp-total_harga-detail').html(result.total_harga_detail
                                    .toLocaleString('de-DE'));
                                flasher.error("{{ __('messages.successdeleted') }}!", "Success");
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                };

                $("#harga_satuan, #kuantiti, #discount, #pajak").on("change keyup paste", function() {
                    var _xhs = $('#harga_satuan').val();
                    var _xku = $('#kuantiti').val();
                    var _xdc = $('#discount').val();
                    var _xpj = $('#pajak').val();
                    var xhs = (_xhs > 0) ? _xhs : 0;
                    var xku = (_xku > 0) ? _xku : 0;
                    var xdc = (_xdc > 0) ? _xdc : 0;
                    var xpj = (_xpj > 0) ? _xpj : 0;
                    var xsub = (xhs * (1 + (xpj / 100) - (xdc / 100))) * xku;
                    var formattedNumber = new Intl.NumberFormat('de-DE').format(xsub);

                    $("#disp-sub_harga").html(formattedNumber);
                });

                $("#tunai").on("change keyup paste", function() {
                    var _xtunai = $('#tunai').val();

                    if (_xtunai === '2') {
                        var now = new Date();
                        var day = ("0" + now.getDate()).slice(-2);
                        var month = ("0" + (now.getMonth() + 1)).slice(-2);
                        var year = now.getFullYear();
                        var today = year + "-" + month + "-" + day;
                        $("#div-jatuhtempo").show();
                        $("#jatuhtempo").val(today);
                    } else {
                        $("#jatuhtempo").val("");
                        $("#div-jatuhtempo").hide();
                    }
                });

                $("#barang_id").on("change keyup paste", function() {
                    var xbar = $('#barang_id option:selected').val();

                    $.ajax({
                        url: '{{ url('/warehouse/goods/get-goods-buy') }}' + "/" + xbar,
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

                $("#submit-detail").on("click", function(e) {
                    e.preventDefault();
                    let key = $('#order_id').val();

                    $.ajax({
                        url: '{{ url('/purchase/order/store-detail') }}' + '/' + key,
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
