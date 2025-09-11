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
                        @lang('messages.warehouse')
                    </th>
                    <th
                        class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                        @lang('messages.orderdate')
                    </th>
                    <th
                        class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                        @lang('messages.adjustmentdate')
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
                        <td colspan="5" class="text-sm bg-primary-20 dark:bg-primary-900">
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
                            <span class="text-gray-900 dark:text-white">{{ $data->gudang->nama }}</span>
                        </td>
                        <td
                            class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                            <span
                                class="text-gray-900 dark:text-white">{{ date_format(date_create($data->tanggal), 'd/m/Y') }}</span>
                        </td>
                        <td
                            class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                            <span
                                class="text-gray-900 dark:text-white">{{ $data->tanggal_adjustment ? date_format(date_create($data->tanggal_adjustment), 'd/m/Y') : '-' }}</span>
                        </td>
                        <td class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800"
                            style="vertical-align: middle;">
                            <div class="flex items-center justify-center">
                                @can('stopname-show')
                                    <a href="{{ route('stock-adjustment.show', Crypt::Encrypt($data->id)) }}"
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

                                @can('stopname-edit')
                                    @if ($data->approved == 0)
                                        <a href="{{ route('stock-adjustment.edit', Crypt::Encrypt($data->id)) }}"
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
