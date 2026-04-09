@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@section('title', __('messages.pcbiaya'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('pcbiaya.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_901_1341)">
                        <path
                            d="M15 17C15 15.343 13.657 14 12 14M12 14C10.343 14 9 15.343 9 17C9 18.657 10.343 20 12 20C13.657 20 15 21.343 15 23C15 24.657 13.657 26 12 26M12 14V13M12 26C10.343 26 9 24.657 9 23M12 26V27M22 31H31V29M25 26H31V24M26 21H31V19M26 16H31V14M23 11H31V9M10 6H31V1H7V6M23 20C23 13.926 18.074 9 12 9C5.926 9 1 13.926 1 20C1 26.074 5.926 31 12 31C18.074 31 23 26.074 23 20Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </g>
                    <defs>
                        <clipPath id="clip0_901_1341">
                            <rect width="32" height="32" fill="white" />
                        </clipPath>
                    </defs>
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.finance')</span>
                    <span>@lang('messages.pcbiaya')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <div x-data="{
        openModal: false,
        imagePreview: '{{ asset('images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}',
        modalTitle: 'Title'
    }" class="w-full overflow-x-auto">
        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('pcbiaya.partials.feedback')
                    </div>

                    {{-- Master --}}
                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">

                                    <div class="w-auto pb-4">
                                        <span for="branch_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.branch')</span>
                                        <x-text-span>{{ $details[0]->cabang->nama }}</x-text-span>
                                    </div>

                                    <div class="w-auto pb-4">
                                        <span for="tanggal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.transactiondate')</span>
                                        <x-text-span>{{ $details[0]->tanggal }}</x-text-span>
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4 lg:pb-12">
                                        <span for="pc_nama"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.pcname')</span>
                                        <x-text-span>{{ $details[0]->pc_nama }}</x-text-span>
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <x-primary-button type="submit" class="block" tabindex="7">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('pcbiaya.index') }}" tabindex="8">
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

            {{-- <form
                action="{{ route('pcbiaya.updatee', ['branch_id' => Crypt::Encrypt($details[0]->branch_id), 'tanggal' => Crypt::Encrypt($details[0]->tanggal)]) }}"
                method="POST" enctype="multipart/form-data">
                @csrf --}}

            <div class="flex flex-col lg:flex-row gap-4 px-4 py-2">
                <div class="w-full">
                    <div class="flex flex-col items-center">

                        {{-- Detail --}}
                        <div
                            class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                            <div class="p-4 space-y-2">
                                <div class="flex flex-row items-center gap-2">
                                    <svg class="size-5" viewBox="0 0 32 32" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <g clip-path="url(#clip0_901_1341)">
                                            <path
                                                d="M15 17C15 15.343 13.657 14 12 14M12 14C10.343 14 9 15.343 9 17C9 18.657 10.343 20 12 20C13.657 20 15 21.343 15 23C15 24.657 13.657 26 12 26M12 14V13M12 26C10.343 26 9 24.657 9 23M12 26V27M22 31H31V29M25 26H31V24M26 21H31V19M26 16H31V14M23 11H31V9M10 6H31V1H7V6M23 20C23 13.926 18.074 9 12 9C5.926 9 1 13.926 1 20C1 26.074 5.926 31 12 31C18.074 31 23 26.074 23 20Z"
                                                stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                                stroke-linejoin="round" />
                                        </g>
                                        <defs>
                                            <clipPath id="clip0_901_1341">
                                                <rect width="32" height="32" fill="white" />
                                            </clipPath>
                                        </defs>
                                    </svg>
                                    <span class="block font-medium text-primary-600 dark:text-primary-500">
                                        @lang('messages.cost')
                                    </span>
                                </div>

                                <div
                                    class="border rounded-md border-primary-100 bg-primary-100 dark:border-primary-800 dark:bg-primary-850">
                                    <div class="p-2 overflow-scroll md:overflow-auto lg:overflow-hidden">
                                        <table id="order_table" class="w-full border-separate border-spacing-2">
                                            <thead>
                                                <tr>
                                                    <th class="w-1/4">@lang('messages.costtype')</th>
                                                    <th class="w-auto">@lang('messages.cost') (@lang('messages.thousands')
                                                        @lang('messages.currencysymbol'))</th>
                                                    <th class="w-auto">@lang('messages.attachment')</th>
                                                    <th class="w-auto text-center">@lang('messages.approval')</th>
                                                </tr>
                                            </thead>

                                            <tbody id="detailBody">
                                                @include('pcbiaya.partials.details', [
                                                    $details,
                                                    'viewMode' => false,
                                                ])
                                            </tbody>

                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- </form> --}}
        </div>

        <div x-show.transition.duration.500ms="openModal"
            class="fixed inset-0 flex items-center justify-center px-4 md:px-0 bg-white bg-opacity-75 dark:bg-black dark:bg-opacity-75">
            <div @click.away="openModal = false"
                class="flex flex-col p-6 h-auto w-auto shadow-2xl rounded-lg border-2 bg-white border-gray-400 dark:bg-gray-700 dark:border-gray-900">
                <div class="flex justify-between mb-4">
                    <div class="font-bold text-lg text-gray-900 dark:text-gray-50"><span x-html="modalTitle"></span>
                    </div>
                    <button @click="openModal = false">
                        <svg class="w-5 h-5 text-gray-900 dark:text-gray-50" viewBox="0 0 24 24" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5.293 5.293a1 1 0 0 1 1.414 0L12 10.586l5.293-5.293a1 1 0 1 1 1.414 1.414L13.414 12l5.293 5.293a1 1 0 0 1-1.414 1.414L12 13.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L10.586 12 5.293 6.707a1 1 0 0 1 0-1.414z"
                                fill="currentColor" />
                        </svg>
                    </button>
                </div>
                <div class="flex items-center justify-center overflow-hidden rounded-lg">
                    <img x-ref="imgRef" src="" class="w-auto h-full max-h-96" />
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
</x-app-layout>
