<div class="my-2">
    <div class="flex flex-col-reverse md:flex-row gap-4 justify-between">
        <div
            class="p-2 md:p-4 border rounded-md bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">

            <div class="relative flex flex-row gap-2 mb-2 md:mb-4">
                <div id="filter-loading" class="absolute top-[0%] right-[2%] z-10 hidden">
                    <svg class="size-5 animate-spin" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <rect width="48" height="48" fill="white" fill-opacity="0.0" />
                        <path
                            d="M4 24C4 35.0457 12.9543 44 24 44V44C35.0457 44 44 35.0457 44 24C44 12.9543 35.0457 4 24 4"
                            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M36 24C36 17.3726 30.6274 12 24 12C17.3726 12 12 17.3726 12 24C12 30.6274 17.3726 36 24 36V36"
                            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>

                <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M21.71,20.29,18,16.61A9,9,0,1,0,16.61,18l3.68,3.68a1,1,0,0,0,1.42,0A1,1,0,0,0,21.71,20.29ZM11,18a7,7,0,1,1,7-7A7,7,0,0,1,11,18Z" />
                </svg>
                <span>@lang('messages.searchpanel')</span>
            </div>

            <div class="flex flex-col lg:flex-row justify-start">
                <div class="flex-row justify-start">
                    <div class="relative shadow-md mr-2 mb-2">
                        <span
                            class="w-24 text-xs h-full absolute inset-y-0 left-0 flex items-center px-2 border-r border-primary-100 dark:border-primary-800">Per
                            @lang('messages.page')</span>
                        <select id="pp-dropdown"
                            class="text-sm px-2 leading-tight pl-28 pr-9 py-2 appearance-none w-full h-full rounded-md border block bg-primary-20 border-primary-100 text-gray-700 dark:text-white dark:bg-primary-700 dark:border-primary-800">
                            <option {{ session('purchase-receipt_pp') == 12 ? 'selected' : '' }} value="12">12
                            </option>
                            <option {{ session('purchase-receipt_pp') == 24 ? 'selected' : '' }} value="24">24
                            </option>
                            <option {{ session('purchase-receipt_pp') == 36 ? 'selected' : '' }} value="36">36
                            </option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="relative shadow-md mr-2 mb-2">
                        <span
                            class="w-24 text-xs h-full absolute inset-y-0 left-0 flex items-center px-2 border-r border-primary-100 dark:border-primary-800">@lang('messages.active')</span>
                        <select id="isactive-dropdown"
                            class="text-sm px-2 leading-tight pl-28 pr-9 py-2 appearance-none w-full h-full rounded-md border block bg-primary-20 border-primary-100 text-gray-700 dark:text-white dark:bg-primary-700 dark:border-primary-800">
                            <option {{ session('purchase-receipt_isactive') == 'all' ? 'selected' : '' }}
                                value="all">
                                @lang('messages.all')</option>
                            <option {{ session('purchase-receipt_isactive') == '1' ? 'selected' : '' }} value="1">
                                @lang('messages.yes')</option>
                            <option {{ session('purchase-receipt_isactive') == '0' ? 'selected' : '' }} value="0">
                                @lang('messages.no')</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="relative shadow-md mr-2 mb-2">
                        <span
                            class="w-24 text-xs h-full absolute inset-y-0 left-0 flex items-center px-2 border-r border-primary-100 dark:border-primary-800">@lang('messages.payment')</span>
                        <select id="tunai-dropdown"
                            class="text-sm px-2 leading-tight pl-28 pr-9 py-2 appearance-none w-full h-full rounded-md border block bg-primary-20 border-primary-100 text-gray-700 dark:text-white dark:bg-primary-700 dark:border-primary-800">
                            <option {{ session('purchase-receipt_tunai') == 'all' ? 'selected' : '' }} value="all">
                                @lang('messages.all')</option>
                            <option {{ session('purchase-receipt_tunai') == '1' ? 'selected' : '' }} value="1">
                                @lang('messages.cash')</option>
                            <option {{ session('purchase-receipt_tunai') == '2' ? 'selected' : '' }} value="2">
                                @lang('messages.credit')</option>
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-white">
                        </div>
                    </div>
                </div>

                <div class="flex-row justify-start">
                    <div class="relative shadow-md mr-2 mb-2">
                        <span
                            class="w-24 text-xs h-full absolute inset-y-0 left-0 flex items-center px-2 border-r border-primary-100 dark:border-primary-800">@lang('messages.supplier')</span>
                        <select id="supplier-dropdown"
                            class="text-sm px-2 leading-tight pl-28 pr-9 py-2 appearance-none w-full h-full rounded-md border block bg-primary-20 border-primary-100 text-gray-700 dark:text-white dark:bg-primary-700 dark:border-primary-800">
                            <option {{ session('purchase-receipt_supplier_id') == 'all' ? 'selected' : '' }}
                                value="all">
                                @lang('messages.all')
                            </option>
                            @foreach ($suppliers as $id => $name)
                                <option {{ session('purchase-receipt_supplier_id') == $id ? 'selected' : '' }}
                                    value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                        <div
                            class="pointer-events-none absolute inset-y-0 right-0 flex items-center px-2 text-gray-700 dark:text-white">
                        </div>
                    </div>

                    <div class="relative shadow-md mr-2 mb-2">
                        <span
                            class="w-24 text-xs h-full absolute inset-y-0 left-0 flex items-center px-2 border-r border-primary-100 dark:border-primary-800">@lang('messages.ordernumber')</span>
                        <input id="search-no_order" placeholder="@lang('messages.search')"
                            value="{{ session('purchase-receipt_no_order') == '_' ? '' : session('purchase-receipt_no_order') }}"
                            class="text-sm pl-28 pr-6 pt-1.5 pb-2 appearance-none rounded-md border block w-full bg-primary-20 border-primary-100 placeholder-gray-400 text-gray-700 dark:text-white dark:bg-primary-700 dark:border-primary-800" />
                    </div>

                    <div class="relative shadow-md mr-2 mb-2">
                        <span
                            class="w-24 text-xs h-full absolute inset-y-0 left-0 flex items-center px-2 border-r border-primary-100 dark:border-primary-800">@lang('calendar.date')</span>
                        <input id="search-tanggal" type="date" placeholder="@lang('messages.search')"
                            value="{{ session('purchase-receipt_tanggal') == '_' ? '' : session('purchase-receipt_tanggal') }}"
                            class="text-sm pl-28 pr-6 pt-1.5 pb-2 appearance-none rounded-md border block w-full bg-primary-20 border-primary-100 placeholder-gray-400 text-gray-700 dark:text-white dark:bg-primary-700 dark:border-primary-800" />
                    </div>
                </div>
            </div>

        </div>

        <div>
            @can('purchasereceipt-create')
                <x-anchor-primary href="{{ route('purchase-receipt.index') }}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5"
                        stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span class="pl-1">@lang('messages.new')</span>
                </x-anchor-primary>
            @endcan
        </div>
    </div>
</div>
