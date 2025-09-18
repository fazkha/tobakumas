@section('title', __('messages.kecamatan'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('kecamatan.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="size-7" viewBox="-1.5 0 19 19" xmlns="http://www.w3.org/2000/svg"
                    class="cf-icon-svg">
                    <path
                        d="M15.084 15.2H.916a.264.264 0 0 1-.254-.42l2.36-4.492a.865.865 0 0 1 .696-.42h.827a9.51 9.51 0 0 0 .943 1.108H3.912l-1.637 3.116h11.45l-1.637-3.116h-1.34a9.481 9.481 0 0 0 .943-1.109h.591a.866.866 0 0 1 .696.421l2.36 4.492a.264.264 0 0 1-.254.42zM11.4 7.189c0 2.64-2.176 2.888-3.103 5.46a.182.182 0 0 1-.356 0c-.928-2.572-3.104-2.82-3.104-5.46a3.282 3.282 0 0 1 6.563 0zm-1.86-.005a1.425 1.425 0 1 0-1.425 1.425A1.425 1.425 0 0 0 9.54 7.184z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.marketing')</span>
                    <span>@lang('messages.kecamatan')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.new')</span>
        </h1>
    </div>

    <form action="{{ route('kecamatan.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('kecamatan.partials.feedback')
                    </div>

                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">

                                    <div class="w-auto pb-4">
                                        <label for="propinsi_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.propinsi')</label>
                                        <select name="propinsi_id" id="propinsi_id" tabindex="1" required autofocus
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($propinsis as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('propinsi_id') == $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('propinsi_id')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="kabupaten_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.kabupaten')</label>
                                        <select name="kabupaten_id" id="kabupaten_id" tabindex="2" required
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($kabupatens as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('kabupaten_id') == $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('kabupaten_id')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="nama"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.kecamatan')</label>
                                        <x-text-input type="text" name="nama" id="nama" tabindex="3"
                                            required placeholder="{{ __('messages.enter') }} {{ __('messages.name') }}"
                                            value="{{ old('nama') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4 lg:pb-12">
                                        <label for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                        <x-text-input type="text" name="keterangan" id="keterangan" tabindex="4"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.description') }}"
                                            value="{{ old('keterangan') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="w-auto">
                                            <label
                                                class="cursor-pointer flex flex-col items-center md:flex-row md:gap-2">
                                                <input type="checkbox" id="isactive" name="isactive" tabindex="5"
                                                    class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md"
                                                    checked>
                                                <span
                                                    class="pr-4 group-hover:text-blue-500 transition-colors duration-300 text-right w-1/2 md:w-full">
                                                    @lang('messages.active')
                                                </span>
                                            </label>
                                        </div>

                                        <x-primary-button type="submit" class="block" tabindex="6">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('kecamatan.index') }}" tabindex="7">
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
    </form>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script type="text/javascript">
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
                                text: item
                            }));
                        });
                        $("#kabupaten_id").focus();
                    }
                });
            });
        </script>
    @endpush
</x-app-layout>
