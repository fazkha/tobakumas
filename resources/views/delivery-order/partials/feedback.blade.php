<div class="flex items-center justify-center">
    @if (session('success'))
        <div class="flex w-full shadow my-5">
            <div class="bg-primary w-16 text-center p-2">
                <div class="flex justify-center h-full items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>
            <div class="bg-white w-full p-4 border-r-4 border-primary">
                <div>
                    <p class="text-gray-600 font-bold">@lang('messages.success')</p>
                    <p class="text-gray-600 text-sm">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    @if (session('error'))
        <div class="flex w-full shadow my-5">
            <div class="bg-red-600 w-16 text-center p-2">
                <div class="flex justify-center h-full items-center">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none"
                        viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                    </svg>
                </div>
            </div>
            <div class="bg-white w-full p-4 border-r-4 border-red-600">
                <div>
                    <p class="text-gray-600 font-bold">@lang('messages.fail')</p>
                    <p class="text-gray-600 text-sm">{{ session('error') }}</p>
                </div>
            </div>
        </div>
    @endif
</div>
