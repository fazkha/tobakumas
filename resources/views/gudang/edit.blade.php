@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@section('title', __('messages.warehouse'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('gudang.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="w-7 h-7" viewBox="0 0 15 15" version="1.1" id="warehouse"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M13.5,5c-0.0762,0.0003-0.1514-0.0168-0.22-0.05L7.5,2L1.72,4.93C1.4632,5.0515,1.1565,4.9418,1.035,4.685&#xA;&#x9;S1.0232,4.1215,1.28,4L7.5,0.92L13.72,4c0.2761,0.0608,0.4508,0.3339,0.39,0.61C14.0492,4.8861,13.7761,5.0608,13.5,5z M5,10H2v3h3&#xA;&#x9;V10z M9,10H6v3h3V10z M13,10h-3v3h3V10z M11,6H8v3h3V6z M7,6H4v3h3V6z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.warehouse')</span>
                    <span>@lang('messages.location')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <form id="gudang-form" action="{{ route('gudang.update', Crypt::Encrypt($datas->id)) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('gudang.partials.feedback')
                    </div>

                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">

                                    <div class="w-auto pb-4">
                                        <label for="branch_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.branch')</label>
                                        <input type="hidden" name="branch_id" value="{{ $datas->branch_id }}" />
                                        <x-text-span>{{ $datas->branch->nama }}</x-text-span>
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="kode"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.locationcode')</label>
                                        <x-text-input type="text" name="kode" id="kode" tabindex="1"
                                            autofocus
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.locationcode') }}"
                                            required value="{{ old('kode', $datas->kode) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('kode')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="nama"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.locationname')</label>
                                        <x-text-input type="text" name="nama" id="nama" tabindex="2"
                                            required
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.locationname') }}"
                                            value="{{ old('nama', $datas->nama) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                        <x-text-input type="text" name="keterangan" id="keterangan" tabindex="3"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.description') }}"
                                            value="{{ old('keterangan', $datas->keterangan) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <label for="alamat"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.locationaddress')</label>
                                        <x-text-input type="text" name="alamat" id="alamat" tabindex="4"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.locationaddress') }}"
                                            required value="{{ old('alamat', $datas->alamat) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="propinsi_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.propinsi')</label>
                                        <select name="propinsi_id" id="propinsi_id" tabindex="5" required
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($propinsis as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $datas->propinsi_id == $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('propinsi_id')" />
                                    </div>

                                    <div class="w-auto pb-4 lg:pb-12">
                                        <label for="kabupaten_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.kabupaten')</label>
                                        <select name="kabupaten_id" id="kabupaten_id" tabindex="6" required
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($kabupatens as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $datas->kabupaten_id == $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('kabupaten_id')" />
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
                                        <x-anchor-secondary href="{{ route('gudang.index') }}" tabindex="7">
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
    </form>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $("#propinsi_id").on("change keyup paste", function() {
                var xpr = $('#propinsi_id option:selected').val();
                if (xpr.trim()) {
                    xprop = xpr;
                } else {
                    xprop = '_';
                }

                $.ajax({
                    url: '{{ url('/marketing/kecamatan/depend-drop-kab') }}' + "/" + xprop,
                    type: "GET",
                    dataType: 'json',
                    success: function(result) {
                        $('#kabupaten_id').empty();
                        $('#kabupaten_id').append($('<option>', {
                            value: null,
                            text: "{{ __('messages.choose') }}..."
                        }));
                        var data = result.kabs;
                        $.each(data, function(item, index) {
                            $('#kabupaten_id').append($('<option>', {
                                value: index,
                                text: item,
                                selected: (index === {{ $datas->kabupaten_id }} ?
                                    true : false)
                            }));
                        });
                        $("#kabupaten_id").focus();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
