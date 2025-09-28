@php
    $notifs = notif_data();
    dd($notif);
@endphp

<!-- Panels -->

<!-- Settings Panel -->
<!-- Backdrop -->
<div x-transition:enter="transition duration-300 ease-in-out" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition duration-300 ease-in-out"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-show="isSettingsPanelOpen"
    @click="isSettingsPanelOpen = false" class="fixed inset-0 z-10 bg-primary-700" style="opacity: 0.5" aria-hidden="true">
</div>
<!-- Panel -->
<section x-transition:enter="transition duration-300 ease-in-out transform sm:duration-500"
    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition duration-300 ease-in-out transform sm:duration-500"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="translate-x-full" x-ref="settingsPanel"
    tabindex="-1" x-show="isSettingsPanelOpen" @keydown.escape="isSettingsPanelOpen = false"
    class="fixed inset-y-0 right-0 z-20 w-full max-w-xs bg-primary-20 shadow-xl dark:bg-primary-900 dark:text-light sm:max-w-md focus:outline-none"
    aria-labelledby="settinsPanelLabel">
    <div class="absolute left-0 p-2 transform -translate-x-full">
        <!-- Close button -->
        <button @click="isSettingsPanelOpen = false" class="p-2 text-white rounded-md focus:outline-none focus:ring">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <!-- Panel content -->
    <div class="flex flex-col h-screen">
        <!-- Panel header -->
        <div
            class="flex flex-col items-center justify-center flex-shrink-0 px-4 py-8 space-y-4 border-b border-primary-100 dark:border-primary-600">
            <span aria-hidden="true" class="text-gray-500 dark:text-primary">
                <svg class="w-8 h-8" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4" />
                </svg>
            </span>
            <h2 id="settinsPanelLabel" class="text-xl font-medium text-gray-500 dark:text-light">@lang('messages.setting')</h2>
        </div>
        <!-- Content -->
        <div class="flex-1 overflow-hidden hover:overflow-y-auto">
            <!-- Theme -->
            <div class="p-4 space-y-1 md:p-8">
                <h6 class="text-lg font-medium text-gray-400 dark:text-light">@lang('messages.theme')</h6>
                <div class="flex items-center space-x-8">
                    <!-- Light button -->
                    <button @click="setLightTheme"
                        class="flex items-center justify-center px-4 py-2 space-x-4 transition-colors border rounded-md hover:text-gray-900 hover:border-gray-900 dark:border-primary dark:hover:text-primary-100 dark:hover:border-primary-500 focus:outline-none focus:ring focus:ring-primary-400 focus:ring-offset-2 dark:focus:ring-offset-dark"
                        :class="{
                            'text-gray-500 dark:border-primary-800 dark:text-primary-100': isDark,
                            'border-gray-900 text-gray-900 dark:text-primary-500': !isDark
                        }">
                        <span>
                            <svg fill="currentColor" class="w-6 h-6" viewBox="-2 -2 24 24"
                                xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMin" class="jam jam-sun">
                                <path
                                    d='M10 13a3 3 0 1 0 0-6 3 3 0 0 0 0 6zm0 2a5 5 0 1 1 0-10 5 5 0 0 1 0 10zm0-15a1 1 0 0 1 1 1v2a1 1 0 0 1-2 0V1a1 1 0 0 1 1-1zm0 16a1 1 0 0 1 1 1v2a1 1 0 0 1-2 0v-2a1 1 0 0 1 1-1zM1 9h2a1 1 0 1 1 0 2H1a1 1 0 0 1 0-2zm16 0h2a1 1 0 0 1 0 2h-2a1 1 0 0 1 0-2zm.071-6.071a1 1 0 0 1 0 1.414l-1.414 1.414a1 1 0 1 1-1.414-1.414l1.414-1.414a1 1 0 0 1 1.414 0zM5.757 14.243a1 1 0 0 1 0 1.414L4.343 17.07a1 1 0 1 1-1.414-1.414l1.414-1.414a1 1 0 0 1 1.414 0zM4.343 2.929l1.414 1.414a1 1 0 0 1-1.414 1.414L2.93 4.343A1 1 0 0 1 4.343 2.93zm11.314 11.314l1.414 1.414a1 1 0 0 1-1.414 1.414l-1.414-1.414a1 1 0 1 1 1.414-1.414z' />
                            </svg>
                        </span>
                        <span>@lang('messages.light')</span>
                    </button>

                    <!-- Dark button -->
                    <button @click="setDarkTheme"
                        class="flex items-center justify-center px-4 py-2 space-x-4 transition-colors border rounded-md hover:text-gray-900 hover:border-gray-900 dark:border-primary dark:hover:text-primary-100 dark:hover:border-primary-500 focus:outline-none focus:ring focus:ring-primary-400 focus:ring-offset-2 dark:focus:ring-offset-dark"
                        :class="{
                            'text-gray-500 dark:border-primary-800 dark:text-primary-100': !isDark,
                            'border-gray-900 text-gray-900 dark:text-primary-500': isDark
                        }">
                        <span>
                            <svg fill="currentColor" class="w-6 h-6" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M21.64,13a1,1,0,0,0-1.05-.14,8.05,8.05,0,0,1-3.37.73A8.15,8.15,0,0,1,9.08,5.49a8.59,8.59,0,0,1,.25-2A1,1,0,0,0,8,2.36,10.14,10.14,0,1,0,22,14.05,1,1,0,0,0,21.64,13Zm-9.5,6.69A8.14,8.14,0,0,1,7.08,5.22v.27A10.15,10.15,0,0,0,17.22,15.63a9.79,9.79,0,0,0,2.1-.22A8.11,8.11,0,0,1,12.14,19.73Z" />
                            </svg>
                        </span>
                        <span>@lang('messages.dark')</span>
                    </button>
                </div>
            </div>

            <!-- Colors -->
            <div class="p-4 space-y-1 md:p-8">
                <h6 class="text-lg font-medium text-gray-400 dark:text-light">@lang('messages.colors')</h6>
                <div class="flex flex-row flex-wrap gap-1">
                    <button @click="setColors('red')" class="w-10 h-10 rounded-full"
                        style="background-color: var(--color-red)"></button>
                    <button @click="setColors('orange')" class="w-10 h-10 rounded-full"
                        style="background-color: var(--color-orange)"></button>
                    <button @click="setColors('yellow')" class="w-10 h-10 rounded-full"
                        style="background-color: var(--color-yellow)"></button>
                    <button @click="setColors('green')" class="w-10 h-10 rounded-full"
                        style="background-color: var(--color-green)"></button>
                    <button @click="setColors('cyan')" class="w-10 h-10 rounded-full"
                        style="background-color: var(--color-cyan)"></button>
                    <button @click="setColors('teal')" class="w-10 h-10 rounded-full"
                        style="background-color: var(--color-teal)"></button>
                    <button @click="setColors('blue')" class="w-10 h-10 rounded-full"
                        style="background-color: var(--color-blue)"></button>
                    <button @click="setColors('fuchsia')" class="w-10 h-10 rounded-full"
                        style="background-color: var(--color-fuchsia)"></button>
                    <button @click="setColors('violet')" class="w-10 h-10 rounded-full"
                        style="background-color: var(--color-violet)"></button>
                    <button @click="setColors('maroon')" class="w-10 h-10 rounded-full"
                        style="background-color: var(--color-maroon)"></button>
                </div>
            </div>

            <!-- Language -->
            <div class="p-4 space-y-1 md:p-8">
                <h6 class="text-lg font-medium text-gray-400 dark:text-light">@lang('messages.language')</h6>
                <div class="flex items-center space-x-8">
                    <!-- English button -->
                    <button @click="setEnLang"
                        class="flex items-center justify-center px-4 py-2 space-x-4 transition-colors border rounded-md hover:text-gray-900 hover:border-gray-900 dark:border-primary dark:hover:text-primary-100 dark:hover:border-primary-500 focus:outline-none focus:ring focus:ring-primary-400 focus:ring-offset-2 dark:focus:ring-offset-dark"
                        :class="{
                            'text-gray-500 dark:border-primary-800 dark:text-primary-100': !isEnLang,
                            'border-gray-900 text-gray-900 dark:text-primary-500': isEnLang
                        }">
                        <span>
                            <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 50 30">
                                <clipPath id="t">
                                    <path d="M25,15h25v15zv15h-25zh-25v-15zv-15h25z" />
                                </clipPath>
                                <path d="M0,0v30h50v-30z" fill="#012169" />
                                <path d="M0,0 50,30M50,0 0,30" stroke="#fff" stroke-width="6" />
                                <path d="M0,0 50,30M50,0 0,30" clip-path="url(#t)" stroke="#C8102E"
                                    stroke-width="4" />
                                <path d="M-1 11h22v-12h8v12h22v8h-22v12h-8v-12h-22z" fill="#C8102E" stroke="#FFF"
                                    stroke-width="2" />
                            </svg>
                        </span>
                        <span>@lang('messages.english')</span>
                    </button>

                    <!-- Indonesia button -->
                    <button @click="setIdLang"
                        class="flex items-center justify-center px-4 py-2 space-x-4 transition-colors border rounded-md hover:text-gray-900 hover:border-gray-900 dark:border-primary dark:hover:text-primary-100 dark:hover:border-primary-500 focus:outline-none focus:ring focus:ring-primary-400 focus:ring-offset-2 dark:focus:ring-offset-dark"
                        :class="{
                            'text-gray-500 dark:border-primary-800 dark:text-primary-100': isEnLang,
                            'border-gray-900 text-gray-900 dark:text-primary-500': !isEnLang
                        }">
                        <span>
                            <svg class="w-6 h-6" width="30" height="20" xmlns="http://www.w3.org/2000/svg">
                                <path fill="#fff" d="M0 0H30V20H0z" />
                                <path fill="red" d="M0 0H30V10H0z" />
                            </svg>
                        </span>
                        <span>Indonesia</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Notification panel -->
