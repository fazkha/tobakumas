@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@section('title', __('messages.goods'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('goods.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="w-7 h-7" viewBox="0 0 52 52" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="m45.2 19.6a1.6 1.6 0 0 1 1.59 1.45v22.55a4.82 4.82 0 0 1 -4.59 4.8h-32.2a4.82 4.82 0 0 1 -4.8-4.59v-22.61a1.6 1.6 0 0 1 1.45-1.59h38.55zm-12.39 6.67-.11.08-9.16 9.93-4.15-4a1.2 1.2 0 0 0 -1.61-.08l-.1.08-1.68 1.52a1 1 0 0 0 -.09 1.44l.09.1 5.86 5.55a2.47 2.47 0 0 0 1.71.71 2.27 2.27 0 0 0 1.71-.71l4.9-5.16.39-.41.52-.55 5-5.3a1.25 1.25 0 0 0 .11-1.47l-.07-.09-1.72-1.54a1.19 1.19 0 0 0 -1.6-.1zm12.39-22.67a4.81 4.81 0 0 1 4.8 4.8v4.8a1.6 1.6 0 0 1 -1.6 1.6h-44.8a1.6 1.6 0 0 1 -1.6-1.6v-4.8a4.81 4.81 0 0 1 4.8-4.8z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.warehouse')</span>
                    <span>@lang('messages.goods')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <form id="barang-form" action="{{ route('goods.update', Crypt::Encrypt($datas->id)) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('barang.partials.feedback')
                    </div>

                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">

                                    <div class="w-auto pb-4">
                                        <label for="gudang_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.warehouse')</label>
                                        <select name="gudang_id" id="gudang_id" tabindex="1" autofocus
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($gudangs as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ $datas->gudang_id == $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('gudang_id')" />
                                    </div>

                                    <div class="flex flex-row justify-between gap-4">
                                        <div class="w-1/2 pb-4">
                                            <label for="jenis_barang_id"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.typeofdesignation')</label>
                                            <select name="jenis_barang_id" id="jenis_barang_id" tabindex="2"
                                                class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                <option value="">@lang('messages.choose')...</option>
                                                @foreach ($jenis_barangs as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $datas->jenis_barang_id == $id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('jenis_barang_id')" />
                                        </div>

                                        <div class="w-1/2 pb-4">
                                            <label for="subjenis_barang_id"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.typeofsubdesignation')</label>
                                            <select name="subjenis_barang_id" id="subjenis_barang_id" tabindex="3"
                                                class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                <option value="">@lang('messages.choose')...</option>
                                                @foreach ($subjenis_barangs as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $datas->subjenis_barang_id == $id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('subjenis_barang_id')" />
                                        </div>
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="nama"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.nameofgoods')</label>
                                        <x-text-input type="text" name="nama" id="nama" tabindex="4"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.name') }}"
                                            required value="{{ old('nama', $datas->nama) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('nama')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="merk"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.brand')</label>
                                        <x-text-input type="text" name="merk" id="merk" tabindex="5"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.branch') }}"
                                            required value="{{ old('merk', $datas->merk) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('merk')" />
                                    </div>

                                    <div class="flex flex-row justify-between gap-4">
                                        <div class="w-1/2 pb-4">
                                            <label for="harga_satuan"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.unitpricebuy')</label>
                                            <x-text-input type="text" name="harga_satuan" id="harga_satuan"
                                                tabindex="8"
                                                value="{{ old('harga_satuan', $datas->harga_satuan) }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('harga_satuan')" />
                                        </div>

                                        <div class="w-1/2 pb-4">
                                            <label for="satuan_beli_id"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.buyunit')</label>
                                            <select name="satuan_beli_id" id="satuan_beli_id" tabindex="6"
                                                class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                <option value="">@lang('messages.choose')...</option>
                                                @foreach ($satuans as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $datas->satuan_beli_id == $id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('satuan_beli_id')" />
                                        </div>
                                    </div>

                                    <div class="flex flex-row justify-between gap-4">
                                        <div class="w-1/2 pb-4">
                                            <label for="harga_satuan_jual"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.unitpricesell')</label>
                                            <x-text-input type="text" name="harga_satuan_jual"
                                                id="harga_satuan_jual" tabindex="9"
                                                value="{{ old('harga_satuan_jual', $datas->harga_satuan_jual) }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('harga_satuan_jual')" />
                                        </div>

                                        <div class="w-1/2 pb-4">
                                            <label for="satuan_jual_id"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.sellunit')</label>
                                            <select name="satuan_jual_id" id="satuan_jual_id" tabindex="7"
                                                class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                <option value="">@lang('messages.choose')...</option>
                                                @foreach ($satuans as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ $datas->satuan_jual_id == $id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('satuan_jual_id')" />
                                        </div>
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4">
                                        <label for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                        <x-text-input type="text" name="keterangan" id="keterangan"
                                            tabindex="10"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.description') }}"
                                            required value="{{ old('keterangan', $datas->keterangan) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                                    </div>

                                    <div class="w-auto pb-4 lg:pb-12">
                                        <label for="gambar"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.picture')</label>
                                        <x-text-input type="file" name="gambar" id="gambar" tabindex="11"
                                            accept=".jpg,.jpeg" placeholder="@lang('messages.choose')"
                                            class="!rounded-none border" />

                                        <x-input-error class="mt-2" :messages="$errors->get('gambar')" />

                                        <div class="mt-2 flex justify-center">
                                            <img id="image-preview" class="w-full lg:w-3/5 h-auto border rounded-lg"
                                                @if ($datas->gambar) src="{{ asset($datas->lokasi . '/' . $datas->gambar) }}" @else src="/images/0cd6be830e32f80192d496e50cfa9dbc.jpg" @endif
                                                alt="o.o" />
                                        </div>
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="dark:bg-black/10">
                                            <label class="cursor-pointer flex flex-col md:flex-row md:gap-2">
                                                <input type="checkbox" id="isactive" name="isactive"
                                                    class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md"
                                                    {{ $datas->isactive == '1' ? 'checked' : '' }}>
                                                <span
                                                    class="pr-4 group-hover:text-blue-500 transition-colors duration-300">
                                                    @lang('messages.active')
                                                </span>
                                            </label>
                                        </div>

                                        <x-primary-button type="submit" class="block" tabindex="13">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('goods.index') }}" tabindex="14">
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
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="{{ url('js/jquery.maskMoney.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function(e) {
                $(function() {
                    $('#harga_satuan').maskMoney({
                        prefix: 'Rp. ',
                        allowNegative: false,
                        thousands: '.',
                        decimal: ',',
                        precision: 0,
                        affixesStay: false
                    });

                    $('#gambar').change(function() {
                        let reader = new FileReader();
                        reader.onload = (e) => {
                            $('#image-preview').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(this.files[0]);
                    });
                })
            });
        </script>
    @endpush
</x-app-layout>
