<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title') - {{ config('custom.product_short') }}</title>

    @stack('styles')
    @vite(['resources/css/app.css', 'resources/js/app.js', 'resources/css/flasher.min.css', 'resources/js/flasher.min.js'])
</head>

<body class="font-sans antialiased">
    <div x-data="setup()" x-init="$refs.loading.classList.add('hidden');
    setColors(color);" :class="{ 'dark': isDark }">
        <div class="flex h-screen antialiased text-gray-900 bg-primary-20 dark:text-light dark:bg-primary-900">
            <div x-ref="loading"
                class="fixed inset-0 z-50 flex items-center justify-center text-2xl font-semibold text-primary-20 bg-primary-800">
                <svg class="animate-spin" width="100px" height="100px" viewBox="0 0 48 48" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <rect width="48" height="48" fill="currentColor" fill-opacity="0.01" />
                    <path d="M4 24C4 35.0457 12.9543 44 24 44V44C35.0457 44 44 35.0457 44 24C44 12.9543 35.0457 4 24 4"
                        stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    <path
                        d="M36 24C36 17.3726 30.6274 12 24 12C17.3726 12 12 17.3726 12 24C12 30.6274 17.3726 36 24 36V36"
                        stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
            </div>

            @include('layouts.aside')

            <div class="flex-1 h-full overflow-x-hidden overflow-y-auto">

                @include('layouts.navigation')

                <main class="bg-light dark:text-light dark:bg-dark">
                    {{ $slot }}
                </main>

                @include('layouts.footer')

            </div>

            @include('layouts.panels')
        </div>
    </div>

    <script>
        const setup = () => {
            const getTheme = () => {
                if (window.localStorage.getItem('dark')) {
                    return JSON.parse(window.localStorage.getItem('dark'))
                }
                return !!window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches
            }
            const setTheme = (value) => {
                window.localStorage.setItem('dark', value)
            }
            const getLang = () => {
                if (window.localStorage.getItem('enLang')) {} else {
                    window.localStorage.setItem('enLang', !0)
                }
                return JSON.parse(window.localStorage.getItem('enLang'))
            }
            const setLang = (value) => {
                window.localStorage.setItem('enLang', value)
            }
            const getColor = () => {
                if (window.localStorage.getItem('color')) {
                    return window.localStorage.getItem('color')
                }
                return 'teal'
            }
            const setColors = (color) => {
                const root = document.documentElement
                root.style.setProperty('--color-primary', `var(--color-${color})`)
                root.style.setProperty('--color-primary-20', `var(--color-${color}-20)`)
                root.style.setProperty('--color-primary-50', `var(--color-${color}-50)`)
                root.style.setProperty('--color-primary-100', `var(--color-${color}-100)`)
                root.style.setProperty('--color-primary-400', `var(--color-${color}-400)`)
                root.style.setProperty('--color-primary-500', `var(--color-${color}-500)`)
                root.style.setProperty('--color-primary-600', `var(--color-${color}-600)`)
                root.style.setProperty('--color-primary-700', `var(--color-${color}-700)`)
                root.style.setProperty('--color-primary-800', `var(--color-${color}-800)`)
                root.style.setProperty('--color-primary-850', `var(--color-${color}-850)`)
                root.style.setProperty('--color-primary-900', `var(--color-${color}-900)`)
                this.selectedColor = color
                window.localStorage.setItem('color', color)
            }
            return {
                loading: !0,
                isDark: getTheme(),
                toggleTheme() {
                    this.isDark = !this.isDark
                    setTheme(this.isDark)
                },
                setLightTheme() {
                    this.isDark = !1
                    setTheme(this.isDark)
                },
                setDarkTheme() {
                    this.isDark = !0
                    setTheme(this.isDark)
                },
                isEnLang: getLang(),
                toggleLang() {
                    this.isEnLang = !this.isEnLang
                    setLang(this.isEnLang)
                },
                setEnLang() {
                    this.isEnLang = !0
                    setLang(this.isEnLang)
                    window.location.href = '/locale/en'
                },
                setIdLang() {
                    this.isEnLang = !1
                    setLang(this.isEnLang)
                    window.location.href = '/locale/id'
                },
                color: getColor(),
                selectedColor: 'teal',
                setColors,
                isSideBarOpen: !0,
                toggleSidbarMenu() {
                    this.isSidebarOpen = !1
                    this.$nextTick(() => {
                        this.$refs.sideBar.focus()
                    })
                },
                isSettingsPanelOpen: !1,
                openSettingsPanel() {
                    this.isSettingsPanelOpen = !0
                    this.$nextTick(() => {
                        this.$refs.settingsPanel.focus()
                    })
                },
                isNotificationsPanelOpen: !1,
                openNotificationsPanel() {
                    this.isNotificationsPanelOpen = !0
                    this.$nextTick(() => {
                        this.$refs.notificationsPanel.focus()
                    })
                },
                isSearchPanelOpen: !1,
                openSearchPanel() {
                    this.isSearchPanelOpen = !0
                    this.$nextTick(() => {
                        this.$refs.searchInput.focus()
                    })
                },
                isMobileSubMenuOpen: !1,
                openMobileSubMenu() {
                    this.isMobileSubMenuOpen = !0
                    this.$nextTick(() => {
                        this.$refs.mobileSubMenu.focus()
                    })
                },
                isMobileMainMenuOpen: !1,
                openMobileMainMenu() {
                    this.isMobileMainMenuOpen = !0
                    this.$nextTick(() => {
                        this.$refs.mobileMainMenu.focus()
                    })
                },
            }
        }
    </script>

    @stack('scripts')
</body>

</html>
