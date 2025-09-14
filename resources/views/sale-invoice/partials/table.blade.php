<div id="mainDiv" x-data="{
    openModal: false,
    modalTitle: 'Title'
}">
    <form id="index-form" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="w-full overflow-x-auto">
            <div
                class="inline-block min-w-full shadow-md overflow-hidden rounded-md border border-solid border-primary-100 dark:border-primary-800">
                <table id="list-table" class="min-w-full leading-normal">
                    <thead>
                        <tr>
                            <th
                                class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                                <div>
                                    <input type="checkbox" id="isprintall" name="isprintall" tabindex="0"
                                        class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-5 h-5 rounded-lg shadow-md">
                                </div>
                            </th>
                            <th
                                class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                                @lang('messages.ordernumber')
                            </th>
                            <th
                                class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                                @lang('calendar.date')
                            </th>
                            <th
                                class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                                @lang('messages.customer')
                            </th>
                            <th
                                class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                                @lang('messages.totalprice') (Rp.)
                            </th>
                            <th
                                class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                                @lang('messages.payment')
                            </th>
                            <th
                                class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                                @lang('messages.active')
                            </th>
                            <th
                                class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                                <div class="flex items-center justify-center">
                                    <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 256 256" id="Flat"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M128,20A108,108,0,1,0,236,128,108.12186,108.12186,0,0,0,128,20Zm0,192a84,84,0,1,1,84-84A84.09562,84.09562,0,0,1,128,212ZM144,84v92a12,12,0,0,1-24,0V106.417l-5.3457,3.5625a12.00027,12.00027,0,1,1-13.3086-19.97265l24-15.99317A12.00071,12.00071,0,0,1,144,84Z" />
                                    </svg>
                                </div>
                            </th>
                            <th
                                class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                                <div class="flex items-center justify-center">
                                    <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 256 256" id="Flat"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M128,20A108,108,0,1,0,236,128,108.12186,108.12186,0,0,0,128,20Zm0,192a84,84,0,1,1,84-84A84.09562,84.09562,0,0,1,128,212Zm29.50391-87.38477-29.51075,39.37891H152a12,12,0,0,1,0,24H104.39648c-.13281.00488-.26464.00684-.39843.00684a12.00272,12.00272,0,0,1-9.47168-19.36914l43.56543-58.13379a12.00426,12.00426,0,1,0-21.1543-11.165A11.9998,11.9998,0,0,1,94.834,89.9834a36.00408,36.00408,0,1,1,63.01172,34.15234C157.73535,124.29883,157.62207,124.458,157.50391,124.61523Z" />
                                    </svg>
                                </div>
                            </th>
                            <th
                                class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                                &nbsp;
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @if ($datas->count() == 0)
                            <tr>
                                <td colspan="10" class="text-sm bg-primary-20 dark:bg-primary-900">
                                    <div class="flex items-center justify-center p-5">@lang('messages.datanotavailable')</div>
                                </td>
                            </tr>
                        @endif

                        @foreach ($datas as $data)
                            <tr>
                                <td
                                    class="text-center px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                    <div>
                                        <input type="checkbox" name="isprint[]" value="{{ $data->id }}"
                                            tabindex="0"
                                            class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-5 h-5 rounded-lg shadow-md">
                                    </div>
                                </td>
                                <td
                                    class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                    <span class="text-gray-900 dark:text-white">{{ $data->no_order }}</span>
                                </td>
                                <td
                                    class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                    <span
                                        class="text-gray-900 dark:text-white">{{ date_format(date_create($data->tanggal), 'd/m/Y') }}</span>
                                </td>
                                <td
                                    class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                    <span class="text-gray-900 dark:text-white">{{ $data->customer->nama }}</span>
                                </td>
                                <td
                                    class="text-right px-3 py-3 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                    <p class="text-gray-900 whitespace-no-wrap dark:text-white">
                                        {{ $data->total_harga ? Number::forHumans($data->total_harga, precision: 2, abbreviate: true) : 0 }}
                                    </p>
                                </td>
                                <td
                                    class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                    <span
                                        class="text-gray-900 dark:text-white">{{ $data->tunai == 1 ? __('messages.cash') : __('messages.credit') }}</span>
                                </td>
                                <td
                                    class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                    <span class="flex items-center justify-center">
                                        @if ($data->isactive == '1')
                                            <span>✔️</span>
                                        @endif
                                        @if ($data->isactive == '0')
                                            <span>❌</span>
                                        @endif
                                    </span>
                                </td>
                                <td
                                    class="text-center px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                    <span>{{ $data->isready == 1 ? '✔️' : '❓' }}</span>
                                </td>
                                <td
                                    class="text-center px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                    <span>{{ $data->ispackaged == 1 ? '✔️' : '❓' }}</span>
                                </td>
                                <td class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800"
                                    style="vertical-align: middle;">
                                    <div class="flex items-center justify-center">
                                        @can('so-create')
                                            <x-anchor-transparent id="print_one-anchor-{{ $data->id }}"
                                                onclick="print_one({{ $data->id }})" title="{{ __('messages.print') }}"
                                                class="ml-2">
                                                <span
                                                    class="relative inline-block px-2 py-2 font-semibold text-blue-800 dark:text-blue-50 leading-tight">
                                                    <span aria-hidden
                                                        class="absolute inset-0 bg-blue-200 hover:bg-blue-400 dark:bg-blue-500 hover:dark:bg-blue-700 opacity-50 rounded-full"></span>
                                                    <svg class="size-4" viewBox="0 0 15 15" fill="none"
                                                        xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M3.5 12.5H1.5C0.947715 12.5 0.5 12.0523 0.5 11.5V7.5C0.5 6.94772 0.947715 6.5 1.5 6.5H13.5C14.0523 6.5 14.5 6.94772 14.5 7.5V11.5C14.5 12.0523 14.0523 12.5 13.5 12.5H11.5M3.5 6.5V1.5C3.5 0.947715 3.94772 0.5 4.5 0.5H10.5C11.0523 0.5 11.5 0.947715 11.5 1.5V6.5M3.5 10.5H11.5V14.5H3.5V10.5Z"
                                                            stroke="currentColor" />
                                                    </svg>
                                                </span>
                                            </x-anchor-transparent>
                                            <svg id="print_one-icon-{{ $data->id }}" class="hidden size-4 m-2"
                                                viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                                                <path
                                                    d="M3.5 12.5H1.5C0.947715 12.5 0.5 12.0523 0.5 11.5V7.5C0.5 6.94772 0.947715 6.5 1.5 6.5H13.5C14.0523 6.5 14.5 6.94772 14.5 7.5V11.5C14.5 12.0523 14.0523 12.5 13.5 12.5H11.5M3.5 6.5V1.5C3.5 0.947715 3.94772 0.5 4.5 0.5H10.5C11.0523 0.5 11.5 0.947715 11.5 1.5V6.5M3.5 10.5H11.5V14.5H3.5V10.5Z"
                                                    stroke="currentColor" />
                                            </svg>
                                        @endcan
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                <div
                    class="px-3 py-3 bg-primary-50 items-center xs:justify-between border-t border-primary-100 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                    <div class="mt-2 xs:mt-0">
                        {{ $datas->links() }}
                    </div>
                </div>
            </div>
        </div>
    </form>

    @can('so-create')
        @if (count($datas) > 0)
            <div class="py-2">
                <div class="flex flex-row flex-wrap lg:flex-nowrap items-center justify-start gap-2 md:gap-4">
                    <x-secondary-button id="print-laporan" tabindex="0"
                        class="bg-indigo-700 hover:bg-indigo-800 dark:bg-indigo-900 hover:dark:bg-indigo-950">
                        <svg id="print-icon" class="size-4" viewBox="0 0 15 15" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M3.5 12.5H1.5C0.947715 12.5 0.5 12.0523 0.5 11.5V7.5C0.5 6.94772 0.947715 6.5 1.5 6.5H13.5C14.0523 6.5 14.5 6.94772 14.5 7.5V11.5C14.5 12.0523 14.0523 12.5 13.5 12.5H11.5M3.5 6.5V1.5C3.5 0.947715 3.94772 0.5 4.5 0.5H10.5C11.0523 0.5 11.5 0.947715 11.5 1.5V6.5M3.5 10.5H11.5V14.5H3.5V10.5Z"
                                stroke="currentColor" />
                        </svg>
                        <span class="pl-1">@lang('messages.print')</span>
                    </x-secondary-button>
                </div>
            </div>
        @endif
    @endcan

    <div
        class="my-2 flex flex-row items-center justify-start shadow-md rounded-md border border-solid border-primary-100 dark:border-primary-800">
        <div class="px-4 py-2 border-r border-primary-100 dark:border-primary-800 bg-primary-50 dark:bg-primary-800">
            <span class="text-sm">@lang('messages.footnote')</span>
        </div>
        <div class="px-4 py-2 flex flex-row flex-wrap gap-6 items-center">
            <div class="flex flex-row gap-2 items-center">
                <svg fill="currentColor" class="size-4" viewBox="0 0 256 256" id="Flat"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M128,20A108,108,0,1,0,236,128,108.12186,108.12186,0,0,0,128,20Zm0,192a84,84,0,1,1,84-84A84.09562,84.09562,0,0,1,128,212ZM144,84v92a12,12,0,0,1-24,0V106.417l-5.3457,3.5625a12.00027,12.00027,0,1,1-13.3086-19.97265l24-15.99317A12.00071,12.00071,0,0,1,144,84Z" />
                </svg>
                <span class="text-sm">@lang('messages.production')</span>
            </div>
            <div class="flex flex-row gap-2 items-center">
                <svg fill="currentColor" class="size-4" viewBox="0 0 256 256" id="Flat"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M128,20A108,108,0,1,0,236,128,108.12186,108.12186,0,0,0,128,20Zm0,192a84,84,0,1,1,84-84A84.09562,84.09562,0,0,1,128,212Zm29.50391-87.38477-29.51075,39.37891H152a12,12,0,0,1,0,24H104.39648c-.13281.00488-.26464.00684-.39843.00684a12.00272,12.00272,0,0,1-9.47168-19.36914l43.56543-58.13379a12.00426,12.00426,0,1,0-21.1543-11.165A11.9998,11.9998,0,0,1,94.834,89.9834a36.00408,36.00408,0,1,1,63.01172,34.15234C157.73535,124.29883,157.62207,124.458,157.50391,124.61523Z" />
                </svg>
                <span class="text-sm">@lang('messages.packaging')</span>
            </div>
        </div>
    </div>

    @can('so-create')
        <div x-show.transition.duration.500ms="openModal"
            class="fixed inset-0 flex items-center justify-center px-4 md:px-0 bg-white bg-opacity-75 dark:bg-black dark:bg-opacity-75">
            <div @click.away="openModal = false"
                class="flex flex-col p-2 h-full w-full shadow-2xl rounded-lg border-1 border-primary-100 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                <div class="flex justify-between mb-2">
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
                <div class="flex items-center justify-center overflow-hidden rounded-lg h-full">
                    <iframe id="iframe-laporan" src="{{ url($documents) }}" frameborder="0"
                        style="width:100%; height:100%;"></iframe>
                </div>
            </div>
        </div>
    @endcan
</div>
