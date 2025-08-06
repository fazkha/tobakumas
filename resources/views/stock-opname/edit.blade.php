@section('title', __('messages.stockopname'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('stock-opname.index') }}" class="flex items-center justify-center">
                <svg class="w-7 h-7" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <path fill="currentColor"
                        d="M12 6v-6h-8v6h-4v7h16v-7h-4zM7 12h-6v-5h2v1h2v-1h2v5zM5 6v-5h2v1h2v-1h2v5h-6zM15 12h-6v-5h2v1h2v-1h2v5z">
                    </path>
                    <path fill="currentColor" d="M0 16h3v-1h10v1h3v-2h-16v2z"></path>
                </svg>
                <span class="px-2">@lang('messages.stockopname')</span>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <div class="py-2 flex flex-col">

        <div class="w-full px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('stock-opname.partials.feedback')
                </div>

                <form id="master-form" action="{{ route('stock-opname.update', Crypt::Encrypt($datas->id)) }}"
                    method="POST" enctype="multipart/form-data" class="w-full">
                    @csrf
                    @method('PUT')

                    {{-- Master --}}
                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2 md:p-6 md:space-y-4">

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">

                                    <div class="w-auto pb-4">
                                        <input type="hidden" name="branch_id" value="{{ $branch_id }}" />
                                        <label for="gudang_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.warehouse')</label>
                                        <select name="gudang_id" id="gudang_id" tabindex="1" required autofocus
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($gudangs as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $datas->gudang_id === $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('gudang_id')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="tanggal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.transactiondate')</label>
                                        <x-text-input type="date" name="tanggal" id="tanggal"
                                            data-date-format="dd-mm-yyyy" tabindex="2" required
                                            value="{{ $datas->tanggal }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('tanggal')" />
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

                                    <div class="flex flex-row items-center justify-end gap-2 md:gap-4">
                                        <x-primary-button type="submit" class="block" tabindex="7">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('stock-opname.index') }}" tabindex="8">
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

                    <div class="w-full" role="alert">
                        @include('stock-opname.partials.feedback')
                    </div>

                    <form id="form-order" method="POST" enctype="multipart/form-data" class="w-full">
                        @csrf

                        {{-- Detail --}}
                        <div
                            class="w-full shadow-lg rounded-md border bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                            <div class="p-4 space-y-2 md:p-6 md:space-y-4">
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
                                        @lang('messages.goods')
                                    </span>
                                </div>

                                <div
                                    class="border rounded-md border-primary-100 bg-primary-100 dark:border-primary-800 dark:bg-primary-850">
                                    <div class="p-4 overflow-scroll md:overflow-auto lg:overflow-hidden">
                                        <table id="order_table" class="w-full border-separate border-spacing-2">
                                            <thead>
                                                <tr>
                                                    <th class="w-1/3">@lang('messages.goods')</th>
                                                    <th class="w-auto">@lang('messages.unit')</th>
                                                    <th class="w-1/5">@lang('messages.stock')</th>
                                                    <th class="w-1/5">@lang('messages.minstock')</th>
                                                    <th class="w-auto">@lang('messages.description')</th>
                                                    <th class="w-auto">&nbsp;</th>
                                                </tr>
                                            </thead>

                                            <tbody id="detailBody">
                                                @include('stock-opname.partials.details', [
                                                    $details,
                                                    'viewMode' => false,
                                                ])
                                            </tbody>

                                            <tbody>
                                                <tr>
                                                    <td class="align-top">
                                                        <input type="hidden" name="branch_id"
                                                            value="{{ $branch_id }}" />
                                                        <input type="hidden" id="master_id" name="master_id"
                                                            value="{{ $datas->id }}" />
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
                                                        <select id="satuan_id" name="satuan_id" required
                                                            tabindex="10"
                                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                            <option value="">@lang('messages.choose')...</option>
                                                            @foreach ($satuans as $id => $name)
                                                                <option value="{{ $id }}">
                                                                    {{ $name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-input type="number" step="0.01" id="stock"
                                                            name="stock" required tabindex="11" />
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-input type="number" step="0.01" id="minstock"
                                                            name="minstock" required tabindex="12" />
                                                    </td>
                                                    <td class="align-top">
                                                        <x-text-input type="text" id="keterangan"
                                                            name="keterangan" tabindex="13" />
                                                    </td>
                                                </tr>
                                            </tbody>

                                        </table>
                                    </div>

                                    <div class="mt-4 mb-4 mr-4 flex flex-row justify-end gap-2 md:gap-4">
                                        <x-primary-button id="submit-detail" tabindex="14">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('stock-opname.index') }}" tabindex="15">
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

                deleteDetail = function(detailId) {
                    let idname = '#a-delete-detail-' + detailId;

                    var confirmation = confirm("Are you sure you want to delete this?");
                    if (confirmation) {
                        $(idname).closest("tr").remove();
                        $.ajax({
                            url: '{{ url('/warehouse/stock-opname/delete-detail') }}' + '/' + detailId,
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
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                };

                $("#barang_id").on("change keyup paste", function() {
                    var xbar = $('#barang_id option:selected').val();

                    $.ajax({
                        url: '{{ url('/warehouse/goods/get-goods-stock') }}' + "/" + xbar,
                        type: "GET",
                        dataType: 'json',
                        success: function(result) {
                            var p1 = result.p1
                            var p2 = result.p2
                            var p3 = result.p3
                            $('#satuan_id').val(p1);
                            $('#stock').val(p2);
                            $('#minstock').val(p3);
                            $('#stock').focus();
                        }
                    });
                });

                $("#submit-detail").on("click", function(e) {
                    e.preventDefault();
                    let key = $('#master_id').val();

                    $.ajax({
                        url: '{{ url('/warehouse/stock-opname/store-detail') }}' + '/' + key,
                        type: 'post',
                        dataType: 'json',
                        data: $('form#form-order').serialize(),
                        success: function(result) {
                            if (result.status !== 'Not Found') {
                                $('#detailBody').html(result.view);
                                $('#form-order')[0].reset();
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
