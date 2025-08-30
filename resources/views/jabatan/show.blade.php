@section('title', __('messages.jobposition'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('jabatan.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="size-7" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M21.435,11.5h-.38V8.12a1.626,1.626,0,0,0-1.62-1.62h-.63V6.12a1.625,1.625,0,0,0-3.25,0V11.5H8.445V6.12a1.625,1.625,0,0,0-3.25,0V6.5h-.63a1.62,1.62,0,0,0-1.62,1.62V11.5h-.38a.5.5,0,1,0,0,1h.38v3.37a1.622,1.622,0,0,0,1.62,1.63H5.2v.37a1.625,1.625,0,1,0,3.25,0V12.5h7.11v5.37a1.625,1.625,0,1,0,3.25,0V17.5h.63a1.628,1.628,0,0,0,1.62-1.63V12.5h.38a.5.5,0,1,0,0-1ZM5.2,16.5h-.63a.625.625,0,0,1-.62-.63V8.12a.623.623,0,0,1,.62-.62H5.2Zm2.25,1.37a.634.634,0,0,1-.63.63.625.625,0,0,1-.62-.63V6.12a.623.623,0,0,1,.62-.62.632.632,0,0,1,.63.62Zm10.36,0a.625.625,0,1,1-1.25,0V6.12a.625.625,0,0,1,1.25,0Zm2.25-2a.625.625,0,0,1-.62.63h-.63v-9h.63a.623.623,0,0,1,.62.62Z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.generalaffair')</span>
                    <span>@lang('messages.jobposition')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.view')</span>
        </h1>
    </div>

    <div class="py-2 flex flex-col">

        <div class="w-full px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('jabatan.partials.feedback')
                </div>

                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-4 space-y-2">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <label for="nama"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.name')</label>
                                    <x-text-span>{{ $datas->nama }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="islevel"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.positionlevel')</label>
                                    <x-text-span>{{ $datas->islevel }}</x-text-span>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="w-auto pb-4 lg:pb-12">
                                    <label for="keterangan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                    <x-text-span>{{ $datas->keterangan ? $datas->keterangan : '-' }}</x-text-span>
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

                                    <x-anchor-secondary href="{{ route('jabatan.index') }}" tabindex="5">
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

    @push('scripts')
    @endpush
</x-app-layout>
