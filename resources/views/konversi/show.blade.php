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
            <span class="px-2 font-semibold">@lang('messages.view')</span>
        </h1>
    </div>

    <div class="py-4 flex flex-col">

        <div class="w-full px-2 sm:px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('konversi.partials.feedback')
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

    @push('scripts')
    @endpush
</x-app-layout>
