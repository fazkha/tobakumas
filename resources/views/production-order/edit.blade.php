@section('title', __('messages.production'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('production-order.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" version="1.1">
                    <path style="fill:#555555;stroke:#000000;stroke-width:1.5px;"
                        d="m 40,2 -2,9 -10,5 -8,-6 -9,9 6,9 -4,10 -10,2 0,12 10,1 4,10 -6,9 9,9 9,-6 9,4 2,10 13,0 1,-11 8,-4 9,7 9,-8 -6,-10 4,-9 11,-2 0,-12 -11,-2 -3,-9 6,-10 -9,-9 -8,6 -11,-5 -1,-9 z m 5,18 C 58,20 69,31 69,44 69,58 58,68 45,68 32,68 21,58 21,44 21,31 32,20 45,20 z" />
                    <circle style="fill:none;stroke:#eeeeee;stroke-width:3" cx="65" cy="65" r="34" />
                    <circle style="fill:#444444;fill-opacity:0.7" cx="65" cy="65" r="32" />
                    <path style="stroke:none;fill:#00C60A;fill-opacity:0.7"
                        d="m 58,33 7,34 32,-7 C 97,60 92,29 58,33" />
                    <circle style=";stroke-width:5pt;stroke:#222222;fill:none;" cx="65" cy="65" r="30" />
                    <g style="fill:#aaaaaa;">
                        <circle cx="65" cy="35" r="2.5" />
                        <circle cx="95" cy="65" r="2.5" />
                        <circle cx="65" cy="95" r="2.5" />
                        <circle cx="35" cy="65" r="2.5" />
                    </g>
                    <path style="stroke:#ffffff;stroke-width:4;fill:none;" d="M 65,65 60,42" />
                    <path style="stroke:#ffffff;stroke-width:3;fill:none;" d="M 65,65 44,87" />
                    <circle style="fill:#ffffff;" cx="65" cy="65" r="3.5" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.production')</span>
                    <span>@lang('messages.productionorder')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <div x-data="{ buttonDisabled: {{ $datas->order->isready == 1 ? 'true' : 'false' }} }" class="py-2 flex flex-col">

        <div class="w-full px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('production-order.partials.feedback')
                </div>

                <form id="master-form" action="{{ route('production-order.update', Crypt::Encrypt($datas->id)) }}"
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
                                        <input type="hidden" id="prod_status" value="{{ $datas->isactive }}" />
                                        <label for="tanggal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.productiondate')</label>
                                        <x-text-input type="date" name="tanggal" id="tanggal"
                                            data-date-format="dd-mm-yyyy" tabindex="1" autofocus required
                                            value="{{ $datas->tanggal }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('tanggal')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                        <x-text-input type="text" name="keterangan" id="keterangan" tabindex="2"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.description') }}"
                                            value="{{ $datas->keterangan }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="no_order"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.salesordernumber')</label>
                                        <x-text-span>{{ $datas->order->no_order }}</x-text-span>
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <label for="petugas_1_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.officer')
                                            1</label>
                                        <select name="petugas_1_id" id="petugas_1_id" tabindex="3"
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($petugas as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $datas->petugas_1_id == $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('petugas_1_id')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="petugas_2_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.officer')
                                            2</label>
                                        <select name="petugas_2_id" id="petugas_2_id" tabindex="4"
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($petugas as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $datas->petugas_2_id == $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('petugas_2_id')" />
                                    </div>

                                    <div class="w-auto pb-4 lg:pb-12">
                                        <label for="tanggungjawab_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.supervisor')</label>
                                        <select name="tanggungjawab_id" id="tanggungjawab_id" tabindex="5"
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($petugas2 as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $datas->tanggungjawab_id == $id ? 'selected' : '' }}>
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
                                                <label class='pl-2'>@lang('messages.productionfinish')</label>
                                            </div>
                                        </div>

                                        <x-primary-button type="submit" class="block" tabindex="6"
                                            x-bind:disabled="buttonDisabled">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('production-order.index') }}"
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

        <div class="flex flex-col lg:flex-row gap-4 px-4 py-2">
            <div class="w-full">
                <div class="flex flex-col items-center gap-4">

                    <div
                        class="w-full shadow-lg rounded-md border bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">
                            <div class="flex flex-row items-center gap-2">
                                <svg class="size-5" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"
                                    fill="currentColor">
                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                        d="M1.5 1l-.5.5v3l.5.5h3l.5-.5v-3L4.5 1h-3zM2 4V2h2v2H2zm-.5 2l-.5.5v3l.5.5h3l.5-.5v-3L4.5 6h-3zM2 9V7h2v2H2zm-1 2.5l.5-.5h3l.5.5v3l-.5.5h-3l-.5-.5v-3zm1 .5v2h2v-2H2zm10.5-7l-.5.5v6l.5.5h3l.5-.5v-6l-.5-.5h-3zM15 8h-2V6h2v2zm0 3h-2V9h2v2zM9.1 8H6v1h3.1l-1 1 .7.6 1.8-1.8v-.7L8.8 6.3l-.7.7 1 1z" />
                                </svg>
                                <span class="block font-medium text-primary-600 dark:text-primary-500">
                                    @lang('messages.productioncombine')
                                </span>
                            </div>

                            {{-- Combine --}}
                            <div id="combineBody">
                                @include('production-order.partials.combines', [$sales])
                            </div>

                            <div class="my-4 flex flex-row justify-end gap-4">
                                <x-primary-button id="submit-combine" tabindex="14" x-bind:disabled="buttonDisabled">
                                    <svg class="size-5" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"
                                        fill="currentColor">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M1.5 1l-.5.5v3l.5.5h3l.5-.5v-3L4.5 1h-3zM2 4V2h2v2H2zm-.5 2l-.5.5v3l.5.5h3l.5-.5v-3L4.5 6h-3zM2 9V7h2v2H2zm-1 2.5l.5-.5h3l.5.5v3l-.5.5h-3l-.5-.5v-3zm1 .5v2h2v-2H2zm10.5-7l-.5.5v6l.5.5h3l.5-.5v-6l-.5-.5h-3zM15 8h-2V6h2v2zm0 3h-2V9h2v2zM9.1 8H6v1h3.1l-1 1 .7.6 1.8-1.8v-.7L8.8 6.3l-.7.7 1 1z" />
                                    </svg>
                                    <span class="pl-1">@lang('messages.combine')</span>
                                </x-primary-button>
                            </div>
                        </div>
                    </div>

                    <div
                        class="w-full shadow-lg rounded-md border bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">
                            <div class="flex flex-row items-center gap-2">
                                <svg class="size-5" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                    <path fill="currentColor"
                                        d="M468.166 24.156c-13.8-.31-30.977 9.192-42.46 16.883-22.597 15.13-45.255 67.882-45.255 67.882s-17.292-5.333-22.626 0c-5.333 5.333 0 22.627 0 22.627l-4.95 4.948 22.628 22.63 4.95-4.952s17.293 5.333 22.626 0c5.333-5.334 0-22.627 0-22.627s52.75-22.66 67.883-45.255c10.7-15.978 24.91-42.97 11.313-56.568-3.824-3.825-8.707-5.45-14.107-5.57zM312.568 121.65L121.65 312.568l77.782 77.782L390.35 199.432l-77.782-77.782zm-176.07 231.223l-4.95 4.95s-17.293-5.332-22.626 0c-5.333 5.335 0 22.628 0 22.628s-52.75 22.66-67.883 45.255c-10.7 15.978-24.91 42.97-11.313 56.568 13.597 13.598 40.59-.612 56.568-11.312 22.596-15.13 45.254-67.882 45.254-67.882s17.292 5.333 22.626 0c5.333-5.333 0-22.627 0-22.627l4.95-4.948-22.628-22.63z" />
                                </svg>
                                <span class="block font-medium text-primary-600 dark:text-primary-500">
                                    @lang('messages.rawmaterial')
                                </span>
                            </div>

                            {{-- Bahan baku --}}
                            <div id="bahanBody">
                                @include('production-order.partials.bahanbakuproduksi', [$bahans])
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="w-full">
                <div class="flex flex-col items-center gap-4">

                    <form id="detail-form" method="POST" enctype="multipart/form-data" class="w-full">
                        @csrf

                        {{-- Detail --}}
                        <div
                            class="w-full shadow-lg rounded-md border bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                            <div class="p-4 space-y-2">
                                <div class="flex flex-row items-center gap-2">
                                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 48 48"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <title>output</title>
                                        <g id="Layer_2" data-name="Layer 2">
                                            <g id="invisible_box" data-name="invisible box">
                                                <rect width="48" height="48" fill="none" />
                                            </g>
                                            <g id="Layer_6" data-name="Layer 6">
                                                <g>
                                                    <path
                                                        d="M45.4,22.6l-7.9-8a2.1,2.1,0,0,0-2.7-.2,1.9,1.9,0,0,0-.2,3L39.2,22H16a2,2,0,0,0,0,4H39.2l-4.6,4.6a1.9,1.9,0,0,0,.2,3,2.1,2.1,0,0,0,2.7-.2l7.9-8A1.9,1.9,0,0,0,45.4,22.6Z" />
                                                    <path
                                                        d="M28,42H24A18,18,0,0,1,24,6h4a2,2,0,0,0,1.4-.6A2,2,0,0,0,30,4a2.4,2.4,0,0,0-.2-.9A2,2,0,0,0,28,2H23.8a22,22,0,0,0,.1,44H28a2,2,0,0,0,1.4-.6l.4-.5A2.4,2.4,0,0,0,30,44,2,2,0,0,0,28,42Z" />
                                                </g>
                                            </g>
                                        </g>
                                    </svg>
                                    <span class="block font-medium text-primary-600 dark:text-primary-500">
                                        @lang('messages.productionresult')
                                    </span>
                                </div>

                                <div
                                    class="border rounded-md border-primary-100 bg-primary-100 dark:border-primary-800 dark:bg-primary-850">
                                    <div class="p-2 overflow-scroll md:overflow-auto lg:overflow-hidden">
                                        <table id="order_table" class="w-full border-separate border-spacing-2">
                                            <thead>
                                                <tr>
                                                    <th class="w-1/2">@lang('messages.goods')</th>
                                                    <th class="w-1/4">@lang('messages.unit')</th>
                                                    <th class="w-auto">@lang('messages.quantity')</th>
                                                </tr>
                                            </thead>

                                            <tbody id="detailBody">
                                                @include('production-order.partials.details', [
                                                    $details,
                                                    'viewMode' => true,
                                                ])
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-4 mb-4 mr-4 flex flex-row flex-wrap justify-end gap-2 md:gap-4">
                                        <x-primary-button id="submit-detail" tabindex="14"
                                            x-on:click="buttonDisabled = true" x-bind:disabled="buttonDisabled">
                                            <svg id="svg-loading" class="hidden animate-spin size-5"
                                                viewBox="0 0 48 48" fill="none"
                                                xmlns="http://www.w3.org/2000/svg">
                                                <rect width="48" height="48" fill="white"
                                                    fill-opacity="0.01" />
                                                <path
                                                    d="M4 24C4 35.0457 12.9543 44 24 44V44C35.0457 44 44 35.0457 44 24C44 12.9543 35.0457 4 24 4"
                                                    stroke="currentColor" stroke-width="4" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                                <path
                                                    d="M36 24C36 17.3726 30.6274 12 24 12C17.3726 12 12 17.3726 12 24C12 30.6274 17.3726 36 24 36V36"
                                                    stroke="currentColor" stroke-width="4" stroke-linecap="round"
                                                    stroke-linejoin="round" />
                                            </svg>
                                            <svg id="svg-default" class="size-5" viewBox="0 0 1024 1024"
                                                class="icon" xmlns="http://www.w3.org/2000/svg">
                                                <path fill="currentColor"
                                                    d="M280.768 753.728L691.456 167.04a32 32 0 1152.416 36.672L314.24 817.472a32 32 0 01-45.44 7.296l-230.4-172.8a32 32 0 0138.4-51.2l203.968 152.96zM736 448a32 32 0 110-64h192a32 32 0 110 64H736zM608 640a32 32 0 010-64h319.936a32 32 0 110 64H608zM480 832a32 32 0 110-64h447.936a32 32 0 110 64H480z" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.productionfinish')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('production-order.index') }}"
                                            tabindex="15">
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

                                <div id="targetDiv"
                                    class="hidden p-4 overflow-scroll md:overflow-auto lg:overflow-hidden">
                                    <table id="target_table" class="w-full">
                                        <thead>
                                            <tr>
                                                <th class="w-auto text-left">@lang('messages.partner')</th>
                                                <th class="w-auto text-left">@lang('messages.goods')</th>
                                                <th class="w-auto">@lang('messages.quantity')</th>
                                                <th class="w-auto">@lang('messages.unit')</th>
                                                <th class="w-auto">&nbsp;</th>
                                            </tr>
                                        </thead>

                                        <tbody id="targetBody">
                                            @include('production-order.partials.targets', [$targets])
                                        </tbody>
                                    </table>
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
                const status_order_ready = '{{ $datas->order->isready }}';

                if (status_order_ready === '1') {
                    $('#targetDiv').removeClass('hidden');
                    $('#targetDiv').addClass('block');
                }

                $("#submit-combine").on("click", function(e) {
                    e.preventDefault();
                    var xkey = '{{ $datas->id }}';

                    $("input[name^='order']:checked").map(function() {
                        var xjoin = $(this).val();
                        // var xstat = $('#prod_status').val();

                        if (xjoin) {
                            console.log(xjoin);

                            $.ajax({
                                url: '{{ url('/production/order/combine') }}' + "/" + xkey +
                                    "/" + xjoin,
                                type: "get",
                                dataType: 'json',
                                success: function(result) {
                                    if (result.status !== 'Not Found') {
                                        $('#bahanBody').html(result.view3);
                                        $('#combineBody').html(result.view2);
                                        $('#detailBody').html(result.view);
                                        $('#targetBody').html(result.view4);
                                        flasher.success(
                                            "{{ __('messages.combinesuccess') }}",
                                            "Success");
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
                        url: '{{ url('/production/order/finish-order') }}' + '/' + key,
                        type: 'get',
                        dataType: 'json',
                        success: function(result) {
                            if (result.status !== 'Not Found') {
                                $('#targetDiv').removeClass('hidden');
                                $('#targetDiv').addClass('block');
                                flasher.success("{{ __('messages.productionfinish') }}",
                                    "Success");
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
