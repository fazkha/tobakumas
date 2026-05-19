@section('title', __('messages.resign'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('resign.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="size-5" viewBox="0 0 24 24" id="sign-out-double-arrow-left"
                    data-name="Line Color" xmlns="http://www.w3.org/2000/svg" class="icon line-color">
                    <polyline id="secondary" points="6 15 3 12 6 9"
                        style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                    </polyline>
                    <polyline id="secondary-2" data-name="secondary" points="11 15 8 12 11 9"
                        style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                    </polyline>
                    <line id="secondary-3" data-name="secondary" x1="8" y1="12" x2="17"
                        y2="12"
                        style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                    </line>
                    <path id="primary" d="M10,5V4a1,1,0,0,1,1-1h9a1,1,0,0,1,1,1V20a1,1,0,0,1-1,1H11a1,1,0,0,1-1-1V19"
                        style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                    </path>
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.humanresource')</span>
                    <span>@lang('messages.resign')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <form action="{{ route('resign.update', Crypt::Encrypt($datas->id)) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('resign.partials.feedback')
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
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.employee')</span>
                                        <x-text-span>{{ $datas->user_nama }}</x-text-span>
                                    </div>

                                    <div class="w-auto pb-4">
                                        <div>
                                            <span for="tanggal_mulai"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.date')</span>
                                            <x-text-span>{{ \Carbon\Carbon::parse($datas->tanggal)->translatedFormat('l, d F Y') }}</x-text-span>
                                        </div>
                                    </div>

                                    <div class="w-auto pb-4">
                                        <span for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</span>
                                        <x-text-span>{{ $datas->keterangan }}</x-text-span>
                                    </div>

                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <label for="tanggapan_pc"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.response')
                                            PC</label>
                                        <x-text-span>{{ $datas->tanggapan_pc }}</x-text-span>
                                    </div>

                                    <div class="w-auto pb-4 lg:pb-12">
                                        <label for="tanggapan_hrd"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.response')
                                            HRD</label>
                                        <x-textarea-input name="tanggapan_hrd" id="tanggapan_hrd" tabindex="1"
                                            rows="7" maxlength="250"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.handling') }}">{{ old('tanggapan_hrd', $datas->tanggapan_hrd) }}
                                        </x-textarea-input>

                                        <x-input-error class="mt-2" :messages="$errors->get('tanggapan_hrd')" />
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="w-auto flex flex-row items-center gap-2 md:flex-row md:gap-2">
                                            <input type="hidden" name="status" id="statusValue"
                                                value="{{ $datas->approved_hrd ?? 0 }}">
                                            <label class="cursor-pointer">
                                                <input type="checkbox" id="statusCheckbox" class="hidden">
                                                <div id="checkboxUI"
                                                    class="w-7 h-7 flex items-center justify-center rounded-lg border border-gray-400 transition-all duration-200 scale-100 hover:scale-110">
                                                    <span id="checkboxIcon" class="text-white text-sm font-bold"></span>
                                                </div>
                                            </label>
                                            <span id="responder"
                                                class="pr-4 group-hover:text-blue-500 transition-colors duration-300 text-left w-1/2 md:w-full">
                                                {{ $datas->approved_hrd === 1 ? 'Disetujui' : ($datas->approved_hrd === 2 ? 'Ditolak' : 'Menunggu') }}
                                            </span>
                                        </div>

                                        <x-primary-button type="submit" class="block" tabindex="2">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('resign.index') }}" tabindex="3">
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
            const ui = document.getElementById('checkboxUI');
            const icon = document.getElementById('checkboxIcon');
            const responder = document.getElementById('responder');

            let state = parseInt(hidden.value) || 0;

            function render() {
                ui.classList.remove('bg-blue-600', 'bg-red-600', 'bg-gray-600');

                if (state === 0) {
                    ui.classList.add('bg-gray-600');
                    responder.innerHTML = '{{ __('messages.pending') }}';
                    icon.innerHTML = '';
                    checkbox.checked = false;
                    checkbox.indeterminate = false;
                } else if (state === 1) {
                    ui.classList.add('bg-blue-600');
                    responder.innerHTML = '{{ __('messages.approved') }}';
                    icon.innerHTML = '✓';
                    checkbox.checked = true;
                    checkbox.indeterminate = false;
                } else {
                    ui.classList.add('bg-red-600');
                    responder.innerHTML = '{{ __('messages.notapproved') }}';
                    icon.innerHTML = '−';
                    checkbox.checked = false;
                    checkbox.indeterminate = true;
                }

                hidden.value = state;
            }

            ui.addEventListener('click', () => {
                state = (state + 1) % 3;
                render();
            });

            render();
        </script>
    @endpush
</x-app-layout>
