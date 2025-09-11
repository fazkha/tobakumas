@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@section('title', __('messages.chartofaccount'))

<x-app-layout>
    <div x-data="{
        imagePreview: '/images/0cd6be830e32f80192d496e50cfa9dbc.jpg',
        imageOrientation: 'p',
        openModalImage: false
    }">

        <div
            class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
            <h1 class="text-xl flex items-center justify-center">
                <a href="{{ route('coa.index') }}" class="flex items-center justify-center">
                    <svg class="w-7 h-7" viewBox="0 0 1024 1024" fill="currentColor" class="icon" version="1.1"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M53.6 1023.2c-6.4 0-12.8-2.4-17.6-8-4.8-4.8-7.2-11.2-6.4-18.4L80 222.4c0.8-12.8 11.2-22.4 24-22.4h211.2v-3.2c0-52.8 20.8-101.6 57.6-139.2C410.4 21.6 459.2 0.8 512 0.8c108 0 196.8 88 196.8 196.8 0 0.8-0.8 1.6-0.8 2.4v0.8H920c12.8 0 23.2 9.6 24 22.4l49.6 768.8c0.8 2.4 0.8 4 0.8 6.4-0.8 13.6-11.2 24.8-24.8 24.8H53.6z m25.6-48H944l-46.4-726.4H708v57.6h0.8c12.8 8.8 20 21.6 20 36 0 24.8-20 44.8-44.8 44.8s-44.8-20-44.8-44.8c0-14.4 7.2-27.2 20-36h0.8v-57.6H363.2v57.6h0.8c12.8 8.8 20 21.6 20 36 0 24.8-20 44.8-44.8 44.8-24.8 0-44.8-20-44.8-44.8 0-14.4 7.2-27.2 20-36h0.8v-57.6H125.6l-46.4 726.4zM512 49.6c-81.6 0-148.8 66.4-148.8 148.8v3.2h298.4l-0.8-1.6v-1.6c0-82.4-67.2-148.8-148.8-148.8z"
                            fill="" />
                    </svg>
                    <span class="px-2">@lang('messages.chartofaccount')</span>
                </a>
                <span class="px-2">&raquo;</span>
                <span class="px-2 font-semibold">Delete</span>
            </h1>
        </div>

        <div class="flex justify-center pt-4 px-2" role="alert">
            <form class="w-full lg:w-1/2" action="{{ route('coa.destroy', Crypt::Encrypt($datas->id)) }}" method="POST"
                enctype="multipart/form-data">
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
                                <x-anchor-secondary href="{{ route('coa.index') }}" tabindex="1" autofocus>
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                    <span class="pl-1">@lang('messages.cancel')</span>
                                </x-anchor-secondary>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>

        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('coa.partials.feedback')
                    </div>

                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">
                                    <div class="pb-4">
                                        <span
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Title</span>
                                        <x-text-span>{{ $datas->title }}</x-text-span>
                                    </div>

                                    <div class="flex flex-row justify-between gap-2">
                                        <div class="w-auto pb-4">
                                            <label
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Number
                                                Of Question(s)</label>
                                            <x-text-span>{{ $datas->quest_amount }}</x-text-span>
                                        </div>

                                        <div class="w-1/2 pb-4">
                                            <label
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Price
                                                (Rp.)</label>
                                            <x-text-span>{{ number_format($datas->price, 0, ',', '.') }}</x-text-span>
                                        </div>
                                    </div>

                                    <div class="pb-4">
                                        <span for="educationlevel_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Educational
                                            Level</span>
                                        <x-text-span>{{ $datas->educationlevel->name }}</x-text-span>
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4 lg:pb-12">
                                        <span
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Description</span>
                                        <x-text-span>{{ $datas->description }}</x-text-span>
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="pr-2">
                                            <div class="inline-flex items-center">
                                                @if ($datas->isactive == '1')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5 text-green-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="m4.5 12.75 6 6 9-13.5" />
                                                    </svg>
                                                @endif
                                                @if ($datas->isactive == '0')
                                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                        viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                        class="size-5 text-red-600">
                                                        <path stroke-linecap="round" stroke-linejoin="round"
                                                            d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                                    </svg>
                                                @endif
                                                <label>Active</label>
                                            </div>
                                        </div>

                                        <x-anchor-secondary href="{{ route('coa.index') }}" tabindex="2">
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

            <div class="flex flex-col lg:flex-row gap-4 px-4 py-2">

                <div class="w-full">
                    <div class="flex flex-col items-center">

                        <div class="w-full" role="alert">
                            @include('coa.partials.feedback-detail')
                        </div>

                        <div
                            class="w-full shadow-lg rounded-md border bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                            <div class="p-4 space-y-2">
                                <div class="flex flex-row
                            items-center justify-between">
                                    <span
                                        class="block font-medium text-primary-600 dark:text-primary-500">Question(s)</span>
                                </div>

                                <div
                                    class="border rounded-md border-primary-500 bg-gray-300 dark:border-primary-800 dark:bg-gray-800">
                                    <div id="table-question-container" class="p-4 overflow-hidden">
                                        <table id="question_table" class="w-full border-separate border-spacing-2">
                                            <thead>
                                                <tr>
                                                    <th class="w-10">Sequence</th>
                                                    <th class="w-auto">Question</th>
                                                    <th class="w-1/4">Answer</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $i = 0;
                                                    $ids = [];
                                                    $selected = '';
                                                @endphp

                                                @if ($details->count() == 0)
                                                    <tr id="{{ 'question' . $i }}">
                                                        <td>
                                                            <x-text-span>&nbsp;</x-text-span>
                                                        </td>
                                                        <td>
                                                            <x-text-span>&nbsp;</x-text-span>
                                                        </td>
                                                        <td>
                                                            <x-text-span>&nbsp;</x-text-span>
                                                        </td>
                                                    </tr>
                                                @endif

                                                @foreach ($details as $detail)
                                                    @foreach ($detail->choice as $choice)
                                                        @if ($choice->id == $detail->correct)
                                                            @php
                                                                $selected = $choice->choice;
                                                            @endphp
                                                        @endif
                                                    @endforeach
                                                    <tr id="question{{ $i }}">
                                                        <td class="align-top">
                                                            <x-text-span>{{ $detail->seq }}</x-text-span>
                                                        </td>
                                                        <td class="relative align-top">
                                                            <img @click="
                                                            imagePreview = '{{ $detail->gambar ? asset($detail->lokasi . '/' . $detail->gambar) : asset('/images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}'; 
                                                            openModalImage = true;"
                                                                src="{{ $detail->gambar ? asset($detail->lokasi . '/' . $detail->gambar) : asset('/images/no-image.256x256.png') }}"
                                                                alt="o.o"
                                                                class="absolute top-[2px] left-[2px] h-9 w-9 rounded-tl-md rounded-br-md bg-gray-300 dark:bg-gray-600" />
                                                            <x-text-span
                                                                class="pl-11">{{ $detail->question }}</x-text-span>
                                                        </td>
                                                        <td class="align-top">
                                                            <x-text-span>{{ $selected ? $selected : '---Not Available---' }}</x-text-span>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $i++;
                                                    @endphp
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="openModalImage" x-cloak x-transition
            class="z-50 fixed inset-0 bg-white bg-opacity-75 dark:bg-black dark:bg-opacity-75 flex items-center justify-center px-4 md:px-0">
            <div @click.away="openModalImage = false;"
                class="flex flex-col p-0 h-full w-full shadow-2xl rounded-lg border-2 bg-white border-gray-300 dark:bg-darker dark:border-gray-700">
                <div class="absolute top-4 right-4">
                    <button @click="openModalImage = false;" class="p-2 rounded-full bg-red-600">
                        <svg class="w-5 h-5 text-white dark:text-white-700" viewBox="0 0 24 24" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5.293 5.293a1 1 0 0 1 1.414 0L12 10.586l5.293-5.293a1 1 0 1 1 1.414 1.414L13.414 12l5.293 5.293a1 1 0 0 1-1.414 1.414L12 13.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L10.586 12 5.293 6.707a1 1 0 0 1 0-1.414z"
                                fill="currentColor" />
                        </svg>
                    </button>
                </div>
                <div
                    class="flex h-full items-center justify-center overflow-auto rounded-lg border border-primary-500 bg-primary-50 dark:border-primary-800 dark:bg-primary-800">
                    <img id="gambar_soal" :src="imagePreview" alt="o.o"
                        :class="imageOrientation == 'p' ? 'w-auto h-full' : 'w-full h-auto'" />
                </div>
            </div>
        </div>

    </div>
</x-app-layout>
