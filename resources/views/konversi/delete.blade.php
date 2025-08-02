@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@section('title', __('messages.conversion'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('conversions.index') }}" class="flex items-center justify-center">
                <svg class="w-7 h-7" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 204.045 204.045"
                    style="enable-background:new 0 0 204.045 204.045;" xml:space="preserve">
                    <g>
                        <g>
                            <path style="fill:#010002;"
                                d="M5.239,97.656c0-23.577,19.186-42.764,42.771-42.764h146.661l-38.931,38.931l3.461,3.461
       l44.843-44.843L159.202,7.601l-3.461,3.464l38.931,38.924H48.01c-26.287,0-47.663,21.387-47.663,47.663v0.494h4.896v-0.49H5.239z" />
                            <path style="fill:#010002;" d="M198.805,106.388c0,23.577-19.19,42.764-42.767,42.764H9.377l38.931-38.931l-3.461-3.461L0,151.604
       l44.843,44.839l3.461-3.468L9.377,154.052h146.661c26.283,0,47.663-21.387,47.663-47.663v-0.494h-4.896V106.388z" />
                        </g>
                    </g>
                </svg>
                <span class="px-2">@lang('messages.conversion')</span>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.delete')</span>
        </h1>
    </div>

    <div class="py-4 flex flex-col">
        <div class="container mx-auto px-2 sm:px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-1/2 shadow mb-5" role="alert">
                    <form action="{{ route('conversions.destroy', Crypt::Encrypt($datas->id)) }}" class="block"
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
                                    <div class="flex justify-between">
                                        <x-primary-button type="submit"
                                            class="bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.delete')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('conversions.index') }}" tabindex="1"
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
                    <div class="p-6 space-y-4 md:space-y-6 sm:p-8">

                        <div class="flex flex-col lg:flex-row">
                            <div class="w-full lg:w-1/2 px-2">

                                <div class="w-auto pb-4">
                                    <label for="satuan_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.usedunit')</label>
                                    <x-text-span>{{ $datas->satuan->nama_lengkap }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4">
                                    <label for="satuan2_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.desiredunit')</label>
                                    <x-text-span>{{ $datas->satuan2->nama_lengkap }}</x-text-span>
                                </div>
                            </div>

                            <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                <div class="w-auto pb-4">
                                    <label for="operator"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.operator')</label>
                                    @php
                                        switch ($datas->operator) {
                                            case config('custom.nilai_tambah'):
                                                $simbol = config('custom.simbol_tambah');
                                                break;
                                            case config('custom.nilai_kurang'):
                                                $simbol = config('custom.simbol_kurang');
                                                break;
                                            case config('custom.nilai_bagi'):
                                                $simbol = config('custom.simbol_bagi');
                                                break;
                                            case config('custom.nilai_kali'):
                                                $simbol = config('custom.simbol_kali');
                                                break;
                                            default:
                                                $simbol = '-';
                                                break;
                                        }
                                    @endphp
                                    <x-text-span>{{ $simbol }}</x-text-span>
                                </div>

                                <div class="w-auto pb-4 lg:pb-12">
                                    <label for="bilangan"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.number')</label>
                                    <x-text-span>{{ $datas->bilangan }}</x-text-span>
                                </div>

                                <div class="flex flex-row items-center justify-end gap-4">
                                    <div class="pr-2">
                                        <div class="inline-flex items-center">
                                            @if ($datas->isactive == '1')
                                                <span>✔️</span>
                                            @endif
                                            @if ($datas->isactive == '0')
                                                <span>❌</span>
                                            @endif
                                            <label class='pl-2'>@lang('messages.active')</label>
                                        </div>
                                    </div>

                                    <x-anchor-secondary href="{{ route('conversions.index') }}" tabindex="1"
                                        autofocus>
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
