@section('title', __('messages.kabupaten'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('kabupaten.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                    <g transform="matrix(1.1485 0 0 1.2471 -1.233 -1.917)" fill="#373737" stroke-width=".82858px">
                        <rect x="1.0737" y="1.5368" width="13.931" height="12.83" fill-opacity=".25" />
                        <path
                            d="M1.074 1.537v12.83h13.93V1.538H1.075zm.835.836h2.41c-.28 1.934.04 3.95 1.045 5.678.803 1.428 1.797 2.841 1.932 4.49-.05.342.328 1.14-.312.99H1.908V2.374zm3.253 0h9.006v2.854L6.434 8.24c-.86-1.37-1.42-2.926-1.37-4.523.001-.45.035-.899.098-1.344zm9.006 3.753v7.406H8.173c.072-1.603-.46-3.177-1.312-4.56 2.435-.95 4.87-1.898 7.307-2.846z"
                            color="currentColor" style="-inkscape-stroke:none" />
                    </g>
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.marketing')</span>
                    <span>@lang('messages.kabupaten')</span>
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
                    @include('kabupaten.partials.feedback')
                </div>

                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-4 space-y-2">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <span for="propinsi_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.propinsi')</span>
                                    <x-text-span>{{ $datas->propinsi->nama }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="nama"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.kabupaten')</span>
                                    <x-text-span>{{ $datas->nama }}</x-text-span>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="w-auto pb-4 lg:pb-12">
                                    <span for="keterangan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</span>
                                    <x-text-span>{{ $datas->keterangan }}</x-text-span>
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

                                    <x-anchor-secondary href="{{ route('kabupaten.index') }}" tabindex="6">
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
