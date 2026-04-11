@section('title', __('messages.mitraizin'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('mitraizin.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="size-7" viewBox="0 0 16 16" id="request-16px"
                    xmlns="http://www.w3.org/2000/svg">
                    <path id="Path_49" data-name="Path 49"
                        d="M30.5,16a.489.489,0,0,1-.191-.038A.5.5,0,0,1,30,15.5V13h-.5A2.5,2.5,0,0,1,27,10.5v-8A2.5,2.5,0,0,1,29.5,0h11A2.5,2.5,0,0,1,43,2.5v8A2.5,2.5,0,0,1,40.5,13H33.707l-2.853,2.854A.5.5,0,0,1,30.5,16Zm-1-15A1.5,1.5,0,0,0,28,2.5v8A1.5,1.5,0,0,0,29.5,12h1a.5.5,0,0,1,.5.5v1.793l2.146-2.147A.5.5,0,0,1,33.5,12h7A1.5,1.5,0,0,0,42,10.5v-8A1.5,1.5,0,0,0,40.5,1ZM36,9a1,1,0,1,0-1,1A1,1,0,0,0,36,9Zm1-4a2,2,0,0,0-4,0,.5.5,0,0,0,1,0,1,1,0,1,1,1,1,.5.5,0,0,0,0,1A2,2,0,0,0,37,5Z"
                        transform="translate(-27)" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.humanresource')</span>
                    <span>@lang('messages.mitraizin')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <form action="{{ route('mitraizin.update', Crypt::Encrypt($datas->id)) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('mitraizin.partials.feedback')
                    </div>

                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">

                                    <div class="w-auto pb-4">
                                        <span for="branch_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.branch')</span>
                                        <x-text-span>{{ $datas->branch_nama }}</x-text-span>
                                    </div>

                                    <div class="w-auto pb-4">
                                        <span for="mitra_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.mitraname')</span>
                                        <x-text-span>{{ $datas->mitra_nama }}</x-text-span>
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <span for="jenis_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.izin')</span>
                                        <x-text-span>{{ $datas->jenis_nama }}</x-text-span>
                                    </div>

                                    <div class="w-auto pb-4 lg:pb-12">
                                        <div class="flex flex-row flex-wrap items-center gap-2">
                                            <div>
                                                <span for="tanggal_mulai"
                                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.startdate')</span>
                                                <x-text-span>{{ $datas->tanggal_mulai->translatedFormat('l, d F Y') }}</x-text-span>
                                                <x-text-span
                                                    class="text-bold">{{ $datas->tanggal_mulai->translatedFormat('H:i') }}</x-text-span>
                                            </div>
                                            <div>
                                                <span for="tanggal_selesai"
                                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.enddate')</span>
                                                <x-text-span>{{ $datas->tanggal_selesai->translatedFormat('l, d F Y') }}</x-text-span>
                                                <x-text-span
                                                    class="text-bold">{{ $datas->tanggal_selesai->translatedFormat('H:i') }}</x-text-span>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md" --}}
                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="w-auto">
                                            <input type="hidden" name="status" id="statusValue"
                                                value="{{ $datas->approved_hrd ?? 0 }}">
                                            <label
                                                class="cursor-pointer flex flex-row items-center gap-2 md:flex-row md:gap-2">
                                                <input type="checkbox" id="statusCheckbox" tabindex="1"
                                                    class="w-7 h-7 rounded-lg"
                                                    {{ $datas->approved_hrd == 1 ? 'checked' : '' }}>
                                                <span
                                                    class="pr-4 group-hover:text-blue-500 transition-colors duration-300 text-right w-1/2 md:w-full">
                                                    @lang('messages.approval')
                                                </span>
                                            </label>
                                        </div>

                                        <x-primary-button type="submit" class="block" tabindex="2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('mitraizin.index') }}" tabindex="3">
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
        <script>
            const checkbox = document.getElementById('statusCheckbox');
            const hidden = document.getElementById('statusValue');

            let state = 0;
            // 0 = unchecked
            // 1 = checked
            // 2 = indeterminate

            if (checkbox.checked) {
                state = 1;
                hidden.value = 1;
            } else {
                state = 0;
                hidden.value = 0;
            }

            // checkbox.addEventListener('click', function(e) {
            //     e.preventDefault();

            //     state = (state + 1) % 3;

            //     if (state === 0) {
            //         checkbox.checked = false;
            //         checkbox.indeterminate = false;
            //         hidden.value = 0;
            //     } else if (state === 1) {
            //         checkbox.checked = true;
            //         checkbox.indeterminate = false;
            //         hidden.value = 1;
            //     } else {
            //         checkbox.checked = false;
            //         checkbox.indeterminate = true;
            //         hidden.value = 2;
            //     }
            // });
        </script>
    @endpush
</x-app-layout>
