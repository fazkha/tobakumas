@section('title', __('messages.announcement'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('announcement.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill="none" stroke="currentColor" stroke-width="2"
                        d="M11,15 C14,15 19,19 19,19 L19,3 C19,3 14,7 11,7 C11,7 11,15 11,15 Z M5,15 L8,23 L12,23 L9,15 M19,14 C20.657,14 22,12.657 22,11 C22,9.343 20.657,8 19,8 M11,19 C11.9999997,18.9999994 14,18 14,16 M2,11 C2,7.88888889 3.7912,7 6,7 L11,7 L11,15 L6,15 C3.7912,15 2,14.1111111 2,11 Z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.humanresource')</span>
                    <span>@lang('messages.announcement')</span>
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
                    @include('pengumuman.partials.feedback')
                </div>

                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-4 space-y-2">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <span for="tanggal"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.date')</span>
                                    <x-text-span>{{ $datas->tanggal }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="judul"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.title_head')</span>
                                    <x-text-span>{{ $datas->judul }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="keterangan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</span>
                                    <x-text-span>{{ $datas->keterangan }}</x-text-span>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="w-auto pb-4 lg:pb-12">
                                    <label for="gambar"
                                        class="text-center block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.picture')</label>
                                    <div class="mt-2 flex justify-center">
                                        <img id="image-preview" class="w-full lg:w-3/5 h-auto border rounded-lg"
                                            @if ($datas->gambar) src="{{ asset($datas->lokasi . '/' . $datas->gambar) }}" @else src="{{ url('/') }}/images/0cd6be830e32f80192d496e50cfa9dbc.jpg" @endif
                                            alt="o.o" />
                                    </div>
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

                                    <x-anchor-secondary href="{{ route('announcement.index') }}" tabindex="1"
                                        autofocus>
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