<!-- Backdrop -->
<div x-transition:enter="transition duration-300 ease-in-out" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition duration-300 ease-in-out"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-show="isNotificationsPanelOpen"
    @click="isNotificationsPanelOpen = false" class="fixed inset-0 z-10 bg-primary-700" style="opacity: 0.5"
    aria-hidden="true"></div>
<!-- Panel -->
<section x-transition:enter="transition duration-300 ease-in-out transform sm:duration-500"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition duration-300 ease-in-out transform sm:duration-500"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" x-ref="notificationsPanel"
    x-show="isNotificationsPanelOpen" @keydown.escape="isNotificationsPanelOpen = false" tabindex="-1"
    aria-labelledby="notificationPanelLabel"
    class="fixed inset-y-0 z-20 w-full max-w-xs bg-primary-20 dark:bg-primary-900 dark:text-light sm:max-w-md focus:outline-none">
    <div class="absolute right-0 p-2 transform translate-x-full">
        <!-- Close button -->
        <button @click="isNotificationsPanelOpen = false"
            class="p-2 text-white rounded-md focus:outline-none focus:ring">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>
    <div class="flex flex-col h-screen" x-data="{ activeTabe: 'action' }">
        <!-- Panel header -->
        <div class="flex-shrink-0">
            <div
                class="flex items-center justify-between px-4 pt-4 border-b border-primary-100 dark:border-primary-800">
                <h2 id="notificationPanelLabel" class="pb-4 font-semibold">Notifications</h2>
                <div class="space-x-2">
                    <button @click.prevent="activeTabe = 'action'"
                        class="px-px pb-4 transition-all duration-200 transform translate-y-px border-b border-primary-100 focus:outline-none"
                        :class="{
                            'border-primary-600 dark:border-primary': activeTabe ==
                                'action',
                            'border-transparent': activeTabe != 'action'
                        }">
                        Action
                    </button>
                    <button @click.prevent="activeTabe = 'user'"
                        class="px-px pb-4 transition-all duration-200 transform translate-y-px border-b border-primary-100 focus:outline-none"
                        :class="{
                            'border-primary-600 dark:border-primary': activeTabe ==
                                'user',
                            'border-transparent': activeTabe != 'user'
                        }">
                        User
                    </button>
                </div>
            </div>
        </div>

        <!-- Panel content (tabs) -->
        <div class="flex-1 pt-4 overflow-y-hidden hover:overflow-y-auto">
            <!-- Action tab -->
            <div class="space-y-4" x-show.transition.in="activeTabe == 'action'">
                @if (count($notifs) > 0)
                    @foreach ($notifs as $notif)
                        @php
                            $currentDateTime = new DateTime()->format('Y-m-d H:i:s');
                            $et = elapsed_interval($notif->tanggal_awal, $currentDateTime);
                        @endphp
                        <a href="{{ route($notif->route) }}" class="block">
                            <div class="flex px-4 space-x-4">
                                <div class="relative flex-shrink-0">
                                    <span
                                        class="inline-block p-2 overflow-visible rounded-full bg-primary-50 text-primary-500 dark:bg-primary-700">
                                        <svg class="w-7 h-7" xmlns="http://www.w3.org/2000/svg" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                        </svg>
                                    </span>
                                    <div
                                        class="absolute h-24 p-px -mt-3 -ml-px bg-primary-50 left-1/2 dark:bg-primary-700">
                                    </div>
                                </div>
                                <div class="flex-1 overflow-hidden">
                                    <h5 class="text-sm font-semibold text-primary dark:text-primary-500">
                                        {{ $notif->title }}
                                    </h5>
                                    <p class="text-sm font-normal text-primary-500 dark:text-primary-600">
                                        {{ $notif->message }}
                                    </p>
                                    <span class="text-sm font-normal text-primary-400 dark:text-primary-700">
                                        {{ $et }} ago
                                    </span>
                                </div>
                            </div>
                        </a>
                    @endforeach
                @endif
            </div>

            <!-- User tab -->
            <div class="space-y-4" x-show.transition.in="activeTabe == 'user'">
                {{-- <template x-for="i in 10" x-key="i">
                    <a href="#" class="block">
                        <div class="flex px-4 space-x-4">
                            <div class="relative flex-shrink-0">
                                <span
                                    class="inline-block p-2 overflow-visible rounded-full bg-primary-50 text-primary-500 dark:bg-primary-700">
                                    <svg fill="currentColor" class="size-7" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M15.71,12.71a6,6,0,1,0-7.42,0,10,10,0,0,0-6.22,8.18,1,1,0,0,0,2,.22,8,8,0,0,1,15.9,0,1,1,0,0,0,1,.89h.11a1,1,0,0,0,.88-1.1A10,10,0,0,0,15.71,12.71ZM12,12a4,4,0,1,1,4-4A4,4,0,0,1,12,12Z" />
                                    </svg>
                                </span>
                                <div
                                    class="absolute h-24 p-px -mt-3 -ml-px bg-primary-50 left-1/2 dark:bg-primary-700">
                                </div>
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <h5 class="text-sm font-semibold text-gray-600 dark:text-light">User
                                </h5>
                                <p class="text-sm font-normal text-gray-400 truncate dark:text-primary-400">
                                    Release new version "Dashboard"
                                </p>
                                <span class="text-sm font-normal text-gray-400 dark:text-primary-500"> 20d
                                    ago </span>
                            </div>
                        </div>
                    </a>
                </template> --}}
            </div>
        </div>
    </div>
