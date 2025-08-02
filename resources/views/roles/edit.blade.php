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
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <div class="py-4 flex flex-col">
        <div class="container mx-auto px-2 sm:px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('roles.partials.feedback')
                </div>

                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-6 space-y-2 md:space-y-2 sm:p-8">
                        <form action="{{ route('roles.update', Crypt::Encrypt($datas->id)) }}"
                            class="space-y-4 md:space-y-6" method="POST" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')

                            <div class="form-group" style="margin-top: 0 !important">
                                <label for="name"
                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.rolename')</label>
                                <x-text-input type="text" name="name" id="name"
                                    placeholder="{{ __('messages.enter') }} {{ __('messages.rolename') }}" required
                                    value="{{ old('name', $datas->name) }}" />

                                <x-input-error class="mt-2" :messages="$errors->get('name')" />
                            </div>

                            <div class="form-group">
                                <label for="permissions"
                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.permission')</label>
                                <x-text-span>
                                    @foreach ($permissions as $permission)
                                        <div class="pb-2">
                                            <input type="checkbox" id="permissions[{{ $permission->name }}]"
                                                name="permissions[{{ $permission->name }}]"
                                                value="{{ $permission->name }}"
                                                {{ $datas->hasPermissionTo($permission->name) ? 'checked' : '' }} />
                                            <label for="permissions[{{ $permission->name }}]"
                                                class="pl-2">{{ $permission->name }}</label>
                                        </div>
                                    @endforeach
                                </x-text-span>
                            </div>

                            <div class="flex justify-between">
                                <x-primary-button type="submit" class="block">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                    </svg>
                                    <span class="pl-1">@lang('messages.save')</span>
                                </x-primary-button>
                                <x-anchor-secondary href="{{ route('roles.index') }}">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                        stroke-width="1.5" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
                                    </svg>
                                    <span class="pl-1">@lang('messages.close')</span>
                                </x-anchor-secondary>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
    @endpush
</x-app-layout>
