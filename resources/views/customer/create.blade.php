@section('title', __('messages.customer'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('customer.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="w-7 h-7" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M106.544 501.695l385.403-380.262c11.913-11.754 31.079-11.722 42.955.075l382.71 380.14c8.025 7.971 20.992 7.927 28.963-.098s7.927-20.992-.098-28.963l-382.71-380.14c-27.811-27.625-72.687-27.7-100.589-.171L77.775 472.539c-8.051 7.944-8.139 20.911-.194 28.962s20.911 8.139 28.962.194z" />
                    <path
                        d="M783.464 362.551v517.12c0 16.962-13.758 30.72-30.72 30.72h-481.28c-16.962 0-30.72-13.758-30.72-30.72v-517.12c0-11.311-9.169-20.48-20.48-20.48s-20.48 9.169-20.48 20.48v517.12c0 39.583 32.097 71.68 71.68 71.68h481.28c39.583 0 71.68-32.097 71.68-71.68v-517.12c0-11.311-9.169-20.48-20.48-20.48s-20.48 9.169-20.48 20.48z" />
                    <path
                        d="M551.175 473.257l-27.341 53.8c-5.124 10.083-1.104 22.412 8.979 27.536s22.412 1.104 27.536-8.979l28.549-56.177c14.571-28.693-2.885-57.14-35.061-57.14h-83.466c-32.176 0-49.632 28.447-35.064 57.135l28.552 56.182c5.124 10.083 17.453 14.103 27.536 8.979s14.103-17.453 8.979-27.536l-27.341-53.8h78.143z" />
                    <path
                        d="M594.039 777.562c38.726 0 70.124-31.395 70.124-70.124 0-80.871-66.26-147.128-147.139-147.128h-9.841c-80.879 0-147.139 66.257-147.139 147.128 0 38.728 31.398 70.124 70.124 70.124h163.871zm0 40.96H430.168c-61.347 0-111.084-49.733-111.084-111.084 0-103.493 84.599-188.088 188.099-188.088h9.841c103.5 0 188.099 84.595 188.099 188.088 0 61.35-49.737 111.084-111.084 111.084z" />
                </svg>
                <span class="px-2">@lang('messages.customer')</span>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.new')</span>
        </h1>
    </div>

    <form action="{{ route('customer.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('customer.partials.feedback')
                    </div>

                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">

                                    <div class="w-auto pb-4">
                                        <label for="branch_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.branch')</label>
                                        <input type="hidden" name="branch_id" value="{{ $branch_id }}" />
                                        <x-text-span>{{ $branch->nama }}</x-text-span>
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="customer_group_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.customergroup')</label>
                                        <select name="customer_group_id" id="customer_group_id" tabindex="1" autofocus
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($groups as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('customer_group_id') === $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('customer_group_id')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="kode"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.customercode')</label>
                                        <x-text-input type="text" name="kode" id="kode" tabindex="2"
                                            autofocus
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.customercode') }}"
                                            required value="{{ old('kode') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('kode')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="nama"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.customername')</label>
                                        <x-text-input type="text" name="nama" id="nama" tabindex="3"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.customername') }}"
                                            required value="{{ old('nama') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="alamat"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.customeraddress')</label>
                                        <x-text-input type="text" name="alamat" id="alamat" tabindex="4"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.customeraddress') }}"
                                            required value="{{ old('alamat') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <label for="tanggal_gabung"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.joindate')</label>
                                        <x-text-input type="date" name="tanggal_gabung" id="tanggal_gabung"
                                            tabindex="5" value="{{ date('Y-m-d') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('tanggal_gabung')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="kontak_nama"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.contactname')</label>
                                        <x-text-input type="text" name="kontak_nama" id="kontak_nama" tabindex="6"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.contactname') }}"
                                            value="{{ old('kontak_nama') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('kontak_nama')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="kontak_telpon"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.contactphone')</label>
                                        <x-text-input type="text" name="kontak_telpon" id="kontak_telpon"
                                            tabindex="7"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.contactphone') }}"
                                            value="{{ old('kontak_telpon') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('kontak_telpon')" />
                                    </div>

                                    <div class="w-auto pb-4 lg:pb-12">
                                        <label for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                        <x-text-input type="text" name="keterangan" id="keterangan"
                                            tabindex="8"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.description') }}"
                                            value="{{ old('keterangan') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="dark:bg-black/10">
                                            <label class="cursor-pointer flex flex-col md:flex-row md:gap-2">
                                                <input type="checkbox" id="isactive" name="isactive" tabindex="9"
                                                    class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7"
                                                    checked>
                                                <span
                                                    class="pr-4 group-hover:text-blue-500 transition-colors duration-300">
                                                    @lang('messages.active')
                                                </span>
                                            </label>
                                        </div>

                                        <x-primary-button type="submit" class="block" tabindex="10">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('customer.index') }}" tabindex="11">
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
