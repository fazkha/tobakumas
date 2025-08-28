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
                <span class="px-2">@lang('messages.goods')</span>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.delete')</span>
        </h1>
    </div>

    <div class="py-2 flex flex-col">
        <div class="container mx-auto px-2 sm:px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-3/4 lg:w-1/2 shadow mb-5" role="alert">
                    <form action="{{ route('goods.destroy', Crypt::Encrypt($datas->id)) }}" class="block"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('DELETE')

                        <div class="flex">
                            <div class="bg-red-600 w-16 text-center p-2">
                                <div class="flex justify-center h-full items-center">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                                        viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                    </svg>
                                </div>
                            </div>
                            <div class="bg-white border-r-4 border-red-600 w-full p-4">
                                <div>
                                    <p class="text-gray-600 font-bold">@lang('messages.confirm')</p>
                                    <p class="text-gray-600 font-bold text-sm">@lang('messages.deleteitemwarning').</p>
                                    <p class="text-gray-600 text-sm mb-5">@lang('messages.deleteitemconfirm')?</p>
                                    <div class="flex flex-col md:flex-row gap-2 justify-between">
                                        <x-primary-button type="submit"
                                            class="bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.delete')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('goods.index') }}" tabindex="1" autofocus>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.cancel')</span>
                                        </x-anchor-secondary>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-4 space-y-2">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <label for="jenis_barang_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.warehouse')</label>
                                    <x-text-span>{{ $datas->gudang->nama }}</x-text-span>
                                </div>

                                <div class="flex flex-row justify-between gap-4">
                                    <div class="w-1/2 pb-4">
                                        <label for="jenis_barang_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.typeofdesignation')</label>
                                        <x-text-span>{{ $datas->jenis_barang->nama }}</x-text-span>
                                    </div>

                                    <div class="w-1/2 pb-4">
                                        <label for="subjenis_barang_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.typeofsubdesignation')</label>
                                        <x-text-span>{{ $datas->subjenis_barang_id ? $datas->subjenis_barang->nama : '-' }}</x-text-span>
                                    </div>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="nama"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.nameofgoods')</label>
                                    <x-text-span>{{ $datas->nama }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="merk"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.brand')</label>
                                    <x-text-span>{{ $datas->merk }}</x-text-span>
                                </div>

                                <div class="flex flex-row justify-between gap-4">
                                    <div class="w-1/2 pb-4">
                                        <label for="harga_satuan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.unitpricebuy')</label>
                                        <x-text-span>Rp.
                                            {{ number_format($datas->harga_satuan, 0, ',', '.') }}</x-text-span>
                                    </div>

                                    <div class="w-1/2 pb-4">
                                        <label for="satuan_beli_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.unit')</label>
                                        <x-text-span>{{ $datas->satuan_beli_id ? $datas->satuan_beli->nama_lengkap : '-' }}</x-text-span>
                                    </div>
                                </div>

                                <div class="flex flex-row justify-between gap-4">
                                    <div class="w-1/2 pb-4">
                                        <label for="harga_satuan_jual"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.unitpricesell')</label>
                                        <x-text-span>Rp.
                                            {{ number_format($datas->harga_satuan_jual, 0, ',', '.') }}</x-text-span>
                                    </div>

                                    <div class="w-1/2 pb-4">
                                        <label for="satuan_jual_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.unit')</label>
                                        <x-text-span>{{ $datas->satuan_jual_id ? $datas->satuan_jual->nama_lengkap : '-' }}</x-text-span>
                                    </div>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="w-auto pb-4">
                                    <label for="keterangan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                    <x-text-span>{{ $datas->keterangan }}</x-text-span>
                                </div>

                                <div class="flex flex-row flex-wrap md:justify-between">
                                    <div class="w-1/2 md:w-1/3 pb-4">
                                        <label for="stock"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.stock')</label>
                                        <x-text-span
                                            class="{{ $datas->stock < $datas->minstock ? 'text-white bg-red-700' : 'text-gray-900 bg-primary-50' }} {{ $datas->stock < $datas->minstock ? 'dark:text-white dark:bg-red-700' : 'dark:text-white dark:bg-primary-800' }}">{{ $datas->stock }}</x-text-span>
                                    </div>

                                    <div class="w-1/2 md:w-1/3 pb-4">
                                        <label for="minstock"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.minstock')</label>
                                        <x-text-span>{{ $datas->minstock }}</x-text-span>
                                    </div>

                                    <div class="w-1/2 md:w-1/3 pb-4">
                                        <label for="satuan_stock_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.unit')</label>
                                        <x-text-span>{{ $datas->satuan_stock_id ? $datas->satuan_stock->nama_lengkap : '-' }}</x-text-span>
                                    </div>
                                </div>

                                <div class="pb-4 lg:pb-12">
                                    <label for="gambar"
                                        class="text-center block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.picture')</label>
                                    <div class="mt-2 flex justify-center">
                                        <img id="image-preview" class="w-full lg:w-3/5 h-auto border rounded-lg"
                                            @if ($datas->gambar) src="{{ asset($datas->lokasi . '/' . $datas->gambar) }}" @else src="/images/0cd6be830e32f80192d496e50cfa9dbc.jpg" @endif
                                            alt="o.o" />
                                    </div>
                                </div>

                                <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                    <div class="pr-2">
                                        <div class="inline-flex items-center">
                                            @if ($datas->isactive == '1')
                                                <span>✔️</span>
                                            @endif
                                            @if ($datas->isactive == '0')
                                                <span>❌</span>
                                            @endif
                                            <span class='pl-2'>@lang('messages.active')</span>
                                        </div>
                                    </div>

                                    <x-anchor-secondary href="{{ route('goods.index') }}" tabindex="2">
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
</x-app-layout>
