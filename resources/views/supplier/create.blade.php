@section('title', __('messages.supplier'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('supplier.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="w-7 h-7" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M22,7.82a1.25,1.25,0,0,0,0-.19v0h0l-2-5A1,1,0,0,0,19,2H5a1,1,0,0,0-.93.63l-2,5h0v0a1.25,1.25,0,0,0,0,.19A.58.58,0,0,0,2,8H2V8a4,4,0,0,0,2,3.4V21a1,1,0,0,0,1,1H19a1,1,0,0,0,1-1V11.44A4,4,0,0,0,22,8V8h0A.58.58,0,0,0,22,7.82ZM13,20H11V16h2Zm5,0H15V15a1,1,0,0,0-1-1H10a1,1,0,0,0-1,1v5H6V12a4,4,0,0,0,3-1.38,4,4,0,0,0,6,0A4,4,0,0,0,18,12Zm0-10a2,2,0,0,1-2-2,1,1,0,0,0-2,0,2,2,0,0,1-4,0A1,1,0,0,0,8,8a2,2,0,0,1-4,.15L5.68,4H18.32L20,8.15A2,2,0,0,1,18,10Z" />
                </svg>
                <span class="px-2">@lang('messages.supplier')</span>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.new')</span>
        </h1>
    </div>

    <form action="{{ route('supplier.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('supplier.partials.feedback')
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
                                        <label for="kode"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.code')</label>
                                        <x-text-input type="text" name="kode" id="kode" tabindex="1"
                                            autofocus
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.suppliercode') }}"
                                            required value="{{ old('kode') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('kode')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="nama"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.name')</label>
                                        <x-text-input type="text" name="nama" id="nama" tabindex="2"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.suppliername') }}"
                                            required value="{{ old('nama') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="alamat"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.address')</label>
                                        <x-text-input type="text" name="alamat" id="alamat" tabindex="3"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.supplieraddress') }}"
                                            required value="{{ old('alamat') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('alamat')" />
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <label for="tanggal_gabung"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.joindate')</label>
                                        <x-text-input type="date" name="tanggal_gabung" id="tanggal_gabung"
                                            tabindex="4" required value="{{ date('Y-m-d') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('tanggal_gabung')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="kontak_nama"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.contactname')</label>
                                        <x-text-input type="text" name="kontak_nama" id="kontak_nama" tabindex="5"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.contactname') }}"
                                            value="{{ old('kontak_nama') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('kontak_nama')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="kontak_telpon"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.contactphone')</label>
                                        <x-text-input type="text" name="kontak_telpon" id="kontak_telpon"
                                            tabindex="6"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.contactphone') }}"
                                            value="{{ old('kontak_telpon') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('kontak_telpon')" />
                                    </div>

                                    <div class="w-auto pb-4 lg:pb-12">
                                        <label for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                        <x-text-input type="text" name="keterangan" id="keterangan" tabindex="7"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.description') }}"
                                            value="{{ old('keterangan') }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="dark:bg-black/10">
                                            <label class="cursor-pointer flex flex-col md:flex-row md:gap-2">
                                                <input type="checkbox" id="isactive" name="isactive" tabindex="8"
                                                    class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7"
                                                    checked>
                                                <span
                                                    class="pl-2 pr-4 group-hover:text-blue-500 transition-colors duration-300">
                                                    @lang('messages.active')
                                                </span>
                                            </label>
                                        </div>

                                        <x-primary-button type="submit" class="block" tabindex="9">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('supplier.index') }}" tabindex="10">
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
