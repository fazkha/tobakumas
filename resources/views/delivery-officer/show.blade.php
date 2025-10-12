@php
    $pro = '';
    $kab = '';
    $i = 0;
@endphp
@section('title', __('messages.brandivjabkec'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('area-officer.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="style=linear">
                        <g id="notification-direct">
                            <path id="vector"
                                d="M2.99219 14L6.49219 14C7.1217 14 7.71448 14.2964 8.09219 14.8L9.14219 16.2C9.5199 16.7036 10.1127 17 10.7422 17L11.9922 17L13.2422 17C13.8717 17 14.4645 16.7036 14.8422 16.2L15.8922 14.8C16.2699 14.2964 16.8627 14 17.4922 14L20.9922 14"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path id="vector_2"
                                d="M13.8469 2.75L8.74219 2.75C5.42848 2.75 2.74219 5.43629 2.74219 8.75L2.74219 15.2578C2.74219 18.5715 5.42848 21.2578 8.74218 21.2578L15.25 21.2578C18.5637 21.2578 21.25 18.5715 21.25 15.2578L21.25 10.1531"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <circle id="vector_3" cx="18.4738" cy="5.52617" r="2.77617" stroke="currentColor"
                                stroke-width="1.5" />
                        </g>
                    </g>
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.delivery')</span>
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
                    @include('area-officer.partials.feedback')
                </div>

                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-4 space-y-2">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <span for="brandivjab_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.jobposition')</span>
                                    <x-text-span>{{ $datas[0]->pegawai->nama_lengkap }}</x-text-span>
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
                                            @foreach ($customers as $customer)
                                                @if ($pro !== $customer->namapropinsi)
                                                    @php
                                                        $pro = $customer->namapropinsi;
                                                    @endphp
                                                    <span class="font-bold">{{ $customer->namapropinsi }}</span>
                                                @endif

                                                @if ($kab !== $customer->namakabupaten)
                                                    @php
                                                        $kab = $customer->namakabupaten;
                                                    @endphp
                                                    <span class="font-bold px-8">{{ $customer->namakabupaten }}</span>
                                                @endif
                                                <span class="px-16">
                                                    <div class="inline-flex items-center">
                                                        @php
                                                            if ($i < count($datas)) {
                                                                if ($datas[$i]->customer_id == $customer->id) {
                                                                    echo '<span>✔️</span>';
                                                                } else {
                                                                    echo '<span class="opacity-30">🚫</span>';
                                                                }
                                                            } else {
                                                                echo '<span class="opacity-30">🚫</span>';
                                                        } @endphp
                                                        <label class='pl-2'>{{ $customer->nama }}</label>
                                                    </div>
                                                </span>
                                                @php
                                                    if ($i < count($datas)) {
                                                        if ($datas[$i]->customer_id == $customer->id) {
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

                                    <x-anchor-secondary href="{{ route('area-officer.index') }}" tabindex="6">
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
