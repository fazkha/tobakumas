@php
    use Illuminate\Support\Facades\Crypt;
@endphp
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
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.sale')</span>
                    <span>@lang('messages.customer')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.delete')</span>
        </h1>
    </div>

    <div class="py-2 flex flex-col">
        <div class="container mx-auto px-2 sm:px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-3/4 lg:w-1/2 shadow mb-5" role="alert">
                    <form action="{{ route('customer.destroy', Crypt::Encrypt($datas->id)) }}" class="block"
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
                                        <x-anchor-secondary href="{{ route('customer.index') }}" tabindex="1"
                                            autofocus>
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
                                    <label for="branch_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.branch')</label>
                                    <input type="hidden" name="branch_id" value="{{ $datas->branch_id }}" />
                                    <x-text-span>{{ $datas->branch->nama }}</x-text-span>
                                </div>

                                <div class="flex flex-row gap-2">
                                    <div class="w-1/3 pb-4">
                                        <span for="customer_group_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.customergroup')</span>
                                        <x-text-span>{{ $datas->customer_group->nama }}</x-text-span>
                                    </div>

                                    <div class="w-2/3 pb-4">
                                        <span for="customer_group_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.relatedbranch')</span>
                                        <x-text-span>{{ $datas->branch_link->nama }}</x-text-span>
                                    </div>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="kode"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.customercode')</span>
                                    <x-text-span>{{ $datas->kode }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="nama"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.customername')</span>
                                    <x-text-span>{{ $datas->nama }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="alamat"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.customeraddress')</span>
                                    <x-text-span>{{ $datas->alamat }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="propinsi"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.propinsi')</span>
                                    <x-text-span>{{ $datas->propinsi->nama }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="kabupaten"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.kabupaten')</span>
                                    <x-text-span>{{ $datas->kabupaten->nama }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="kecamatan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.kecamatan')</span>
                                    <x-text-span>{{ $datas->kecamatan->nama }}</x-text-span>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="w-auto pb-4">
                                    <span for="tanggal_gabung"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.joindate')</span>
                                    <x-text-span>{{ date('d/m/Y', strtotime($datas->tanggal_gabung)) }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="kontak_nama"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.contactname')</span>
                                    <x-text-span>{{ $datas->kontak_nama }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="kontak_telpon"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.contactphone')</span>
                                    <x-text-span>{{ $datas->kontak_telpon }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4 lg:pb-12">
                                    <span for="keterangan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</span>
                                    <x-text-span>{{ $datas->keterangan ? $datas->keterangan : '...' }}</x-text-span>
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

                                    <x-anchor-secondary href="{{ route('customer.index') }}" tabindex="2">
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
