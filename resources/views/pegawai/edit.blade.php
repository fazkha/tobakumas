@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@section('title', __('messages.employee'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('employee.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="size-7" viewBox="0 0 32 32" version="1.1"
                    xmlns="http://www.w3.org/2000/svg">
                    <title>users</title>
                    <path
                        d="M16 21.416c-5.035 0.022-9.243 3.537-10.326 8.247l-0.014 0.072c-0.018 0.080-0.029 0.172-0.029 0.266 0 0.69 0.56 1.25 1.25 1.25 0.596 0 1.095-0.418 1.22-0.976l0.002-0.008c0.825-3.658 4.047-6.35 7.897-6.35s7.073 2.692 7.887 6.297l0.010 0.054c0.127 0.566 0.625 0.982 1.221 0.982 0.69 0 1.25-0.559 1.25-1.25 0-0.095-0.011-0.187-0.031-0.276l0.002 0.008c-1.098-4.78-5.305-8.295-10.337-8.316h-0.002zM9.164 11.102c0 0 0 0 0 0 2.858 0 5.176-2.317 5.176-5.176s-2.317-5.176-5.176-5.176c-2.858 0-5.176 2.317-5.176 5.176v0c0.004 2.857 2.319 5.172 5.175 5.176h0zM9.164 3.25c0 0 0 0 0 0 1.478 0 2.676 1.198 2.676 2.676s-1.198 2.676-2.676 2.676c-1.478 0-2.676-1.198-2.676-2.676v0c0.002-1.477 1.199-2.674 2.676-2.676h0zM22.926 11.102c2.858 0 5.176-2.317 5.176-5.176s-2.317-5.176-5.176-5.176c-2.858 0-5.176 2.317-5.176 5.176v0c0.004 2.857 2.319 5.172 5.175 5.176h0zM22.926 3.25c1.478 0 2.676 1.198 2.676 2.676s-1.198 2.676-2.676 2.676c-1.478 0-2.676-1.198-2.676-2.676v0c0.002-1.477 1.199-2.674 2.676-2.676h0zM31.311 19.734c-0.864-4.111-4.46-7.154-8.767-7.154-0.395 0-0.784 0.026-1.165 0.075l0.045-0.005c-0.93-2.116-3.007-3.568-5.424-3.568-2.414 0-4.49 1.448-5.407 3.524l-0.015 0.038c-0.266-0.034-0.58-0.057-0.898-0.063l-0.009-0c-4.33 0.019-7.948 3.041-8.881 7.090l-0.012 0.062c-0.018 0.080-0.029 0.173-0.029 0.268 0 0.691 0.56 1.251 1.251 1.251 0.596 0 1.094-0.417 1.22-0.975l0.002-0.008c0.684-2.981 3.309-5.174 6.448-5.186h0.001c0.144 0 0.282 0.020 0.423 0.029 0.056 3.218 2.679 5.805 5.905 5.805 3.224 0 5.845-2.584 5.905-5.794l0-0.006c0.171-0.013 0.339-0.035 0.514-0.035 3.14 0.012 5.765 2.204 6.442 5.14l0.009 0.045c0.126 0.567 0.625 0.984 1.221 0.984 0.69 0 1.249-0.559 1.249-1.249 0-0.094-0.010-0.186-0.030-0.274l0.002 0.008zM16 18.416c-0 0-0 0-0.001 0-1.887 0-3.417-1.53-3.417-3.417s1.53-3.417 3.417-3.417c1.887 0 3.417 1.53 3.417 3.417 0 0 0 0 0 0.001v-0c-0.003 1.886-1.53 3.413-3.416 3.416h-0z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.humanresource')</span>
                    <span>@lang('messages.employee')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <div class="py-2 flex flex-col">

        <div class="w-full px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('pegawai.partials.feedback')
                </div>

                <form id="master-form" action="{{ route('employee.update', Crypt::Encrypt($datas->id)) }}"
                    method="POST" enctype="multipart/form-data" class="w-full">
                    @csrf
                    @method('PUT')

                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">

                                    <div class="w-auto pb-4">
                                        <label for="nik"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.nik')</label>
                                        <x-text-input type="text" name="nik" id="nik" tabindex="1"
                                            autofocus required
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.nik') }}"
                                            value="{{ old('nik', $datas->nik) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('nik')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="nama_lengkap"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.fullname')</label>
                                        <x-text-input type="text" name="nama_lengkap" id="nama_lengkap"
                                            tabindex="2" required
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.fullname') }}"
                                            value="{{ old('nama_lengkap', $datas->nama_lengkap) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('nama_lengkap')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="nama_panggilan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.shortname')</label>
                                        <x-text-input type="text" name="nama_panggilan" id="nama_panggilan"
                                            tabindex="3"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.shortname') }}"
                                            value="{{ old('nama_panggilan', $datas->nama_panggilan) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('nama_panggilan')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="tempat_lahir"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.birthplace')</label>
                                        <x-text-input type="text" name="tempat_lahir" id="tempat_lahir"
                                            tabindex="4"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.birthplace') }}"
                                            value="{{ old('tempat_lahir', $datas->tempat_lahir) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('tempat_lahir')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="tanggal_lahir"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.birthdate')</label>
                                        <x-text-input type="date" name="tanggal_lahir" id="tanggal_lahir"
                                            data-date-format="dd-mm-yyyy" tabindex="5"
                                            value="{{ old('tanggal_lahir', $datas->tanggal_lahir) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('tanggal_lahir')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="alamat_asal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.originaddress')</label>
                                        <x-text-input type="text" name="alamat_asal" id="alamat_asal" tabindex="6"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.originaddress') }}"
                                            value="{{ old('alamat_asal', $datas->alamat_asal) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('alamat_asal')" />
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <label for="nip"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.nip')</label>
                                        <x-text-input type="text" name="nip" id="nip" tabindex="7"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.nip') }}"
                                            value="{{ old('nip', $datas->nip) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('nip')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="alamat_tinggal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.residentialaddress')</label>
                                        <x-text-input type="text" name="alamat_tinggal" id="alamat_tinggal"
                                            tabindex="8" required
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.residentialaddress') }}"
                                            value="{{ old('alamat_tinggal', $datas->alamat_tinggal) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('alamat_tinggal')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="telpon"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.phonenumber')</label>
                                        <x-text-input type="text" name="telpon" id="telpon" tabindex="9"
                                            required
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.phonenumber') }}"
                                            value="{{ old('telpon', $datas->telpon) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('telpon')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <span
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.gender')</span>
                                        <x-text-span>
                                            <div class="flex flex-row gap-6">
                                                <label class="relative flex items-center cursor-pointer">
                                                    <input {{ $datas->kelamin == 'L' ? 'checked' : '' }}
                                                        class="sr-only peer" name="kelamin" id="kelamin-laki"
                                                        type="radio" value="L" />
                                                    <div
                                                        class="w-5 h-5 bg-transparent border-2 border-blue-500 rounded-full peer-checked:bg-blue-500 peer-checked:border-blue-500 peer-hover:shadow-lg peer-hover:shadow-blue-500/50 peer-checked:shadow-lg peer-checked:shadow-blue-500/50 transition duration-300 ease-in-out">
                                                    </div>
                                                    <label for="kelamin-laki"
                                                        class="ml-2">@lang('messages.genderman')</label>
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

                                    <div class="w-auto pb-4">
                                        <label for="email"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.emailaddress')</label>
                                        <x-text-input type="text" name="email" id="email" tabindex="10"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.emailaddress') }}"
                                            value="{{ old('email', $datas->email) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('email')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                        <x-text-input type="text" name="keterangan" id="keterangan"
                                            tabindex="11"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.description') }}"
                                            value="{{ old('keterangan', $datas->keterangan) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                                    </div>
                                </div>
                            </div>

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">
                                    <h1
                                        class="text-xl font-bold leading-tight pb-2 mb-4 border-b-2 border-primary-100 dark:border-primary-800">
                                        @lang('messages.penggajian')</h1>
                                    <div class="w-auto pb-4">
                                        <label for="gaji_pokok"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.gapok')
                                            (@lang('messages.currencysymbol'))</label>
                                        <x-text-input type="number" min="0" name="gaji_pokok"
                                            id="gaji_pokok" tabindex="12"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.gapok') }}"
                                            value="{{ old('gaji_pokok', $penggajian ? $penggajian->gaji_pokok : null) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('gaji_pokok')" />
                                    </div>

                                    <div class="flex flex-row gap-2">
                                        <div class="w-2/3 pb-4">
                                            <label for="t1_keterangan"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.tunjangan')</label>
                                            <x-text-input type="text" name="t1_keterangan" id="t1_keterangan"
                                                tabindex="13"
                                                placeholder="{{ __('messages.enter') }} {{ __('messages.tunjangan') }}"
                                                value="{{ old('t1_keterangan', $penggajian ? $penggajian->t1_keterangan : null) }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('t1_keterangan')" />
                                        </div>

                                        <div class="w-1/3 pb-4">
                                            <label for="t1_gaji"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Rp.</label>
                                            <x-text-input type="number" min="0" name="t1_gaji"
                                                id="t1_gaji" tabindex="14"
                                                placeholder="{{ __('messages.enter') }} {{ __('messages.tunjangan') }}"
                                                value="{{ old('t1_gaji', $penggajian ? $penggajian->t1_gaji : null) }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('t1_gaji')" />
                                        </div>
                                    </div>

                                    <div class="flex flex-row gap-2">
                                        <div class="w-2/3 pb-4">
                                            <label for="t2_keterangan"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.tunjangan')</label>
                                            <x-text-input type="text" name="t2_keterangan" id="t2_keterangan"
                                                tabindex="15"
                                                placeholder="{{ __('messages.enter') }} {{ __('messages.tunjangan') }}"
                                                value="{{ old('t2_keterangan', $penggajian ? $penggajian->t2_keterangan : null) }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('t2_keterangan')" />
                                        </div>

                                        <div class="w-1/3 pb-4">
                                            <label for="t2_gaji"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Rp.</label>
                                            <x-text-input type="number" min="0" name="t2_gaji"
                                                id="t2_gaji" tabindex="16"
                                                placeholder="{{ __('messages.enter') }} {{ __('messages.tunjangan') }}"
                                                value="{{ old('t2_gaji', $penggajian ? $penggajian->t2_gaji : null) }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('t2_gaji')" />
                                        </div>
                                    </div>

                                    <div class="flex flex-row gap-2">
                                        <div class="w-2/3 pb-4">
                                            <label for="t3_keterangan"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.tunjangan')</label>
                                            <x-text-input type="text" name="t3_keterangan" id="t3_keterangan"
                                                tabindex="17"
                                                placeholder="{{ __('messages.enter') }} {{ __('messages.tunjangan') }}"
                                                value="{{ old('t3_keterangan', $penggajian ? $penggajian->t3_keterangan : null) }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('t3_keterangan')" />
                                        </div>

                                        <div class="w-1/3 pb-4">
                                            <label for="t3_gaji"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Rp.</label>
                                            <x-text-input type="number" min="0" name="t3_gaji"
                                                id="t3_gaji" tabindex="18"
                                                placeholder="{{ __('messages.enter') }} {{ __('messages.tunjangan') }}"
                                                value="{{ old('t3_gaji', $penggajian ? $penggajian->t3_gaji : null) }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('t3_gaji')" />
                                        </div>
                                    </div>

                                </div>

                                <div class="w-full lg:w-1/2 px-2">
                                    <h1
                                        class="text-xl font-bold leading-tight pb-2 mb-4 border-b-2 border-primary-100 dark:border-primary-800">
                                        @lang('messages.rekening')</h1>
                                    <div class="w-auto pb-4">
                                        <label for="rek_nama_bank"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.bankname')</label>
                                        <x-text-input type="text" name="rek_nama_bank" id="rek_nama_bank"
                                            tabindex="19"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.bankname') }}"
                                            value="{{ old('rek_nama_bank', $penggajian ? $penggajian->rek_nama_bank : null) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('rek_nama_bank')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="rek_nomor"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.bankaccountnumber')</label>
                                        <x-text-input type="text" name="rek_nomor" id="rek_nomor" tabindex="20"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.bankaccountnumber') }}"
                                            value="{{ old('rek_nomor', $penggajian ? $penggajian->rek_nomor : null) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('rek_nomor')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="rek_nama_pemilik"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.bankaccountname')</label>
                                        <x-text-input type="text" name="rek_nama_pemilik" id="rek_nama_pemilik"
                                            tabindex="21"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.bankaccountname') }}"
                                            value="{{ old('rek_nama_pemilik', $penggajian ? $penggajian->rek_nama_pemilik : null) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('rek_nama_pemilik')" />
                                    </div>
                                </div>
                            </div>

                            <div class="pb-4 lg:pb-12">
                                <x-text-span>
                                    <h1
                                        class="text-xl font-bold leading-tight pb-2 mb-4 border-b-2 border-primary-100 dark:border-primary-800">
                                        @lang('messages.attachment')</h1>
                                    <div class="image-set">
                                        <div class="flex flex-row flex-wrap gap-4 md:gap-6 text-center justify-center">
                                            <div class="w-1/3 md:w-1/4 lg:w-1/6">
                                                <span
                                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">
                                                    <span>@lang('messages.picture')-1</span>
                                                    <a data-title="{{ __('messages.picture') }}-1"
                                                        href="{{ $datas->gambar_1_nama ? asset($datas->gambar_1_lokasi . '/' . $datas->gambar_1_nama) : url('/images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}">
                                                        <img id="image_1-preview"
                                                            src="{{ $datas->gambar_1_nama ? asset($datas->gambar_1_lokasi . '/' . $datas->gambar_1_nama) : url('/images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}"
                                                            alt="o.o">
                                                    </a>
                                                </span>
                                                <label for="gambar_1_nama"
                                                    class="cursor-pointer">📁&nbsp;Select&nbsp;file...</label>
                                                <input type="file" id="gambar_1_nama" name="gambar_1_nama"
                                                    style="display: none;" />
                                            </div>

                                            <div class="w-1/3 md:w-1/4 lg:w-1/6">
                                                <span
                                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">
                                                    <span>@lang('messages.picture')-2</span>
                                                    <a data-title="{{ __('messages.picture') }}-2"
                                                        href="{{ $datas->gambar_2_nama ? asset($datas->gambar_2_lokasi . '/' . $datas->gambar_2_nama) : url('/images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}">
                                                        <img id="image_2-preview"
                                                            src="{{ $datas->gambar_2_nama ? asset($datas->gambar_2_lokasi . '/' . $datas->gambar_2_nama) : url('/images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}"
                                                            alt="o.o">
                                                    </a>
                                                </span>
                                                <label for="gambar_2_nama"
                                                    class="cursor-pointer">📁&nbsp;Select&nbsp;file...</label>
                                                <input type="file" id="gambar_2_nama" name="gambar_2_nama"
                                                    style="display: none;" />
                                            </div>

                                            <div class="w-1/3 md:w-1/4 lg:w-1/6">
                                                <span
                                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">
                                                    <span>@lang('messages.picture')-3</span>
                                                    <a data-title="{{ __('messages.picture') }}-3"
                                                        href="{{ $datas->gambar_3_nama ? asset($datas->gambar_3_lokasi . '/' . $datas->gambar_3_nama) : url('/images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}">
                                                        <img id="image_3-preview"
                                                            src="{{ $datas->gambar_3_nama ? asset($datas->gambar_3_lokasi . '/' . $datas->gambar_3_nama) : url('/images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}"
                                                            alt="o.o">
                                                    </a>
                                                </span>
                                                <label for="gambar_3_nama"
                                                    class="cursor-pointer">📁&nbsp;Select&nbsp;file...</label>
                                                <input type="file" id="gambar_3_nama" name="gambar_3_nama"
                                                    style="display: none;" />
                                            </div>

                                            <div class="w-1/3 md:w-1/4 lg:w-1/6">
                                                <span
                                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">
                                                    <span>@lang('messages.picture')-4</span>
                                                    <a data-title="{{ __('messages.picture') }}-4"
                                                        href="{{ $datas->gambar_4_nama ? asset($datas->gambar_4_lokasi . '/' . $datas->gambar_4_nama) : url('/images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}">
                                                        <img id="image_4-preview"
                                                            src="{{ $datas->gambar_4_nama ? asset($datas->gambar_4_lokasi . '/' . $datas->gambar_4_nama) : url('/images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}"
                                                            alt="o.o">
                                                    </a>
                                                </span>
                                                <label for="gambar_4_nama"
                                                    class="cursor-pointer">📁&nbsp;Select&nbsp;file...</label>
                                                <input type="file" id="gambar_4_nama" name="gambar_4_nama"
                                                    style="display: none;" />
                                            </div>

                                            <div class="w-1/3 md:w-1/4 lg:w-1/6">
                                                <span
                                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">
                                                    <span>@lang('messages.picture')-5</span>
                                                    <a data-title="{{ __('messages.picture') }}-5"
                                                        href="{{ $datas->gambar_5_nama ? asset($datas->gambar_5_lokasi . '/' . $datas->gambar_5_nama) : url('/images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}">
                                                        <img id="image_5-preview"
                                                            src="{{ $datas->gambar_5_nama ? asset($datas->gambar_5_lokasi . '/' . $datas->gambar_5_nama) : url('/images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}"
                                                            alt="o.o">
                                                    </a>
                                                </span>
                                                <label for="gambar_5_nama"
                                                    class="cursor-pointer">📁&nbsp;Select&nbsp;file...</label>
                                                <input type="file" id="gambar_5_nama" name="gambar_5_nama"
                                                    style="display: none;" />
                                            </div>
                                        </div>
                                    </div>
                                </x-text-span>
                            </div>

                            <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                <div class="w-auto">
                                    <label class="cursor-pointer flex flex-col items-center md:flex-row md:gap-2">
                                        <input type="checkbox" id="isactive" name="isactive" tabindex="22"
                                            class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md"
                                            {{ $datas->isactive == '1' ? 'checked' : '' }}>
                                        <span
                                            class="pr-4 group-hover:text-blue-500 transition-colors duration-300 text-right w-1/2 md:w-full">
                                            @lang('messages.active')
                                        </span>
                                    </label>
                                </div>

                                <x-primary-button type="submit" class="block" tabindex="23">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                    </svg>
                                    <span class="pl-1">@lang('messages.save')</span>
                                </x-primary-button>
                                <x-anchor-secondary href="{{ route('employee.index') }}" tabindex="24">
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
                </form>
            </div>
        </div>

        <div class="flex flex-col lg:flex-row gap-4 px-4 py-2">
            <div class="w-full">
                <div class="flex flex-col items-center">

                    <form id="jabatan-form" method="POST" enctype="multipart/form-data" class="w-full">
                        @csrf

                        {{-- Jabatan --}}
                        <div
                            class="w-full shadow-lg rounded-md border bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                            <div class="p-4 space-y-2">
                                <div class="flex flex-row items-center gap-2">
                                    <svg fill="currentColor" class="size-5" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21.435,11.5h-.38V8.12a1.626,1.626,0,0,0-1.62-1.62h-.63V6.12a1.625,1.625,0,0,0-3.25,0V11.5H8.445V6.12a1.625,1.625,0,0,0-3.25,0V6.5h-.63a1.62,1.62,0,0,0-1.62,1.62V11.5h-.38a.5.5,0,1,0,0,1h.38v3.37a1.622,1.622,0,0,0,1.62,1.63H5.2v.37a1.625,1.625,0,1,0,3.25,0V12.5h7.11v5.37a1.625,1.625,0,1,0,3.25,0V17.5h.63a1.628,1.628,0,0,0,1.62-1.63V12.5h.38a.5.5,0,1,0,0-1ZM5.2,16.5h-.63a.625.625,0,0,1-.62-.63V8.12a.623.623,0,0,1,.62-.62H5.2Zm2.25,1.37a.634.634,0,0,1-.63.63.625.625,0,0,1-.62-.63V6.12a.623.623,0,0,1,.62-.62.632.632,0,0,1,.63.62Zm10.36,0a.625.625,0,1,1-1.25,0V6.12a.625.625,0,0,1,1.25,0Zm2.25-2a.625.625,0,0,1-.62.63h-.63v-9h.63a.623.623,0,0,1,.62.62Z" />
                                    </svg>
                                    <span class="block font-medium text-primary-600 dark:text-primary-500">
                                        @lang('messages.jobposition')
                                    </span>
                                </div>

                                <div
                                    class="border rounded-md border-primary-100 bg-primary-100 dark:border-primary-800 dark:bg-primary-850">
                                    <div class="p-2 overflow-scroll md:overflow-auto lg:overflow-hidden">
                                        <table id="jabatan_table" class="w-full border-separate border-spacing-2">
                                            <thead>
                                                <tr>
                                                    <th class="w-1/3">@lang('messages.jobposition')</th>
                                                    <th class="w-1/12">@lang('messages.startdate')</th>
                                                    <th class="w-1/12">@lang('messages.enddate')</th>
                                                    <th class="w-auto">@lang('messages.description')</th>
                                                    <th class="w-auto">@lang('messages.status')</th>
                                                </tr>
                                            </thead>

                                            <tbody id="jabatanBody">
                                                @include('pegawai.partials.details', [
                                                    $details,
                                                    'viewMode' => false,
                                                ])
                                            </tbody>

                                            <tbody>
                                                <tr>
                                                    <td>
                                                        <input type="hidden" id="pegawai_id" name="pegawai_id"
                                                            value="{{ $datas->id }}" />
                                                        <select name="brandivjab_id" id="brandivjab_id"
                                                            tabindex="25" required
                                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-600 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                            <option value="">@lang('messages.choose')...
                                                            </option>
                                                            @foreach ($brandivjabs as $brandivjab)
                                                                <option value="{{ $brandivjab->id }}"
                                                                    {{ old('brandivjab_id') == $brandivjab->id ? 'selected' : '' }}>
                                                                    {{ $brandivjab->jabatan->nama . ($brandivjab->keterangan ? ' ' . $brandivjab->keterangan : '') . ($brandivjab->division_id ? ' ' . $brandivjab->division->nama : '') . ' - ' . $brandivjab->branch->nama . ' (' . $brandivjab->branch->kode . ')' }}
                                                                </option>
                                                            @endforeach
                                                        </select>

                                                        <x-input-error class="mt-2" :messages="$errors->get('brandivjab_id')" />
                                                    </td>
                                                    <td>
                                                        <x-text-input type="date" id="tanggal_mulai"
                                                            name="tanggal_mulai" tabindex="26" />
                                                    </td>
                                                    <td>
                                                        <x-text-input type="date" id="tanggal_akhir"
                                                            name="tanggal_akhir" tabindex="27" />
                                                    </td>
                                                    <td>
                                                        <x-text-input type="text" id="keterangan"
                                                            name="keterangan" tabindex="28" />
                                                    </td>
                                                    <td class="text-center">
                                                        <div class="w-auto">
                                                            <select name="isactive" id="isactive" tabindex="29"
                                                                class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-600 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                                <option value="1" selected>@lang('messages.active')
                                                                </option>
                                                                <option value="2">@lang('messages.relocate')</option>
                                                                <option value="3">@lang('messages.resign')</option>
                                                                <option value="9">@lang('messages.fired')</option>
                                                            </select>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>

                                    <div class="mt-4 mb-4 mr-4 flex flex-row flex-wrap justify-end gap-2 md:gap-4">
                                        <x-primary-button id="submit-detail" tabindex="30">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('employee.index') }}" tabindex="31">
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
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
        <link rel="stylesheet" href="{{ url('/') }}/css/photoviewer.min.css">
    @endpush

    @push('scripts')
        <script type="text/javascript" src="{{ url('/') }}/js/photoviewer.min.js"></script>
        <script type="text/javascript">
            $(document).ready(function(e) {
                $('.image-set a').click(function(e) {
                    e.preventDefault();
                    var this_title = $(this).attr('data-title');
                    var this_index = 0;
                    var counter = 0;

                    var items = $('.image-set a').get().map(function(el) {
                        if ($(el).attr('data-title') == this_title) {
                            this_index = counter;
                        }
                        counter = counter + 1;
                        return {
                            src: $(el).attr('href'),
                            title: $(el).attr('data-title')
                        }
                    });

                    var options = {
                        index: this_index,
                        positionFixed: false
                    };
                    // index: $(this).index(),

                    new PhotoViewer(items, options);

                });

                function getInitialFormValues(formId) {
                    const form = document.getElementById(formId);
                    const initialValues = {};
                    for (let i = 0; i < form.elements.length; i++) {
                        const element = form.elements[i];
                        if (element.name) {
                            if (element.type === 'checkbox' || element.type === 'radio') {
                                initialValues[element.name] = element.checked;
                            } else {
                                initialValues[element.name] = element.value;
                            }
                        }
                    }
                    return initialValues;
                }

                function isFormDirty(formId, initialValues) {
                    const form = document.getElementById(formId);
                    for (let i = 0; i < form.elements.length; i++) {
                        const element = form.elements[i];
                        if (element.name) {
                            let currentValue;
                            if (element.type === 'checkbox' || element.type === 'radio') {
                                currentValue = element.checked;
                            } else {
                                currentValue = element.value;
                            }

                            if (initialValues[element.name] !== currentValue) {
                                return true;
                            }
                        }
                    }
                    return false;
                }

                const myFormInitialValues = getInitialFormValues('master-form');

                deleteDetail = function(detailId) {
                    let idname = '#a-delete-detail-' + detailId;

                    var confirmation = confirm("Are you sure you want to delete this?");
                    if (confirmation) {
                        // $(idname).closest("tr").remove();
                        $.ajax({
                            url: '{{ url('/human-resource/employee/delete-jabatan') }}' + '/' + detailId,
                            type: 'delete',
                            dataType: 'json',
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(result) {
                                if (result.status !== 'Not Found') {
                                    $('#jabatanBody').html(result.view);
                                    flasher.error("{{ __('messages.successdeleted') }}!", "Success");
                                }
                                $('#jabatan-form')[0].reset();
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                };

                $("#submit-detail").on("click", function(e) {
                    e.preventDefault();
                    let key = $('#pegawai_id').val();

                    $.ajax({
                        url: '{{ url('/human-resource/employee/store-jabatan') }}' + '/' + key,
                        type: 'post',
                        dataType: 'json',
                        data: $('form#jabatan-form').serialize(),
                        success: function(result) {
                            if (result.status !== 'Not Found') {
                                $('#jabatanBody').html(result.view);
                                $('#jabatan-form')[0].reset();
                                flasher.success("{{ __('messages.successsaved') }}!", "Success");
                            }
                        }
                    });

                    // if (isFormDirty('master-form', myFormInitialValues)) {
                    //     $('form#master-form').submit();
                    // }
                });

                $('#gambar_1_nama').change(function() {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $('#image_1-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                });
                $('#gambar_2_nama').change(function() {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $('#image_2-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                });
                $('#gambar_3_nama').change(function() {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $('#image_3-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                });
                $('#gambar_4_nama').change(function() {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $('#image_4-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                });
                $('#gambar_5_nama').change(function() {
                    let reader = new FileReader();
                    reader.onload = (e) => {
                        $('#image_5-preview').attr('src', e.target.result);
                    }
                    reader.readAsDataURL(this.files[0]);
                });
            });
        </script>
    @endpush
</x-app-layout>
