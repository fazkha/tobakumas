@section('title', __('messages.user'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('users.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="w-7 h-7" viewBox="-2 -1.5 24 24" xmlns="http://www.w3.org/2000/svg"
                    preserveAspectRatio="xMinYMin" class="jam jam-users">
                    <path
                        d='M3.534 11.07a1 1 0 1 1 .733 1.86A3.579 3.579 0 0 0 2 16.26V18a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1.647a3.658 3.658 0 0 0-2.356-3.419 1 1 0 1 1 .712-1.868A5.658 5.658 0 0 1 14 16.353V18a3 3 0 0 1-3 3H3a3 3 0 0 1-3-3v-1.74a5.579 5.579 0 0 1 3.534-5.19zM7 1a4 4 0 0 1 4 4v2a4 4 0 1 1-8 0V5a4 4 0 0 1 4-4zm0 2a2 2 0 0 0-2 2v2a2 2 0 1 0 4 0V5a2 2 0 0 0-2-2zm9 17a1 1 0 0 1 0-2h1a1 1 0 0 0 1-1v-1.838a3.387 3.387 0 0 0-2.316-3.213 1 1 0 1 1 .632-1.898A5.387 5.387 0 0 1 20 15.162V17a3 3 0 0 1-3 3h-1zM13 2a1 1 0 0 1 0-2 4 4 0 0 1 4 4v2a4 4 0 0 1-4 4 1 1 0 0 1 0-2 2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z' />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.setting')</span>
                    <span>@lang('messages.user')</span>
                </div>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.view')</span>
        </h1>
    </div>

    <div class="py-2 flex flex-col">
        <div class="container mx-auto px-2 sm:px-4 py-2">
            <div class="flex flex-col items-center">

                <div class="w-full" role="alert">
                    @include('users.partials.feedback')
                </div>

                <div
                    class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                    <div class="p-6 space-y-2 md:space-y-2 sm:p-8">
                        <div class="form-group">
                            <label for="name"
                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.username')</label>
                            <x-text-span>{{ $datas->name }}</x-text-span>
                        </div>

                        <div class="form-group">
                            <label for="email"
                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.emailaddress')</label>
                            <x-text-span>{{ $datas->email }}</x-text-span>
                        </div>

                        {{-- <div class="form-group">
                            <label for="password"
                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Password</label>
                            <x-text-span type="password" name="password"
                                id="password">{{ $datas->password }}</x-text-span>
                        </div> --}}

                        <div class="form-group">
                            <label for="approved"
                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.approval')</label>
                            <x-text-span>
                                <div class="px-2 pb-2">
                                    <div class="inline-flex items-center">
                                        @if ($datas->approved == '1')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5 text-green-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="m4.5 12.75 6 6 9-13.5" />
                                            </svg>
                                        @endif
                                        @if ($datas->approved == '0')
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5 text-red-600">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M18.364 18.364A9 9 0 0 0 5.636 5.636m12.728 12.728A9 9 0 0 1 5.636 5.636m12.728 12.728L5.636 5.636" />
                                            </svg>
                                        @endif
                                        <label class="pl-2">@lang('messages.approved')</label>
                                    </div>
                                </div>

                                <div class="px-2 pb-2">
                                    <label for="branch_id"
                                        class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.branch')</label>
                                    <x-text-span>{{ $datas->profile->branch->nama }}</x-text-span>
                                </div>
                            </x-text-span>
                        </div>

                        <div class="form-group">
                            <label for="roles"
                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.role')</label>
                            <x-text-span>
                                @foreach ($roles as $role)
                                    <div class="pb-2">
                                        <input disabled type="checkbox" id="roles[{{ $role }}]"
                                            name="roles[{{ $role }}]" value="{{ $role }}" checked />
                                        <label for="roles[{{ $role }}]"
                                            class="pl-2">{{ $role }}</label>
                                    </div>
                                @endforeach
                            </x-text-span>
                        </div>

                        <div class="flex justify-end pt-4">
                            <x-anchor-secondary href="{{ route('users.index') }}" tabindex="1" autofocus>
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
