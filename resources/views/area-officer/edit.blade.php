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
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <form action="{{ route('area-officer.updateDetail', Crypt::encrypt($datas[0]->pegawai_id)) }}" method="POST"
        enctype="multipart/form-data">
        @csrf

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
                                        <label for="pegawai_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.officer')</label>
                                        <select name="pegawai_id" id="pegawai_id" tabindex="1" required autofocus
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">
                                                @lang('messages.choose')...
                                            </option>
                                            @foreach ($petugas as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $datas[0]->pegawai_id == $id ? 'selected' : '' }}>
                                                    {{ $name }}
                                                </option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('pegawai_id')" />
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
                                        <span for="propinsis"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.customer')</span>
                                        <x-text-span>
                                            <div class="p-2 flex flex-col gap-2">
                                                <table>
                                                    @foreach ($customers as $customer)
                                                        @if ($pro !== $customer->namapropinsi)
                                                            @php
                                                                $pro = $customer->namapropinsi;
                                                            @endphp
                                                            <tr>
                                                                <td colspan="2">
                                                                    <span
                                                                        class="font-bold">{{ $customer->namapropinsi }}</span>
                                                                </td>
                                                            </tr>
                                                        @endif

                                                        @if ($kab !== $customer->namakabupaten)
                                                            @php
                                                                $kab = $customer->namakabupaten;
                                                            @endphp
                                                            <tr>
                                                                <td colspan="2">
                                                                    <span
                                                                        class="font-bold px-4 md:px-8">{{ $customer->namakabupaten }}</span>
                                                                </td>
                                                            </tr>
                                                        @endif
                                                        <tr>
                                                            <td class="w-4/5">
                                                                <div class="pl-8 md:pl-16 py-2">
                                                                    <label
                                                                        class="cursor-pointer flex flex-row gap-2 items-center">
                                                                        <input type="checkbox" name="custs[]"
                                                                            value="{{ $customer->id }}"
                                                                            @php if ($i < count($datas)) {
                                                                        if ($datas[$i]->customer_id == $customer->id) {
                                                                            echo 'checked';
                                                                        }
                                                                    } @endphp
                                                                            class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md" />
                                                                        <span
                                                                            class="pr-4 group-hover:text-blue-500 transition-colors duration-300">
                                                                            {{ $customer->nama }}
                                                                        </span>
                                                                    </label>
                                                                </div>
                                                            </td>
                                                            <td class="w-1/5">
                                                                <div>
                                                                    <input type="number" min="0"
                                                                        name="urutans[]"
                                                                        @if ($i < count($datas)) @if ($datas[$i]->customer_id == $customer->id)
                                                                            value="{{ $datas[$i]->urutan }}" @endif
                                                                        @endif
                                                                    class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border bg-primary-20 border-primary-100 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300 disabled:bg-primary-50 disabled:dark:bg-primary-800 disabled:text-gray-900 disabled:border-primary-100 disabled:dark:border-primary-800"
                                                                    />
                                                                </div>
                                                            </td>
                                                        </tr>
                                                        @php
                                                            if ($i < count($datas)) {
                                                                if ($datas[$i]->customer_id == $customer->id) {
                                                                    $i++;
                                                                }
                                                            }
                                                        @endphp
                                                    @endforeach
                                                </table>
                                            </div>
                                        </x-text-span>
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="w-auto">
                                            <label
                                                class="cursor-pointer flex flex-col items-center md:flex-row md:gap-2">
                                                <input type="checkbox" id="isactive" name="isactive"
                                                    class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md"
                                                    {{ $datas[0]->isactive == 1 ? 'checked' : '' }}>
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
                                        <x-anchor-secondary href="{{ route('area-officer.index') }}" tabindex="6">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
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
