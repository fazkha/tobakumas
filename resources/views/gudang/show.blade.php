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
                <span class="px-2">@lang('messages.location')</span>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.view')</span>
        </h1>
    </div>

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
                                    <x-text-span>{{ $datas->kode }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="nama"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.locationname')</label>
                                    <x-text-span>{{ $datas->nama }}</x-text-span>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="w-auto pb-4">
                                    <label for="alamat"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.locationaddress')</label>
                                    <x-text-span>{{ $datas->alamat }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4 lg:pb-12">
                                    <label for="keterangan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                    <x-text-span>{{ $datas->keterangan ? $datas->keterangan : '...' }}</x-text-span>
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

                                    <x-anchor-secondary href="{{ route('gudang.index') }}" tabindex="1" autofocus>
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
</x-app-layout>
