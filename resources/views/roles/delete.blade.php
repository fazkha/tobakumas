@section('title', __('messages.role'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('roles.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="w-7 h-7" viewBox="0 0 52 52" data-name="Layer 1"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M38.3,27.2A11.4,11.4,0,1,0,49.7,38.6,11.46,11.46,0,0,0,38.3,27.2Zm2,12.4a2.39,2.39,0,0,1-.9-.2l-4.3,4.3a1.39,1.39,0,0,1-.9.4,1,1,0,0,1-.9-.4,1.39,1.39,0,0,1,0-1.9l4.3-4.3a2.92,2.92,0,0,1-.2-.9,3.47,3.47,0,0,1,3.4-3.8,2.39,2.39,0,0,1,.9.2c.2,0,.2.2.1.3l-2,1.9a.28.28,0,0,0,0,.5L41.1,37a.38.38,0,0,0,.6,0l1.9-1.9c.1-.1.4-.1.4.1a3.71,3.71,0,0,1,.2.9A3.57,3.57,0,0,1,40.3,39.6Z" />
                    <circle cx="21.7" cy="14.9" r="12.9" />
                    <path
                        d="M25.2,49.8c2.2,0,1-1.5,1-1.5h0a15.44,15.44,0,0,1-3.4-9.7,15,15,0,0,1,1.4-6.4.77.77,0,0,1,.2-.3c.7-1.4-.7-1.5-.7-1.5h0a12.1,12.1,0,0,0-1.9-.1A19.69,19.69,0,0,0,2.4,47.1c0,1,.3,2.8,3.4,2.8H24.9C25.1,49.8,25.1,49.8,25.2,49.8Z" />
                </svg>
                <span class="px-2">@lang('messages.role')</span>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.delete')</span>
        </h1>
    </div>

    <div class="py-4 flex flex-col">
        <div class="container mx-auto px-2 sm:px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-1/2 shadow mb-5" role="alert">
                    <form action="{{ route('roles.destroy', Crypt::Encrypt($datas->id)) }}" class="block"
                        method="POST" enctype="multipart/form-data">
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
                                    <div class="flex justify-between">
                                        <x-primary-button type="submit"
                                            class="bg-red-600 hover:bg-red-700 dark:bg-red-700 dark:hover:bg-red-800">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.delete')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('roles.index') }}" tabindex="1" autofocus>
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.cancel')</span>
                                        </x-anchor-secondary>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>

                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-6 space-y-2 md:space-y-2 sm:p-8">
                        <div class="form-group" style="margin-top: 0 !important">
                            <label for="name"
                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.rolename')</label>
                            <x-text-span name="name" id="name">{{ $datas->name }}</x-text-span>
                        </div>

                        <div class="form-group">
                            <label for="permissions"
                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.permission')</label>
                            <x-text-span>
                                @foreach ($permissions as $permission)
                                    <div class="pb-2">
                                        <input disabled type="checkbox" id="permissions[{{ $permission->name }}]"
                                            name="permissions[{{ $permission->name }}]" value="{{ $permission->name }}"
                                            {{ $datas->hasPermissionTo($permission->name) ? 'checked' : '' }} />
                                        <label for="permissions[{{ $permission->name }}]"
                                            class="pl-2">{{ $permission->name }}</label>
                                    </div>
                                @endforeach
                            </x-text-span>
                        </div>

                        <div class="flex justify-end pt-4">
                            <x-anchor-secondary href="{{ route('roles.index') }}" tabindex="2">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                    stroke-width="1.5" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                </svg>
                                <span class="pl-1">@lang('messages.close')</span>
                            </x-anchor-secondary>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
</x-app-layout>