</section>

<!-- Search panel -->
<!-- Backdrop -->
<div x-transition:enter="transition duration-300 ease-in-out" x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100" x-transition:leave="transition duration-300 ease-in-out"
    x-transition:leave-start="opacity-100" x-transition:leave-end="opacity-0" x-show="isSearchPanelOpen"
    @click="isSearchPanelOpen = false" class="fixed inset-0 z-10 bg-primary-700" style="opacity: 0.5"
    aria-hidden="ture"></div>
<!-- Panel -->
<section x-transition:enter="transition duration-300 ease-in-out transform sm:duration-500"
    x-transition:enter-start="-translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition duration-300 ease-in-out transform sm:duration-500"
    x-transition:leave-start="translate-x-0" x-transition:leave-end="-translate-x-full" x-show="isSearchPanelOpen"
    @keydown.escape="isSearchPanelOpen = false"
    class="fixed inset-y-0 z-20 w-full max-w-xs bg-primary-20 shadow-xl dark:bg-primary-900 dark:text-light sm:max-w-md focus:outline-none">
    <div class="absolute right-0 p-2 transform translate-x-full">
        <!-- Close button -->
        <button @click="isSearchPanelOpen = false" class="p-2 text-white rounded-md focus:outline-none focus:ring">
            <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
            </svg>
        </button>
    </div>

    <h2 class="sr-only">Search panel</h2>
    <!-- Panel content -->
    <div class="flex flex-col h-screen">
        <!-- Panel header (Search input) -->
        <div
            class="relative flex-shrink-0 px-4 py-8 text-gray-400 border-b border-primary-100 dark:border-primary-800 dark:focus-within:text-light focus-within:text-gray-700">
            <span class="absolute inset-y-0 inline-flex items-center px-4">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </span>
            <input x-ref="searchInput" name="searchInput" type="text"
                class="w-full py-2 pl-10 pr-4 border border-primary-100 rounded-full dark:bg-dark dark:border-transparent dark:text-light focus:outline-none focus:ring"
                placeholder="Search..." />
        </div>

        <!-- Panel content (Search result) -->
        <div class="flex-1 px-4 pb-4 space-y-4 overflow-y-hidden h hover:overflow-y-auto">
            <h3 class="py-2 text-sm font-semibold text-gray-600 dark:text-light">History</h3>
            <a href="#" class="flex space-x-4">
                <div class="flex-shrink-0">
                    <img class="w-10 h-10 rounded-lg" src="" alt="Post cover" />
                </div>
                <div class="flex-1 max-w-xs overflow-hidden">
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-light">Header</h4>
                    <p class="text-sm font-normal text-gray-400 truncate dark:text-primary-400">
                        Lorem ipsum dolor, sit amet consectetur.
                    </p>
                    <span class="text-sm font-normal text-gray-400 dark:text-primary-500"> Post </span>
                </div>
            </a>
            <a href="#" class="flex space-x-4">
                <div class="flex-shrink-0">
                    <img class="w-10 h-10 rounded-lg" src="" alt="User" />
                </div>
                <div class="flex-1 max-w-xs overflow-hidden">
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-light">User</h4>
                    <p class="text-sm font-normal text-gray-400 truncate dark:text-primary-400">
                        Last activity 3h ago.
                    </p>
                    <span class="text-sm font-normal text-gray-400 dark:text-primary-500"> Offline </span>
                </div>
            </a>
            <a href="#" class="flex space-x-4">
                <div class="flex-shrink-0">
                    <img class="w-10 h-10 rounded-lg" src="" alt="Dashboard" />
                </div>
                <div class="flex-1 max-w-xs overflow-hidden">
                    <h4 class="text-sm font-semibold text-gray-600 dark:text-light">Dashboard</h4>
                    <p class="text-sm font-normal text-gray-400 truncate dark:text-primary-400">
                        Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                    </p>
                    <span class="text-sm font-normal text-gray-400 dark:text-primary-500"> Updated 3h ago.
                    </span>
                </div>
            </a>
            <template x-for="i in 10" x-key="i">
                <a href="#" class="flex space-x-4">
                    <div class="flex-shrink-0">
                        <img class="w-10 h-10 rounded-lg" src="" alt="Dashboard" />
                    </div>
                    <div class="flex-1 max-w-xs overflow-hidden">
                        <h4 class="text-sm font-semibold text-gray-600 dark:text-light">Dashboard</h4>
                        <p class="text-sm font-normal text-gray-400 truncate dark:text-primary-400">
                            Lorem ipsum dolor, sit amet consectetur adipisicing elit.
                        </p>
                        <span class="text-sm font-normal text-gray-400 dark:text-primary-500"> Updated 3h
                            ago. </span>
                    </div>
                </a>
            </template>
        </div>
    </div>
</section>
