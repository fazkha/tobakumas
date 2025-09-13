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
            <span class="px-2 font-semibold">@lang('messages.view')</span>
        </h1>
    </div>

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
                                    <input type="hidden" name="branch_id" value="{{ $datas->branch_id }}" />
                                    <x-text-span>{{ $datas->branch->nama }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <span for="customer_group_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.customergroup')</span>
                                    <x-text-span>{{ $datas->customer_group->nama }}</x-text-span>
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

                                    <x-anchor-secondary href="{{ route('customer.index') }}" tabindex="1" autofocus>
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
