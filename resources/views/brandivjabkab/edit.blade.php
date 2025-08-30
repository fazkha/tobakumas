@php
    $p = '';
    $i = 0;
@endphp
@section('title', __('messages.brandivjabkab'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('brandivjabkab.index') }}" class="flex items-center justify-center">
                <svg class="size-7" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 329.966 329.966"
                    style="enable-background:new 0 0 329.966 329.966;" xml:space="preserve">
                    <path id="XMLID_822_" d="M218.317,139.966h-38.334v-45V15c0-8.284-6.716-15-15-15h-120c-8.284,0-15,6.716-15,15v79.966
c0,8.284,6.716,15,15,15h105v30h-38.334c-52.383,0-95,42.617-95,95s42.617,95,95,95h106.668c52.383,0,95-42.617,95-95
S270.7,139.966,218.317,139.966z M59.983,79.966V30h90v49.966H59.983z M218.317,299.966H111.649c-35.841,0-65-29.159-65-65
s29.159-65,65-65h38.334v65c0,8.284,6.716,15,15,15c8.284,0,15-6.716,15-15v-65h38.334c35.841,0,65,29.159,65,65
S254.158,299.966,218.317,299.966z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.marketing')</span>
                    <span>@lang('messages.brandivjabkab')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <form action="{{ route('brandivjabkab.updateDetail', Crypt::encrypt($datas[0]->brandivjab_id)) }}" method="POST"
        enctype="multipart/form-data">
        @csrf

        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('brandivjabkab.partials.feedback')
                    </div>

                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">

                                    <div class="w-auto pb-4">
                                        <label for="brandivjab_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.jobposition')</label>
                                        <select name="brandivjab_id" id="brandivjab_id" tabindex="1" required
                                            autofocus
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">
                                                @lang('messages.choose')...
                                            </option>
                                            @foreach ($brandivjabs as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $datas[0]->brandivjab_id == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('brandivjab_id')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                        <x-text-input type="text" name="keterangan" id="keterangan" tabindex="3"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.description') }}"
                                            value="{{ $datas[0]->keterangan }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                                    </div>

                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4 lg:pb-12">
                                        <label for="propinsis"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.region')</label>
                                        <x-text-span>
                                            <div class="p-2 flex flex-col gap-2">
                                                @foreach ($kabupatens as $kabupaten)
                                                    @if ($p !== $kabupaten->namapropinsi)
                                                        @php
                                                            $p = $kabupaten->namapropinsi;
                                                        @endphp
                                                        <span class="font-bold">{{ $kabupaten->namapropinsi }}</span>
                                                    @endif
                                                    <span class="px-4">
                                                        <label class="cursor-pointer flex flex-row gap-2 items-center">
                                                            <input type="checkbox" name="kabs[]"
                                                                value="{{ $kabupaten->id }}"
                                                                @php if ($i < count($datas)) {
                                                                    if ($datas[$i]->kabupaten_id == $kabupaten->id) {
                                                                        echo 'checked';
                                                                    }
                                                                } @endphp
                                                                class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md">
                                                            <span
                                                                class="pr-4 group-hover:text-blue-500 transition-colors duration-300">
                                                                {{ $kabupaten->nama }}
                                                            </span>
                                                        </label>
                                                    </span>
                                                    @php
                                                        if ($i < count($datas)) {
                                                            if ($datas[$i]->kabupaten_id == $kabupaten->id) {
                                                                $i++;
                                                            }
                                                        }
                                                    @endphp
                                                @endforeach
                                            </div>
                                        </x-text-span>
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="dark:bg-black/10">
                                            <label class="cursor-pointer flex flex-col md:flex-row md:gap-2">
                                                <input type="checkbox" id="isactive" name="isactive"
                                                    class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md"
                                                    {{ $datas[0]->isactive == 1 ? 'checked' : '' }}>
                                                <span
                                                    class="pr-4 group-hover:text-blue-500 transition-colors duration-300">
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
                                        <x-anchor-secondary href="{{ route('brandivjabkab.index') }}" tabindex="6">
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
