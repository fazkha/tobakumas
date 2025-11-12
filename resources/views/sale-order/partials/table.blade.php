@php
    use Illuminate\Support\Facades\Crypt;
@endphp

<div class="w-full overflow-x-auto">
    <div
        class="inline-block min-w-full shadow-md overflow-hidden rounded-md border border-solid border-primary-100 dark:border-primary-800">
        <table class="min-w-full leading-normal">
            <thead>
                <tr>
                    <th
                        class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                        #
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
                        @lang('messages.totalprice') (@lang('messages.currencysymbol'))
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
                            class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                            <p class="text-center text-gray-900 whitespace-no-wrap dark:text-white">
                                {{ ++$i }}
                            </p>
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
                                @can('so-approval')
                                    <a href="{{ route('sale-order.approval', Crypt::Encrypt($data->id)) }}"
                                        title="{{ __('messages.approval') }}">
                                        <span
                                            class="relative inline-block px-2 py-2 font-semibold text-violet-800 dark:text-violet-50 leading-tight">
                                            <span aria-hidden
                                                class="absolute inset-0 bg-violet-200 hover:bg-violet-400 dark:bg-violet-500 hover:dark:bg-violet-700 opacity-50 rounded-full"></span>
                                            <svg class="size-5" viewBox="0 0 32 32" enable-background="new 0 0 32 32"
                                                id="Editable-line" version="1.1" xml:space="preserve"
                                                xmlns="http://www.w3.org/2000/svg"
                                                xmlns:xlink="http://www.w3.org/1999/xlink">
                                                <path
                                                    d="  M26.002,13H20V7.026C20,5.907,19.093,5,17.974,5c-0.615,0-1.198,0.28-1.582,0.76L9,15l0.001,0L9,15v10l3,2h12.473  c0.892,0,1.676-0.592,1.921-1.451l2.49-8.725C29.43,14.908,27.993,13,26.002,13z"
                                                    fill="none" id="XMLID_5_" stroke="currentColor"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                                    stroke-width="2" />
                                                <rect fill="none" height="14" id="XMLID_3_" stroke="currentColor"
                                                    stroke-linecap="round" stroke-linejoin="round" stroke-miterlimit="10"
                                                    stroke-width="2" width="6" x="3" y="13" />
                                                <circle cx="6" cy="23" id="XMLID_4_" r="1" />
                                            </svg>
                                        </span>
                                    </a>
                                @endcan

                                @can('so-show')
                                    <a href="{{ route('sale-order.show', Crypt::Encrypt($data->id)) }}"
                                        title="{{ __('messages.view') }}" class="ml-2">
                                        <span
                                            class="relative inline-block px-2 py-2 font-semibold text-blue-800 dark:text-blue-50 leading-tight">
                                            <span aria-hidden
                                                class="absolute inset-0 bg-blue-200 hover:bg-blue-400 dark:bg-blue-500 hover:dark:bg-blue-700 opacity-50 rounded-full"></span>
                                            <svg class="size-5" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"
                                                fill="currentColor">
                                                <path fill-rule="evenodd" clip-rule="evenodd"
                                                    d="M1 10c0-3.9 3.1-7 7-7s7 3.1 7 7h-1c0-3.3-2.7-6-6-6s-6 2.7-6 6H1zm4 0c0-1.7 1.3-3 3-3s3 1.3 3 3-1.3 3-3 3-3-1.3-3-3zm1 0c0 1.1.9 2 2 2s2-.9 2-2-.9-2-2-2-2 .9-2 2z" />
                                            </svg>
                                        </span>
                                    </a>
                                @endcan

                                @can('so-edit')
                                    @if ($data->ispackaged == 0)
                                        <a href="{{ route('sale-order.edit', Crypt::Encrypt($data->id)) }}"
                                            title="{{ __('messages.edit') }}" class="ml-2">
                                            <span
                                                class="relative inline-block px-2 py-2 font-semibold text-green-800 dark:text-green-50 leading-tight">
                                                <span aria-hidden
                                                    class="absolute inset-0 bg-green-200 hover:bg-green-400 dark:bg-green-500 hover:dark:bg-green-700 opacity-50 rounded-full"></span>
                                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                </svg>
                                            </span>
                                        </a>
                                    @endif
                                @endcan

                                @can('so-delete')
                                    @if ($data->ispackaged == 0)
                                        <a href="{{ route('sale-order.delete', Crypt::Encrypt($data->id)) }}"
                                            title="{{ __('messages.delete') }}" class="ml-2">
                                            <span
                                                class="relative inline-block px-2 py-2 font-semibold text-red-800 dark:text-red-50 leading-tight">
                                                <span aria-hidden
                                                    class="absolute inset-0 bg-red-200 hover:bg-red-400 dark:bg-red-500 hover:dark:bg-red-700 opacity-50 rounded-full"></span>
                                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </span>
                                        </a>
                                    @endif
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

<div
    class="flex flex-row items-center justify-start shadow-md rounded-md border border-solid border-primary-100 dark:border-primary-800">
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
            <span class="text-sm">@lang('messages.delivery')</span>
        </div>

    </div>
</div>
