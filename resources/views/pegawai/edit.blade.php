@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@section('title', __('messages.employee'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('employee.index') }}" class="flex items-center justify-center">
                <svg class="w-7 h-7" viewBox="0 0 20 20" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M4.2359 10.0547C3.69336 10.8685 3.40385 11.8247 3.40385 12.8028L3.40385 13.5C3.40385 14.0522 2.95614 14.5 2.40385 14.5C1.85157 14.5 1.40385 14.0522 1.40385 13.5L1.40385 12.8028C1.40385 11.4299 1.81023 10.0877 2.5718 8.94531L4.2359 10.0547Z"
                        fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.20663 9C5.41471 9 4.67519 9.39578 4.23591 10.0547L2.57181 8.9453C3.38202 7.72998 4.74601 7 6.20663 7H6.40386C6.95614 7 7.40386 7.44772 7.40386 8C7.40386 8.55228 6.95614 9 6.40386 9H6.20663Z"
                        fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M8.41797 10.0547C8.96051 10.8685 9.25002 11.8247 9.25002 12.8028L9.25002 13.5C9.25002 14.0522 9.69773 14.5 10.25 14.5C10.8023 14.5 11.25 14.0522 11.25 13.5L11.25 12.8028C11.25 11.4299 10.8436 10.0877 10.0821 8.94531L8.41797 10.0547Z"
                        fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.44724 9C7.23916 9 7.97868 9.39578 8.41796 10.0547L10.0821 8.9453C9.27185 7.72998 7.90786 7 6.44724 7H6.25001C5.69773 7 5.25001 7.44772 5.25001 8C5.25001 8.55228 5.69773 9 6.25001 9H6.44724Z"
                        fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M6.25 6.25C6.94036 6.25 7.5 5.69036 7.5 5C7.5 4.30964 6.94036 3.75 6.25 3.75C5.55964 3.75 5 4.30964 5 5C5 5.69036 5.55964 6.25 6.25 6.25ZM6.25 8.25C8.04493 8.25 9.5 6.79493 9.5 5C9.5 3.20507 8.04493 1.75 6.25 1.75C4.45507 1.75 3 3.20507 3 5C3 6.79493 4.45507 8.25 6.25 8.25Z"
                        fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M11.4859 13.3047C10.9434 14.1185 10.6538 15.0747 10.6539 16.0528L10.6539 16.75C10.6539 17.3022 10.2061 17.75 9.65385 17.75C9.10157 17.75 8.65385 17.3022 8.65385 16.75L8.65385 16.0528C8.65385 14.6799 9.06023 13.3377 9.8218 12.1953L11.4859 13.3047Z"
                        fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M13.4566 12.25C12.6647 12.25 11.9252 12.6458 11.4859 13.3047L9.82181 12.1953C10.632 10.98 11.996 10.25 13.4566 10.25H13.6539C14.2061 10.25 14.6539 10.6977 14.6539 11.25C14.6539 11.8023 14.2061 12.25 13.6539 12.25H13.4566Z"
                        fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M15.668 13.3047C16.2105 14.1185 16.5 15.0747 16.5 16.0528L16.5 16.75C16.5 17.3022 16.9477 17.75 17.5 17.75C18.0523 17.75 18.5 17.3022 18.5 16.75L18.5 16.0528C18.5 14.6799 18.0936 13.3377 17.3321 12.1953L15.668 13.3047Z"
                        fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M13.6972 12.25C14.4892 12.25 15.2287 12.6458 15.668 13.3047L17.3321 12.1953C16.5219 10.98 15.1579 10.25 13.6972 10.25H13.5C12.9477 10.25 12.5 10.6977 12.5 11.25C12.5 11.8023 12.9477 12.25 13.5 12.25H13.6972Z"
                        fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                        d="M13.5 9.5C14.1904 9.5 14.75 8.94036 14.75 8.25C14.75 7.55964 14.1904 7 13.5 7C12.8096 7 12.25 7.55964 12.25 8.25C12.25 8.94036 12.8096 9.5 13.5 9.5ZM13.5 11.5C15.2949 11.5 16.75 10.0449 16.75 8.25C16.75 6.45507 15.2949 5 13.5 5C11.7051 5 10.25 6.45507 10.25 8.25C10.25 10.0449 11.7051 11.5 13.5 11.5Z"
                        fill="currentColor" />
                </svg>
                <span class="px-2">@lang('messages.employee')</span>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <form action="{{ route('employee.update', Crypt::Encrypt($datas->id)) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('pegawai.partials.feedback')
                    </div>

                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">

                                    <div class="w-auto pb-4">
                                        <label for="branch_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.branch')</label>
                                        <x-text-span>{{ $datas->branch->nama }}</x-text-span>
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="nama_lengkap"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.fullname')</label>
                                        <x-text-input type="text" name="nama_lengkap" id="nama_lengkap"
                                            tabindex="1" autofocus
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.fullname') }}"
                                            required value="{{ old('nama_lengkap', $datas->nama_lengkap) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('nama_lengkap')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="alamat_tinggal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.residentialaddress')</label>
                                        <x-text-input type="text" name="alamat_tinggal" id="alamat_tinggal"
                                            tabindex="2"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.residentialaddress') }}"
                                            required value="{{ old('alamat_tinggal', $datas->alamat_tinggal) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('alamat_tinggal')" />
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <label for="telpon"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.phonenumber')</label>
                                        <x-text-input type="text" name="telpon" id="telpon" tabindex="3"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.phonenumber') }}"
                                            required value="{{ old('telpon', $datas->telpon) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('telpon')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.gender')</label>
                                        <x-text-span>
                                            <div class="flex flex-row gap-6">
                                                <label class="relative flex items-center cursor-pointer">
                                                    <input {{ $datas->kelamin == 'L' ? 'checked' : '' }}
                                                        class="sr-only peer" name="kelamin" id="kelamin-laki"
                                                        type="radio" value="L" />
                                                    <div
                                                        class="w-5 h-5 bg-transparent border-2 border-blue-500 rounded-full peer-checked:bg-blue-500 peer-checked:border-blue-500 peer-hover:shadow-lg peer-hover:shadow-blue-500/50 peer-checked:shadow-lg peer-checked:shadow-blue-500/50 transition duration-300 ease-in-out">
                                                    </div>
                                                    <label for="kelamin-laki" class="ml-2">@lang('messages.genderman')</label>
                                                </label>
                                                <label class="relative flex items-center cursor-pointer">
                                                    <input {{ $datas->kelamin == 'P' ? 'checked' : '' }}
                                                        class="sr-only peer" name="kelamin" id="kelamin-perempuan"
                                                        type="radio" value="P" />
                                                    <div
                                                        class="w-5 h-5 bg-transparent border-2 border-red-500 rounded-full peer-checked:bg-red-500 peer-checked:border-red-500 peer-hover:shadow-lg peer-hover:shadow-red-500/50 peer-checked:shadow-lg peer-checked:shadow-red-500/50 transition duration-300 ease-in-out">
                                                    </div>
                                                    <label for="kelamin-perempuan"
                                                        class="ml-2">@lang('messages.genderwoman')</label>
                                                </label>
                                            </div>
                                        </x-text-span>
                                    </div>

                                    <div class="w-auto pb-4 lg:pb-12">
                                        <label for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                        <x-text-input type="text" name="keterangan" id="keterangan"
                                            tabindex="4"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.description') }}"
                                            value="{{ old('keterangan', $datas->keterangan) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="dark:bg-black/10">
                                            <label class="cursor-pointer flex flex-col md:flex-row md:gap-2">
                                                <input type="checkbox" id="isactive" name="isactive"
                                                    class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7"
                                                    {{ $datas->isactive == '1' ? 'checked' : '' }}>
                                                <span
                                                    class="pr-4 group-hover:text-blue-500 transition-colors duration-300">
                                                    @lang('messages.active')
                                                </span>
                                            </label>
                                        </div>

                                        <x-primary-button type="submit" class="block" tabindex="6">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('employee.index') }}" tabindex="7">
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
