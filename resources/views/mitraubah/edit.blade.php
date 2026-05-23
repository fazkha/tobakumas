@section('title', __('messages.mitraubah'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('mitraubah.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" version="1.1">
                    <path style="fill:none;stroke:#444444;stroke-width:2" d="M 8,16 15,14 83,6 92,78 29,94 22,92 z" />
                    <path style="fill:#287293;stroke:#888888" d="m 8,16 7,-2 68,-8 3,25 -67,9 -6,1 z" />
                    <path style="fill:none;stroke:#dddddd" d="m 15,15 c 1,7 3,19 4,23" />
                    <path style="fill:#cccccc;stroke:#888888"
                        d="m 19,39 -6,1 9,52 7,2 C 29,94 92,79 92,78 92,77 86,30 86,30 z" />
                    <path style="fill:#eeeeee;stroke:#aaaaaa;"
                        d="m 86,30 c 0,0 4,22 11,33 -3,6 -14,14 -19,15 -6,1 -45,13 -45,13 0,0 -4,-2 -6,-11 L 19,39 z" />
                    <path style="fill:#dddddd;stroke:#aaaaaa;"
                        d="M 97,63 C 96,62 93,60 93,60 L 81,77 c 0,0 11,-6 16,-14" />
                    <path style="fill:#4444444"
                        d="m 56,41 22,-4 1,5 c 0,0 -8,13 -5,31 l -6,2 c 0,0 -4,-13 4,-31 L 57,47 z M 32,54 c 6,-2 7,-7 8,-11 l 6,-1 8,36 -7,2 -6,-26 c 0,0 -3,5 -7,6 z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.humanresource')</span>
                    <span>@lang('messages.mitraubah')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <form action="{{ route('mitraubah.update', Crypt::Encrypt($datas->id)) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('mitraubah.partials.feedback')
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
                                        <span for="jenis_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.izin')</span>
                                        <x-text-span>{{ $datas->jenis_ubah == 1 ? 'Tambah Hari' : 'Ganti Hari' }}</x-text-span>
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <span for="tanggal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.date')</span>
                                        <x-text-span>{{ \Carbon\Carbon::parse($datas->tanggal)->translatedFormat('l, d F Y') }}</x-text-span>
                                    </div>

                                    <div class="w-auto pb-4 lg:pb-12">
                                        <span for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</span>
                                        <x-text-span>{{ $datas->keterangan }}</x-text-span>
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
                                        <x-anchor-secondary href="{{ route('mitraubah.index') }}" tabindex="3">
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
