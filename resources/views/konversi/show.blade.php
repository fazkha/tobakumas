@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@section('title', __('messages.conversion'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('conversions.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 17 17" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <path
                        d="M6 8h-6v-6h1v4.109c1.013-3.193 4.036-5.484 7.5-5.484 3.506 0 6.621 2.36 7.574 5.739l-0.963 0.271c-0.832-2.95-3.551-5.011-6.611-5.011-3.226 0.001-6.016 2.276-6.708 5.376h4.208v1zM11 9v1h4.208c-0.693 3.101-3.479 5.375-6.708 5.375-3.062 0-5.78-2.061-6.611-5.011l-0.963 0.271c0.952 3.379 4.067 5.739 7.574 5.739 3.459 0 6.475-2.28 7.5-5.482v4.108h1v-6h-6z"
                        fill="currentColor" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.warehouse')</span>
                    <span>@lang('messages.conversion')</span>
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
                    @include('konversi.partials.feedback')
                </div>

                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-4 space-y-2">

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
