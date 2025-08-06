@section('title', __('messages.unit'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('units.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="w-7 h-7" viewBox="-0.77 0 50 50" xmlns="http://www.w3.org/2000/svg">
                    <g id="_3" data-name="3" transform="translate(-290.767 -130.5)">
                        <path id="Path_224" data-name="Path 224"
                            d="M337.753,130.5h-45.5c-.818,0-1.483.485-1.483,1.081a1.148,1.148,0,0,0,.943,1h46.584a1.147,1.147,0,0,0,.939-1C339.233,130.985,338.57,130.5,337.753,130.5Z" />
                        <path id="Path_225" data-name="Path 225"
                            d="M335.961,177.3h-.439V162.5a20.258,20.258,0,0,0,.013-3.459,20.081,20.081,0,0,0-16.913-19.822v-2.266h15.692a1.406,1.406,0,0,0,1.446-1.364l.947-1.954H293.294l.889,1.954a1.407,1.407,0,0,0,1.448,1.364h16.3v2.32c-.092.016-.184.029-.275.046a20.087,20.087,0,0,0-16.26,18.789,1.674,1.674,0,0,0-.137.656V177.3h-1.215a1.6,1.6,0,0,0,0,3.2h41.92a1.6,1.6,0,0,0,0-3.2Zm-20.018-5.067v-2.577h-.732v2.577a13.6,13.6,0,0,1-13.163-13.662c0-.043.005-.084.005-.126h2.381v-.73H302.09a13.584,13.584,0,0,1,13.121-12.807v2.86h.732V144.9a13.587,13.587,0,0,1,13.134,12.808h-3.338v.73h3.367v-.116c0,.081.013.159.013.242A13.6,13.6,0,0,1,315.943,172.231Z" />
                        <path id="Path_226" data-name="Path 226"
                            d="M316.1,152.925l-.037-.005v-2.839c0-.172-.217-.31-.485-.31s-.485.138-.485.31v2.839l-.038.005a5.375,5.375,0,1,0,1.045,0Zm-.524,10a4.629,4.629,0,0,1-.87-9.169V156a2.367,2.367,0,0,0-1.65,2.22,2.527,2.527,0,0,0,5.044,0,2.368,2.368,0,0,0-1.65-2.22v-2.247a4.629,4.629,0,0,1-.874,9.169Z" />
                    </g>
                </svg>
                <span class="px-2">@lang('messages.unit')</span>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.view')</span>
        </h1>
    </div>

    <div class="py-2 flex flex-col">

        <div class="w-full px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('satuan.partials.feedback')
                </div>

                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-4 space-y-2 md:p-6 md:space-y-4">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <label for="singkatan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.abbreviation')</label>
                                    <x-text-span>{{ $datas->singkatan }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="nama_lengkap"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.unitname')</label>
                                    <x-text-span>{{ $datas->nama_lengkap }}</x-text-span>
                                </div>

                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="pb-4 lg:pb-12">
                                    <label for="keterangan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                    <x-text-span>{{ $datas->keterangan }}</x-text-span>
                                </div>

                                <div class="flex flex-row items-center justify-end gap-2 md:gap-4">
                                    <div class="pr-2">
                                        <div class="inline-flex items-center">
                                            @if ($datas->isactive == '1')
                                                <span>✔️</span>
                                            @endif
                                            @if ($datas->isactive == '0')
                                                <span>❌</span>
                                            @endif
                                            <label class='pl-2'>@lang('messages.active')</label>
                                        </div>
                                    </div>

                                    <x-anchor-secondary href="{{ route('units.index') }}" tabindex="1" autofocus>
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
