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
            <span class="px-2 font-semibold">@lang('messages.new')</span>
        </h1>
    </div>

    <form action="{{ route('kabupaten.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

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
                                        <label for="nama"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.kabupaten')</label>
                                        <x-text-input type="text" name="nama" id="nama" tabindex="2"
                                            required placeholder="{{ __('messages.enter') }} {{ __('messages.name') }}"
                                            value="{{ old('nama') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4 lg:pb-12">
                                        <label for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                        <x-text-input type="text" name="keterangan" id="keterangan" tabindex="3"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.description') }}"
                                            value="{{ old('keterangan') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="w-auto">
                                            <label
                                                class="cursor-pointer flex flex-col items-center md:flex-row md:gap-2">
                                                <input type="checkbox" id="isactive" name="isactive"
                                                    class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md"
                                                    checked>
                                                <span
                                                    class="pr-4 group-hover:text-blue-500 transition-colors duration-300 text-right w-1/2 md:w-full">
                                                    @lang('messages.active')
                                                </span>
                                            </label>
                                        </div>

                                        <x-primary-button type="submit" class="block" tabindex="5">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
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
    </form>

    @push('scripts')
    @endpush
</x-app-layout>
