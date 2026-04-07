@section('title', __('messages.pcpettycash'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('pcpettycash.index') }}" class="flex items-center justify-center">
                <svg class="size-7" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 200.158 200.158"
                    style="enable-background:new 0 0 200.158 200.158;" xml:space="preserve">
                    <g>
                        <g>
                            <g>
                                <path style="fill:#010002;"
                                    d="M136.222,42.807c0-17.454-14.19-31.655-31.633-31.655c-17.45,0-31.651,14.201-31.651,31.655 c0,17.443,14.197,31.641,31.651,31.641C122.032,74.447,136.222,60.246,136.222,42.807z M104.588,69.397 c-14.677,0-26.602-11.928-26.602-26.594c0-14.67,11.925-26.605,26.602-26.605c14.67,0,26.598,11.935,26.598,26.605 C131.183,57.473,119.254,69.397,104.588,69.397z" />
                                <path style="fill:#010002;"
                                    d="M106.46,39.693c-4.166-1.775-4.842-2.731-4.842-4.341c0-1.371,0.619-3.003,3.6-3.003 c2.745,0,4.495,1.002,5.343,1.492l1.066,0.608l1.915-5.071l-0.784-0.447c-1.689-0.948-3.497-1.503-5.519-1.7v-4.581h-5.035v4.842 c-4.277,1.045-6.986,4.245-6.986,8.367c-0.011,5.103,4.212,7.455,8.335,9.076c3.579,1.446,4.32,2.874,4.32,4.57 c0,0.959-0.319,1.786-0.927,2.394c-1.818,1.829-6.442,1.22-9.359-0.709l-1.12-0.73l-1.84,5.125l0.651,0.458 c1.643,1.174,4.112,1.979,6.639,2.197v4.699h5.035v-5.025c4.427-1.034,7.358-4.474,7.358-8.783 C114.319,44.761,111.964,41.933,106.46,39.693z" />
                                <path style="fill:#010002;"
                                    d="M145.731,59.158c-13.439,0-24.383,10.937-24.383,24.365c0,13.439,10.948,24.361,24.383,24.361 s24.365-10.923,24.365-24.361C170.092,70.095,159.169,59.158,145.731,59.158z M145.731,103.554 c-11.055,0-20.045-8.986-20.045-20.031s8.99-20.031,20.045-20.031c11.044,0,20.024,8.986,20.024,20.031 S156.775,103.554,145.731,103.554z" />
                                <path style="fill:#010002;"
                                    d="M147.245,80.932c-3.038-1.296-3.525-1.968-3.525-3.089c0-1.36,0.837-2.047,2.48-2.047 c2.022,0,3.285,0.719,3.958,1.109l1.066,0.598l1.621-4.32l-0.773-0.447c-1.242-0.705-2.58-1.142-4.069-1.306v-3.522h-4.355v3.765 c-3.207,0.859-5.307,3.407-5.307,6.56c0,4.037,3.296,5.887,6.506,7.158c2.609,1.045,3.135,2.022,3.121,3.253 c0,0.651-0.208,1.217-0.608,1.632c-1.292,1.281-4.681,0.848-6.832-0.565l-1.113-0.73l-1.585,4.373l0.641,0.455 c1.242,0.884,3.081,1.503,4.975,1.711v3.611h4.32l0.021-3.883c3.386-0.87,5.604-3.547,5.604-6.896 C153.39,83.978,150.093,82.085,147.245,80.932z" />
                            </g>
                            <path style="fill:#010002;"
                                d="M0,172.647h31.555v-62.576H0V172.647z M6.288,116.363h18.975v49.997H6.288V116.363z" />
                            <path style="fill:#010002;"
                                d="M199.299,148.733c-1.142-2.416-12.154-24.558-25.091-22.253c-3.572,0.655-6.682,3.919-10.991,8.432 c-7.333,7.712-17.386,18.288-33.426,18.288c-9.781,0-20.825-3.969-32.936-11.828l25.113-0.011c0,0,0.251,0.021,0.666,0.021 c6.585,0,10.837-3.579,10.837-9.13v-10.411l-0.064-0.623c-0.73-3.686-4.037-10.654-11.23-10.654H37.191v60.239l2.197,0.694 c2.283,0.705,55.973,17.476,75.603,17.476l0,0c0,0,0.565,0.032,1.621,0.032c8.453,0,52.13-1.782,82.228-36.898l1.317-1.546 L199.299,148.733z M116.613,182.725c-0.837,0-1.306-0.021-1.482-0.021c-16.634,0-61.216-13.331-71.652-16.516V116.86h78.692 c3.361,0,4.71,4.262,5.003,5.354v10.042c0,2.351-2.469,2.842-4.549,2.842l-45.022-0.011l7.444,5.644 c16.388,12.451,31.444,18.76,44.743,18.76c18.725,0,30.327-12.175,37.986-20.238c2.806-2.96,5.995-6.302,7.559-6.596 c6.106-0.998,13.417,9.323,17.368,16.924C164.445,181.104,124.447,182.725,116.613,182.725z" />
                        </g>
                    </g>
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.finance')</span>
                    <span>@lang('messages.pcpettycash')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <form action="{{ route('pcpettycash.update', Crypt::Encrypt($datas->id)) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('pcpettycash.partials.feedback')
                    </div>

                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">

                                    <div class="w-auto pb-4">
                                        <label for="branch_id"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.branch')</label>
                                        <select name="branch_id" id="branch_id" tabindex="1" required autofocus
                                            class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-600 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                            <option value="">@lang('messages.choose')...</option>
                                            @foreach ($branches as $id => $name)
                                                <option value="{{ $id }}"
                                                    {{ old('branch_id', $datas->branch_id) == $id ? 'selected' : '' }}>
                                                    {{ $name }}</option>
                                            @endforeach
                                        </select>

                                        <x-input-error class="mt-2" :messages="$errors->get('branch_id')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="tanggal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.transactiondate')</label>
                                        <x-text-input type="date" name="tanggal" id="tanggal"
                                            data-date-format="dd-mm-yyyy" tabindex="2" required
                                            value="{{ $datas->tanggal }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('tanggal')" />
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4 lg:pb-12">
                                        <label for="nominal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.nominal')
                                            (@lang('messages.thousands') @lang('messages.currencysymbol'))</label>
                                        <x-text-input type="text" name="nominal" id="nominal" tabindex="3"
                                            value="{{ $datas->nominal }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('nominal')" />
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
                                        <x-anchor-secondary href="{{ route('pcpettycash.index') }}" tabindex="8">
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
    </form>

    @push('scripts')
    @endpush
</x-app-layout>
