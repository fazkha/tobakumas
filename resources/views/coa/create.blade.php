@section('title', __('messages.chartofaccount'))

<x-app-layout>
    <div x-data="{
        openModal: false,
        modalTitle: 'Group'
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
                <span class="px-2 font-semibold">New</span>
            </h1>
        </div>

        <form action="{{ route('coa.store') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="py-2 flex flex-col">

                <div class="w-full">
                    <div class="flex flex-col items-center">

                        <div class="w-full" role="alert">
                            @include('coa.partials.feedback')
                        </div>

                        <div
                            class="w-full shadow-lg rounded-md border bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                            <div class="p-4 space-y-2 md:p-6 md:space-y-4">

                                <div class="flex flex-col lg:flex-row">
                                    <div class="w-full lg:w-1/2 px-2">

                                        <div class="pb-4">
                                            <div class="relative">
                                                <span @click="openModal = true; modalTitle = 'COA Group';"
                                                    class="w-10 text-xs h-2/6 absolute top-1/2 left-0 flex items-center px-3 mt-1 border-r border-gray-400 dark:border-primary-800">
                                                    <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 24 24"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M21,12a1,1,0,0,0-1,1v6a1,1,0,0,1-1,1H5a1,1,0,0,1-1-1V5A1,1,0,0,1,5,4h6a1,1,0,0,0,0-2H5A3,3,0,0,0,2,5V19a3,3,0,0,0,3,3H19a3,3,0,0,0,3-3V13A1,1,0,0,0,21,12ZM6,12.76V17a1,1,0,0,0,1,1h4.24a1,1,0,0,0,.71-.29l6.92-6.93h0L21.71,8a1,1,0,0,0,0-1.42L17.47,2.29a1,1,0,0,0-1.42,0L13.23,5.12h0L6.29,12.05A1,1,0,0,0,6,12.76ZM16.76,4.41l2.83,2.83L18.17,8.66,15.34,5.83ZM8,13.17l5.93-5.93,2.83,2.83L10.83,16H8Z" />
                                                    </svg>
                                                </span>

                                                <label for="coasgroups_id"
                                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Account
                                                    Group</label>
                                                <select name="coasgroups_id" id="coasgroups_id"
                                                    class="w-full pl-14 block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary dark:text-gray dark:placeholder-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300">
                                                    <option value="">Choose group...</option>
                                                    @foreach ($coasgroups as $id => $name)
                                                        <option value="{{ $id }}"
                                                            {{ old('coasgroups_id') === $id ? 'selected' : '' }}>
                                                            {{ $name }}</option>
                                                    @endforeach
                                                </select>

                                                <x-input-error class="mt-2" :messages="$errors->get('educationleve_id')" />
                                            </div>
                                        </div>

                                        <div class="flex flex-row justify-between gap-2">
                                            <div class="w-auto pb-4">
                                                <label for="code"
                                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Code</label>
                                                <x-text-input type="text" name="code" id="code"
                                                    placeholder="Enter code" required value="{{ old('code') }}" />

                                                <x-input-error class="mt-2" :messages="$errors->get('code')" />
                                            </div>

                                            <div class="w-2/3 pb-4">
                                                <label for="name"
                                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Name</label>
                                                <x-text-input type="text" name="name" id="name"
                                                    placeholder="Enter name" required value="{{ old('name') }}" />

                                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                                            </div>
                                        </div>

                                    </div>

                                    <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                        <div class="pb-4 lg:pb-12">
                                            <label for="balance"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Balance
                                                Side</label>
                                            <select name="balance" id="balance"
                                                class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                <option value="">Choose side...</option>
                                                <option value="1" {{ old('balance') === '1' ? 'selected' : '' }}>
                                                    Debit
                                                </option>
                                                <option value="2" {{ old('balance') === '2' ? 'selected' : '' }}>
                                                    Credit
                                                </option>
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('balance')" />
                                        </div>

                                        <div class="flex flex-row items-center justify-end gap-2 md:gap-4">
                                            <div class="pr-2">
                                                <div class="inline-flex items-center">
                                                    <input type="checkbox" id="isactive" name="isactive"
                                                        class="form-control">
                                                    <label for="isactive"
                                                        class="ml-2 block font-medium text-primary-600 dark:text-primary-500">Active</label>
                                                </div>

                                                <x-input-error class="mt-2" :messages="$errors->get('isactive')" />
                                            </div>

                                            <x-primary-button type="submit" class="block">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                                </svg>
                                                <span class="pl-1">Save</span>
                                            </x-primary-button>
                                            <x-anchor-secondary href="{{ route('coa.index') }}">
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

        <div x-show.transition.duration.500ms="openModal"
            class="fixed inset-0 flex items-center justify-center px-4 md:px-0 bg-white bg-opacity-75 dark:bg-black dark:bg-opacity-75">
            <div @click.away="openModal = false"
                class="flex flex-col p-6 h-full w-auto shadow-2xl rounded-lg border-2 bg-white border-gray-400 dark:bg-gray-700 dark:border-gray-900">
                <div class="flex justify-between mb-4">
                    <h1 class="font-bold text-xl text-gray-900 dark:text-gray-50"><span x-html="modalTitle"></span>
                    </h1>
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
                    Content
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush

</x-app-layout>
