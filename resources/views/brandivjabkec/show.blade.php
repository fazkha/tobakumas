@php
    $pro = '';
    $kab = '';
    $i = 0;
@endphp
@section('title', __('messages.brandivjabkec'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('brandivjabkec.index') }}" class="flex items-center justify-center">
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
                    <span>@lang('messages.brandivjabkec')</span>
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
                    @include('brandivjabkec.partials.feedback')
                </div>

                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-4 space-y-2">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <span for="brandivjab_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.jobposition')</span>
                                    <x-text-span>{{ $brandivjabs->nama }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="keterangan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</span>
                                    <x-text-span>{{ $datas[0]->keterangan }}</x-text-span>
                                </div>

                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="w-auto pb-4 lg:pb-12">
                                    <span for="propinsis"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.region')</span>
                                    <x-text-span>
                                        <div class="p-2 flex flex-col gap-2">
                                            @foreach ($kecamatans as $kecamatan)
                                                @if ($pro !== $kecamatan->namapropinsi)
                                                    @php
                                                        $pro = $kecamatan->namapropinsi;
                                                    @endphp
                                                    <span class="font-bold">{{ $kecamatan->namapropinsi }}</span>
                                                @endif

                                                @if ($kab !== $kecamatan->namakabupaten)
                                                    @php
                                                        $kab = $kecamatan->namakabupaten;
                                                    @endphp
                                                    <span class="font-bold px-8">{{ $kecamatan->namakabupaten }}</span>
                                                @endif
                                                <span class="px-16">
                                                    <div class="inline-flex items-center">
                                                        @php
                                                            if ($i < count($datas)) {
                                                                if ($datas[$i]->kecamatan_id == $kecamatan->id) {
                                                                    echo '<span>✔️</span>';
                                                                } else {
                                                                    echo '<span class="opacity-30">🚫</span>';
                                                                }
                                                            } else {
                                                                echo '<span class="opacity-30">🚫</span>';
                                                        } @endphp
                                                        <label class='pl-2'>{{ $kecamatan->nama }}</label>
                                                    </div>
                                                </span>
                                                @php
                                                    if ($i < count($datas)) {
                                                        if ($datas[$i]->kecamatan_id == $kecamatan->id) {
                                                            $i++;
                                                        }
                                                    }
                                                @endphp
                                            @endforeach
                                        </div>
                                    </x-text-span>
                                </div>

                                <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                    <div class="pr-2">
                                        <div class="inline-flex items-center">
                                            @if ($datas[0]->isactive == '1')
                                                <span>✔️</span>
                                            @endif
                                            @if ($datas[0]->isactive == '0')
                                                <span>❌</span>
                                            @endif
                                            <label class='pl-2'>@lang('messages.active')</label>
                                        </div>
                                    </div>

                                    <x-anchor-secondary href="{{ route('brandivjabkec.index') }}" tabindex="6">
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
