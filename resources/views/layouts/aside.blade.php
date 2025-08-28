<aside x-transition:enter="transition duration-300 ease-in-out transform sm:duration-500"
    x-transition:enter-start="translate-x-full" x-transition:enter-end="translate-x-0"
    x-transition:leave="transition duration-300 slide transform sm:duration-500" x-transition:leave-start="translate-x-0"
    x-transition:leave-end="translate-x-full" x-ref="sideBar" x-show="isSideBarOpen"
    class="z-1 flex-shrink-0 hidden w-64 bg-primary-20 border-r border-primary-100 dark:border-primary-800 dark:bg-primary-900 md:block"
    aria-hidden="true">
    <div class="flex items-center justify-start py-2.5 border-b border-primary-100 dark:border-primary-800">
        <div class="px-2">
            <a href="{{ route('dashboard') }}">
                <x-application-logo class="!size-10" />
            </a>
        </div>
        <div class="text-md text-gray-800 dark:text-gray-200">{{ config('custom.company_name') }}</div>
    </div>

    <div class="flex flex-col h-[90%]">
        <!-- Sidebar links -->
        <nav aria-label="Main" class="flex-1 max-h-[100%] px-2 py-4 space-y-2 overflow-y-hidden hover:overflow-y-auto">

            <div x-data="{{ request()->getRequestUri() == '/admin/dashboard' ? '{isActive: true, open: true}' : '{isActive: false, open: false}' }}">
                <a href="{{ route('dashboard') }}"
                    class="flex items-center p-2 transition-colors rounded-md text-gray-600 hover:bg-primary-100 dark:text-light dark:hover:bg-primary"
                    :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }" role="button" aria-haspopup="true"
                    :aria-expanded="(open || isActive) ? 'true' : 'false'">
                    <span aria-hidden="true">
                        <svg class="w-5 h-5" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg"
                            xmlns:xlink="http://www.w3.org/1999/xlink">
                            <path fill="currentColor" d="M0 15h16v1h-16v-1z"></path>
                            <path fill="currentColor" d="M0 0h1v16h-1v-16z"></path>
                            <path fill="currentColor" d="M9 8l-2.9-3-4.1 4v5h14v-13.1z"></path>
                        </svg>
                    </span>
                    <span class="ml-2 text-sm"> Dashboards </span>
                </a>
            </div>

            @can('branch-list')
                <div x-data="{{ substr(request()->getRequestUri(), 0, 22) == '/general-affair/branch' ||
                substr(request()->getRequestUri(), 0, 24) == '/general-affair/division' ||
                substr(request()->getRequestUri(), 0, 23) == '/general-affair/jabatan' ||
                substr(request()->getRequestUri(), 0, 26) == '/general-affair/brandivjab'
                    ? '{isActive: true, open: true}'
                    : '{isActive: false, open: false}' }}">
                    <a href="#" @click="$event.preventDefault(); open = !open"
                        class="flex items-center p-2 text-gray-600 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                        :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }" role="button" aria-haspopup="true"
                        :aria-expanded="(open || isActive) ? 'true' : 'false'">
                        <span aria-hidden="true">
                            <svg fill="currentColor" class="size-5" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                                data-name="Layer 1">
                                <path
                                    d="M14,8h1a1,1,0,0,0,0-2H14a1,1,0,0,0,0,2Zm0,4h1a1,1,0,0,0,0-2H14a1,1,0,0,0,0,2ZM9,8h1a1,1,0,0,0,0-2H9A1,1,0,0,0,9,8Zm0,4h1a1,1,0,0,0,0-2H9a1,1,0,0,0,0,2Zm12,8H20V3a1,1,0,0,0-1-1H5A1,1,0,0,0,4,3V20H3a1,1,0,0,0,0,2H21a1,1,0,0,0,0-2Zm-8,0H11V16h2Zm5,0H15V15a1,1,0,0,0-1-1H10a1,1,0,0,0-1,1v5H6V4H18Z" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm">@lang('messages.generalaffair')</span>
                        <span aria-hidden="true" class="ml-auto">
                            <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </a>
                    @can('branch-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="generalaffair">
                            <a href="{{ route('branch.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg class="size-5" viewBox="0 0 1024 1024" t="1569683632175" class="icon" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg" p-id="12593"
                                        xmlns:xlink="http://www.w3.org/1999/xlink">
                                        <defs>
                                            <style type="text/css"></style>
                                        </defs>
                                        <path
                                            d="M640.6 429.8h257.1c7.9 0 14.3-6.4 14.3-14.3V158.3c0-7.9-6.4-14.3-14.3-14.3H640.6c-7.9 0-14.3 6.4-14.3 14.3v92.9H490.6c-3.9 0-7.1 3.2-7.1 7.1v221.5h-85.7v-96.5c0-7.9-6.4-14.3-14.3-14.3H126.3c-7.9 0-14.3 6.4-14.3 14.3v257.2c0 7.9 6.4 14.3 14.3 14.3h257.1c7.9 0 14.3-6.4 14.3-14.3V544h85.7v221.5c0 3.9 3.2 7.1 7.1 7.1h135.7v92.9c0 7.9 6.4 14.3 14.3 14.3h257.1c7.9 0 14.3-6.4 14.3-14.3v-257c0-7.9-6.4-14.3-14.3-14.3h-257c-7.9 0-14.3 6.4-14.3 14.3v100h-78.6v-393h78.6v100c0 7.9 6.4 14.3 14.3 14.3z m53.5-217.9h150V362h-150V211.9zM329.9 587h-150V437h150v150z m364.2 75.1h150v150.1h-150V662.1z"
                                            p-id="12594"></path>
                                    </svg>
                                    @lang('messages.branch')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('division-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="generalaffair">
                            <a href="{{ route('division.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg fill="currentColor" class="size-5" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24"
                                        enable-background="new 0 0 24 24" xml:space="preserve">
                                        <g id="chart-partition">
                                            <path
                                                d="M24,23H0V0h24V23z M18,21h4v-5h-4V21z M12,21h4v-5h-4V21z M2,21h8v-5H2V21z M15,14h7V9h-7V14z M2,14h11V9H2V14z M13,7h9V2 H2v5H13z" />
                                        </g>
                                    </svg>
                                    @lang('messages.division')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('jabatan-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="generalaffair">
                            <a href="{{ route('jabatan.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg fill="currentColor" class="size-5" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M22,9.67A1,1,0,0,0,21.14,9l-5.69-.83L12.9,3a1,1,0,0,0-1.8,0L8.55,8.16,2.86,9a1,1,0,0,0-.81.68,1,1,0,0,0,.25,1l4.13,4-1,5.68a1,1,0,0,0,.4,1,1,1,0,0,0,1.05.07L12,18.76l5.1,2.68a.93.93,0,0,0,.46.12,1,1,0,0,0,.59-.19,1,1,0,0,0,.4-1l-1-5.68,4.13-4A1,1,0,0,0,22,9.67Zm-6.15,4a1,1,0,0,0-.29.89l.72,4.19-3.76-2a1,1,0,0,0-.94,0l-3.76,2,.72-4.19a1,1,0,0,0-.29-.89l-3-3,4.21-.61a1,1,0,0,0,.76-.55L12,5.7l1.88,3.82a1,1,0,0,0,.76.55l4.21.61Z" />
                                    </svg>
                                    @lang('messages.jobposition')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('brandivjab-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="generalaffair">
                            <a href="{{ route('brandivjab.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg class="size-5" viewBox="0 0 24 24" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path d="M16.4501 14.4V8.5C16.4501 7.95 16.0001 7.5 15.4501 7.5H12.55"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M14.05 6L12.25 7.5L14.05 9" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path d="M7.55005 10.2V14.3999" stroke="currentColor" stroke-width="1.5"
                                            stroke-linecap="round" stroke-linejoin="round" />
                                        <path
                                            d="M7.70001 9.89999C8.77697 9.89999 9.65002 9.02697 9.65002 7.95001C9.65002 6.87306 8.77697 6 7.70001 6C6.62306 6 5.75 6.87306 5.75 7.95001C5.75 9.02697 6.62306 9.89999 7.70001 9.89999Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M7.54999 17.9999C8.5441 17.9999 9.34998 17.194 9.34998 16.1999C9.34998 15.2058 8.5441 14.3999 7.54999 14.3999C6.55588 14.3999 5.75 15.2058 5.75 16.1999C5.75 17.194 6.55588 17.9999 7.54999 17.9999Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path
                                            d="M16.45 17.9999C17.4441 17.9999 18.25 17.194 18.25 16.1999C18.25 15.2058 17.4441 14.3999 16.45 14.3999C15.4559 14.3999 14.65 15.2058 14.65 16.1999C14.65 17.194 15.4559 17.9999 16.45 17.9999Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M9 22H15C20 22 22 20 22 15V9C22 4 20 2 15 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22Z"
                                            stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    @lang('messages.brandivjab')
                                </span>
                            </a>
                        </div>
                    @endcan
                </div>
            @endcan

            @can('pegawai-list')
                <div x-data="{{ substr(request()->getRequestUri(), 0, 24) == '/human-resource/employee'
                    ? '{isActive: true, open: true}'
                    : '{isActive: false, open: false}' }}">
                    <a href="#" @click="$event.preventDefault(); open = !open"
                        class="flex items-center p-2 text-gray-600 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                        :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }" role="button"
                        aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'">
                        <span aria-hidden="true">
                            <svg class="w-5 h-5" fill="currentColor" version="1.1" id="Capa_1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                                y="0px" viewBox="0 0 800 800" style="enable-background:new 0 0 800 800;"
                                xml:space="preserve">
                                <g>
                                    <path
                                        d="M700,422.622c0,17.149-13.846,31.06-30.945,31.06h-55.144V574.53c30.924,9.713,53.558,38.37,53.558,72.604
                                                                                                    c0,42.22-34.092,76.435-76.192,76.435c-42.058,0-76.216-34.215-76.216-76.435c0-33.366,21.484-61.444,51.217-71.875V453.682
                                                                                                    H411.501v197.281c30.946,9.7,53.603,38.357,53.603,72.604c0,42.208-34.137,76.433-76.215,76.433
                                                                                                    c-42.058,0-76.192-34.225-76.192-76.433c0-33.377,21.462-61.445,51.192-71.876V453.681H217.469v120.849
                                                                                                    c30.947,9.713,53.538,38.37,53.538,72.604c0,42.219-34.093,76.435-76.172,76.435c-42.058,0-76.173-34.216-76.173-76.435
                                                                                                    c0-33.366,21.442-61.444,51.172-71.875V453.682h-38.888c-17.102,0-30.946-13.909-30.946-31.06c0-17.14,13.845-31.05,30.946-31.05
                                                                                                    h538.108C686.154,391.572,700,405.482,700,422.622z M410.548,178.586l20.397,212.985h50.543
                                                                                                    c-2.453-42.077-6.1-88.538-8.485-117.347c18.968,25.22,30.707,64.905,34.376,117.347h60.177
                                                                                                    c-5.358-86.743-29.927-148.005-73.589-181.631c-7.249-5.59-15.276-11.235-23.328-15.596c-5.578-3.011-11.459-5.437-17.447-7.482
                                                                                                    C439.452,182.196,424.999,179.565,410.548,178.586z M400,169.896c47.157,0,85.416-37.998,85.416-84.948
                                                                                                    C485.416,38.021,447.157,0,400,0c-47.18,0-85.417,38.021-85.417,84.948C314.583,131.898,352.82,169.896,400,169.896z
                                                                                                    M291.102,391.572c5.122-52.496,17.34-92.321,35.83-117.39c-1.302,25.96-3.493,70.016-5.621,117.39h45.14l25.563-213.312
                                                                                                    c-15.581,0.522-31.229,2.969-45.984,8.046c-6.057,2.088-12.001,4.633-17.601,7.766c-19.098,10.669-35.157,24.742-48.416,41.925
                                                                                                    c-6.793,8.798-12.608,18.281-17.665,28.167c-19.987,39.261-27.887,83.948-31.619,127.406L291.102,391.572L291.102,391.572z" />
                                </g>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm">@lang('messages.humanresource')</span>
                        <span aria-hidden="true" class="ml-auto">
                            <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </a>
                    @can('pegawai-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="humanresource">
                            <a href="{{ route('employee.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg fill="currentColor" class="size-5" viewBox="0 0 32 32" version="1.1"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <title>users</title>
                                        <path
                                            d="M16 21.416c-5.035 0.022-9.243 3.537-10.326 8.247l-0.014 0.072c-0.018 0.080-0.029 0.172-0.029 0.266 0 0.69 0.56 1.25 1.25 1.25 0.596 0 1.095-0.418 1.22-0.976l0.002-0.008c0.825-3.658 4.047-6.35 7.897-6.35s7.073 2.692 7.887 6.297l0.010 0.054c0.127 0.566 0.625 0.982 1.221 0.982 0.69 0 1.25-0.559 1.25-1.25 0-0.095-0.011-0.187-0.031-0.276l0.002 0.008c-1.098-4.78-5.305-8.295-10.337-8.316h-0.002zM9.164 11.102c0 0 0 0 0 0 2.858 0 5.176-2.317 5.176-5.176s-2.317-5.176-5.176-5.176c-2.858 0-5.176 2.317-5.176 5.176v0c0.004 2.857 2.319 5.172 5.175 5.176h0zM9.164 3.25c0 0 0 0 0 0 1.478 0 2.676 1.198 2.676 2.676s-1.198 2.676-2.676 2.676c-1.478 0-2.676-1.198-2.676-2.676v0c0.002-1.477 1.199-2.674 2.676-2.676h0zM22.926 11.102c2.858 0 5.176-2.317 5.176-5.176s-2.317-5.176-5.176-5.176c-2.858 0-5.176 2.317-5.176 5.176v0c0.004 2.857 2.319 5.172 5.175 5.176h0zM22.926 3.25c1.478 0 2.676 1.198 2.676 2.676s-1.198 2.676-2.676 2.676c-1.478 0-2.676-1.198-2.676-2.676v0c0.002-1.477 1.199-2.674 2.676-2.676h0zM31.311 19.734c-0.864-4.111-4.46-7.154-8.767-7.154-0.395 0-0.784 0.026-1.165 0.075l0.045-0.005c-0.93-2.116-3.007-3.568-5.424-3.568-2.414 0-4.49 1.448-5.407 3.524l-0.015 0.038c-0.266-0.034-0.58-0.057-0.898-0.063l-0.009-0c-4.33 0.019-7.948 3.041-8.881 7.090l-0.012 0.062c-0.018 0.080-0.029 0.173-0.029 0.268 0 0.691 0.56 1.251 1.251 1.251 0.596 0 1.094-0.417 1.22-0.975l0.002-0.008c0.684-2.981 3.309-5.174 6.448-5.186h0.001c0.144 0 0.282 0.020 0.423 0.029 0.056 3.218 2.679 5.805 5.905 5.805 3.224 0 5.845-2.584 5.905-5.794l0-0.006c0.171-0.013 0.339-0.035 0.514-0.035 3.14 0.012 5.765 2.204 6.442 5.14l0.009 0.045c0.126 0.567 0.625 0.984 1.221 0.984 0.69 0 1.249-0.559 1.249-1.249 0-0.094-0.010-0.186-0.030-0.274l0.002 0.008zM16 18.416c-0 0-0 0-0.001 0-1.887 0-3.417-1.53-3.417-3.417s1.53-3.417 3.417-3.417c1.887 0 3.417 1.53 3.417 3.417 0 0 0 0 0 0.001v-0c-0.003 1.886-1.53 3.413-3.416 3.416h-0z" />
                                    </svg>
                                    @lang('messages.employee')
                                </span>
                            </a>
                        </div>
                    @endcan
                </div>
            @endcan

            @can('propinsi-list')
                <div x-data="{{ substr(request()->getRequestUri(), 0, 19) == '/marketing/propinsi' ||
                substr(request()->getRequestUri(), 0, 20) == '/marketing/kabupaten' ||
                substr(request()->getRequestUri(), 0, 24) == '/marketing/brandivjabkab'
                    ? '{isActive: true, open: true}'
                    : '{isActive: false, open: false}' }}">
                    <a href="#" @click="$event.preventDefault(); open = !open"
                        class="flex items-center p-2 text-gray-600 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                        :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }" role="button"
                        aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'">
                        <span aria-hidden="true">
                            <svg fill="currentColor" class="size-5" viewBox="0 0 1000 1000"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M897 198q-24-6-87-14-81-10-164-15-13-33-47-56-42-29-99-29t-99 29q-34 23-48 56-82 5-163 15-63 8-87 14-23 5-35 18-10 11-14 27-2 12-1 25l2 10 32 296q32 298 33 306l1 6q3 14 7 20 8 10 27.5 10t25.5-14q3-7 3-24l2-100q0-21 15-36t36-16q184-4 263-4t263 4q21 1 36 15.5t15 35.5l2 101q0 17 3 24 6 14 25.5 14t27.5-10q4-6 7-20l1-6q1-8 33-306l32-296 2-10q1-13-1-25-4-16-14-27-12-13-35-18zm-43 111q-3 28-19 189l-15 156-113-3q-128-3-206-3h-1q-78 0-207 3l-112 3-15-156q-16-161-20-189-2-20 2-30t15-14q7-3 29-6h3q164-25 305-25h1q142 0 305 24l3 1q21 3 29 6 11 4 15 13.5t1 30.5zm-457 24h162q4 0 7 3t3 7v227q0 4-3 6.5t-7 2.5H397q-4 0-7-2.5t-3-6.5V343q0-4 3-7t7-3zm409 11l-122-20q-4 0-7.5 2.5T672 333l-25 158q-1 4 1.5 7t6.5 4l122 20q4 0 7.5-2t4.5-6l25-159q1-4-1.5-7t-6.5-4zm-469 17q0-4-3.5-6.5T326 353l-122 20q-4 0-6.5 3.5T196 384l25 158q1 4 4 6.5t7 1.5l123-20q4 0 6.5-3.5t1.5-7.5z" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm">@lang('messages.marketing')</span>
                        <span aria-hidden="true" class="ml-auto">
                            <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </a>
                    @can('propinsi-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="marketing">
                            <a href="{{ route('propinsi.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg fill="currentColor" class="size-5" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M21.32,5.05l-6-2h-.07a.7.7,0,0,0-.14,0h-.23l-.13,0h-.07L9,5,3.32,3.05a1,1,0,0,0-.9.14A1,1,0,0,0,2,4V18a1,1,0,0,0,.68.95l6,2h0a1,1,0,0,0,.62,0h0L15,19.05,20.68,21A1.19,1.19,0,0,0,21,21a.94.94,0,0,0,.58-.19A1,1,0,0,0,22,20V6A1,1,0,0,0,21.32,5.05ZM8,18.61,4,17.28V5.39L8,6.72Zm6-1.33-4,1.33V6.72l4-1.33Zm6,1.33-4-1.33V5.39l4,1.33Z" />
                                    </svg>
                                    @lang('messages.propinsi')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('kabupaten-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="marketing">
                            <a href="{{ route('kabupaten.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg fill="currentColor" class="size-5" viewBox="-1.5 0 19 19"
                                        xmlns="http://www.w3.org/2000/svg" class="cf-icon-svg">
                                        <path
                                            d="M15.084 15.2H.916a.264.264 0 0 1-.254-.42l2.36-4.492a.865.865 0 0 1 .696-.42h.827a9.51 9.51 0 0 0 .943 1.108H3.912l-1.637 3.116h11.45l-1.637-3.116h-1.34a9.481 9.481 0 0 0 .943-1.109h.591a.866.866 0 0 1 .696.421l2.36 4.492a.264.264 0 0 1-.254.42zM11.4 7.189c0 2.64-2.176 2.888-3.103 5.46a.182.182 0 0 1-.356 0c-.928-2.572-3.104-2.82-3.104-5.46a3.282 3.282 0 0 1 6.563 0zm-1.86-.005a1.425 1.425 0 1 0-1.425 1.425A1.425 1.425 0 0 0 9.54 7.184z" />
                                    </svg>
                                    @lang('messages.kabupaten')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('kabupaten-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="marketing">
                            <a href="{{ route('brandivjabkab.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg class="size-5" version="1.1" xmlns="http://www.w3.org/2000/svg"
                                        xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                        viewBox="0 0 329.966 329.966" style="enable-background:new 0 0 329.966 329.966;"
                                        xml:space="preserve">
                                        <path id="XMLID_822_"
                                            d="M218.317,139.966h-38.334v-45V15c0-8.284-6.716-15-15-15h-120c-8.284,0-15,6.716-15,15v79.966
                                                                                                                c0,8.284,6.716,15,15,15h105v30h-38.334c-52.383,0-95,42.617-95,95s42.617,95,95,95h106.668c52.383,0,95-42.617,95-95
                                                                                                                S270.7,139.966,218.317,139.966z M59.983,79.966V30h90v49.966H59.983z M218.317,299.966H111.649c-35.841,0-65-29.159-65-65
                                                                                                                s29.159-65,65-65h38.334v65c0,8.284,6.716,15,15,15c8.284,0,15-6.716,15-15v-65h38.334c35.841,0,65,29.159,65,65
                                                                                                                S254.158,299.966,218.317,299.966z" />
                                    </svg>
                                    @lang('messages.brandivjabkab')
                                </span>
                            </a>
                        </div>
                    @endcan
                </div>
            @endcan

            @can('satuan-list')
                <div x-data="{{ substr(request()->getRequestUri(), 0, 16) == '/warehouse/units' ||
                substr(request()->getRequestUri(), 0, 22) == '/warehouse/conversions' ||
                substr(request()->getRequestUri(), 0, 17) == '/warehouse/gudang' ||
                substr(request()->getRequestUri(), 0, 16) == '/warehouse/goods' ||
                substr(request()->getRequestUri(), 0, 23) == '/warehouse/stock-opname' ||
                substr(request()->getRequestUri(), 0, 27) == '/warehouse/purchase-receipt'
                    ? '{isActive: true, open: true}'
                    : '{isActive: false, open: false}' }}">
                    <a href="#" @click="$event.preventDefault(); open = !open"
                        class="flex items-center p-2 text-gray-600 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                        :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }" role="button"
                        aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'">
                        <span aria-hidden="true">
                            <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 1024 1024"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M948.828 837.618c16.962 0 30.72-13.758 30.72-30.72V390.693c0-8.603-7.779-20.23-15.735-23.516L529.105 187.588c-10.592-4.376-30.496-4.351-41.074.052L56.711 367.086c-7.967 3.315-15.751 14.982-15.751 23.607v416.205c0 16.962 13.758 30.72 30.72 30.72h877.148zm0 40.96H71.68c-39.583 0-71.68-32.097-71.68-71.68V390.693c0-25.169 17.737-51.757 40.978-61.425l431.315-179.444c20.615-8.582 51.809-8.62 72.451-.093L979.452 329.32c23.279 9.617 41.056 36.187 41.056 61.373v416.205c0 39.583-32.097 71.68-71.68 71.68z" />
                                <path
                                    d="M223.534 851.277V562.386c0-16.962 13.758-30.72 30.72-30.72h512c16.962 0 30.72 13.758 30.72 30.72v288.891c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48V562.386c0-39.583-32.097-71.68-71.68-71.68h-512c-39.583 0-71.68 32.097-71.68 71.68v288.891c0 11.311 9.169 20.48 20.48 20.48s20.48-9.169 20.48-20.48z" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm">@lang('messages.warehouse')</span>
                        <span aria-hidden="true" class="ml-auto">
                            <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </a>
                    @can('satuan-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="warehouse">
                            <a href="{{ route('units.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <span aria-hidden="true">
                                        <svg fill="currentColor" class="w-5 h-5" viewBox="-0.77 0 50 50"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <g id="_3" data-name="3" transform="translate(-290.767 -130.5)">
                                                <path id="Path_224" data-name="Path 224"
                                                    d="M337.753,130.5h-45.5c-.818,0-1.483.485-1.483,1.081a1.148,1.148,0,0,0,.943,1h46.584a1.147,1.147,0,0,0,.939-1C339.233,130.985,338.57,130.5,337.753,130.5Z" />
                                                <path id="Path_225" data-name="Path 225"
                                                    d="M335.961,177.3h-.439V162.5a20.258,20.258,0,0,0,.013-3.459,20.081,20.081,0,0,0-16.913-19.822v-2.266h15.692a1.406,1.406,0,0,0,1.446-1.364l.947-1.954H293.294l.889,1.954a1.407,1.407,0,0,0,1.448,1.364h16.3v2.32c-.092.016-.184.029-.275.046a20.087,20.087,0,0,0-16.26,18.789,1.674,1.674,0,0,0-.137.656V177.3h-1.215a1.6,1.6,0,0,0,0,3.2h41.92a1.6,1.6,0,0,0,0-3.2Zm-20.018-5.067v-2.577h-.732v2.577a13.6,13.6,0,0,1-13.163-13.662c0-.043.005-.084.005-.126h2.381v-.73H302.09a13.584,13.584,0,0,1,13.121-12.807v2.86h.732V144.9a13.587,13.587,0,0,1,13.134,12.808h-3.338v.73h3.367v-.116c0,.081.013.159.013.242A13.6,13.6,0,0,1,315.943,172.231Z" />
                                                <path id="Path_226" data-name="Path 226"
                                                    d="M316.1,152.925l-.037-.005v-2.839c0-.172-.217-.31-.485-.31s-.485.138-.485.31v2.839l-.038.005a5.375,5.375,0,1,0,1.045,0Zm-.524,10a4.629,4.629,0,0,1-.87-9.169V156a2.367,2.367,0,0,0-1.65,2.22,2.527,2.527,0,0,0,5.044,0,2.368,2.368,0,0,0-1.65-2.22v-2.247a4.629,4.629,0,0,1-.874,9.169Z" />
                                            </g>
                                        </svg>
                                    </span>
                                    @lang('messages.unit')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('konversi-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="warehouse">
                            <a href="{{ route('conversions.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <span aria-hidden="true">
                                        <svg class="size-5" viewBox="0 0 17 17" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <path
                                                d="M6 8h-6v-6h1v4.109c1.013-3.193 4.036-5.484 7.5-5.484 3.506 0 6.621 2.36 7.574 5.739l-0.963 0.271c-0.832-2.95-3.551-5.011-6.611-5.011-3.226 0.001-6.016 2.276-6.708 5.376h4.208v1zM11 9v1h4.208c-0.693 3.101-3.479 5.375-6.708 5.375-3.062 0-5.78-2.061-6.611-5.011l-0.963 0.271c0.952 3.379 4.067 5.739 7.574 5.739 3.459 0 6.475-2.28 7.5-5.482v4.108h1v-6h-6z"
                                                fill="currentColor" />
                                        </svg>
                                    </span>
                                    @lang('messages.conversion')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('gudang-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="warehouse">
                            <a href="{{ route('gudang.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <span aria-hidden="true">
                                        <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 15 15" version="1.1"
                                            id="warehouse" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M13.5,5c-0.0762,0.0003-0.1514-0.0168-0.22-0.05L7.5,2L1.72,4.93C1.4632,5.0515,1.1565,4.9418,1.035,4.685&#xA;&#x9;S1.0232,4.1215,1.28,4L7.5,0.92L13.72,4c0.2761,0.0608,0.4508,0.3339,0.39,0.61C14.0492,4.8861,13.7761,5.0608,13.5,5z M5,10H2v3h3&#xA;&#x9;V10z M9,10H6v3h3V10z M13,10h-3v3h3V10z M11,6H8v3h3V6z M7,6H4v3h3V6z" />
                                        </svg>
                                    </span>
                                    @lang('messages.location')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('barang-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="warehouse">
                            <a href="{{ route('goods.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <span aria-hidden="true">
                                        <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 52 52"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="m45.2 19.6a1.6 1.6 0 0 1 1.59 1.45v22.55a4.82 4.82 0 0 1 -4.59 4.8h-32.2a4.82 4.82 0 0 1 -4.8-4.59v-22.61a1.6 1.6 0 0 1 1.45-1.59h38.55zm-12.39 6.67-.11.08-9.16 9.93-4.15-4a1.2 1.2 0 0 0 -1.61-.08l-.1.08-1.68 1.52a1 1 0 0 0 -.09 1.44l.09.1 5.86 5.55a2.47 2.47 0 0 0 1.71.71 2.27 2.27 0 0 0 1.71-.71l4.9-5.16.39-.41.52-.55 5-5.3a1.25 1.25 0 0 0 .11-1.47l-.07-.09-1.72-1.54a1.19 1.19 0 0 0 -1.6-.1zm12.39-22.67a4.81 4.81 0 0 1 4.8 4.8v4.8a1.6 1.6 0 0 1 -1.6 1.6h-44.8a1.6 1.6 0 0 1 -1.6-1.6v-4.8a4.81 4.81 0 0 1 4.8-4.8z" />
                                        </svg>
                                    </span>
                                    @lang('messages.goods')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('stopname-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="warehouse">
                            <a href="{{ route('stock-opname.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <span aria-hidden="true">
                                        <svg class="w-5 h-5" viewBox="0 0 16 16" version="1.1"
                                            xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink">
                                            <path fill="currentColor"
                                                d="M12 6v-6h-8v6h-4v7h16v-7h-4zM7 12h-6v-5h2v1h2v-1h2v5zM5 6v-5h2v1h2v-1h2v5h-6zM15 12h-6v-5h2v1h2v-1h2v5z">
                                            </path>
                                            <path fill="currentColor" d="M0 16h3v-1h10v1h3v-2h-16v2z"></path>
                                        </svg>
                                    </span>
                                    @lang('messages.stockopname')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('purchasereceipt-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="warehouse">
                            <a href="{{ route('purchase-receipt.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <span aria-hidden="true">
                                        <svg class="size-5" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512"
                                            xml:space="preserve">
                                            <style type="text/css">
                                                .st0 {
                                                    fill: currentColor;
                                                }
                                            </style>
                                            <g>
                                                <path class="st0"
                                                    d="M447.77,33.653c-36.385-5.566-70.629,15.824-82.588,49.228h-44.038v37.899h40.902 c5.212,31.372,29.694,57.355,62.855,62.436c41.278,6.316,79.882-22.042,86.222-63.341C517.428,78.575,489.07,39.969,447.77,33.653z" />
                                                <path class="st0"
                                                    d="M162.615,338.222c0-6.88-5.577-12.468-12.468-12.468H96.16c-6.891,0-12.467,5.588-12.467,12.468 c0,6.868,5.576,12.467,12.467,12.467h53.988C157.038,350.689,162.615,345.091,162.615,338.222z" />
                                                <path class="st0"
                                                    d="M392.999,237.965L284.273,340.452l-37.966,9.398v-86.619H0v215.996h246.307v-59.454l35.547-5.732 c16.95-2.418,29.396-6.692,44.336-15.018l46.302-24.228v104.432h132.435V270.828C504.927,202.618,428.016,202.43,392.999,237.965z M215.996,448.913H30.313v-155.37h185.683v63.805l-36.419,9.01c-15.968,4.395-25.708,20.518-22.174,36.696l0.298,1.247 c3.478,15.912,18.651,26.436,34.785,24.14l23.51-3.788V448.913z" />
                                            </g>
                                        </svg>
                                    </span>
                                    @lang('messages.goodsreceipt')
                                </span>
                            </a>
                        </div>
                    @endcan
                </div>
            @endcan

            @can('supplier-list')
                <div x-data="{{ substr(request()->getRequestUri(), 0, 18) == '/purchase/supplier' ||
                substr(request()->getRequestUri(), 0, 15) == '/purchase/order' ||
                substr(request()->getRequestUri(), 0, 14) == '/purchase/plan'
                    ? '{isActive: true, open: true}'
                    : '{isActive: false, open: false}' }}">
                    <a href="#" @click="$event.preventDefault(); open = !open"
                        class="flex items-center p-2 text-gray-600 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                        :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }" role="button"
                        aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'">
                        <span aria-hidden="true">
                            <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 1000 1000"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M891 308H340q-6 0-10.5-4t-5.5-10l-32-164q-2-14-12-22.5T256 99H110q-15 0-25.5 10.5T74 135v5q0 15 10.5 26t25.5 11h102q4 0 7 2.5t4 6.5l102 544q3 19 20 28 8 5 18 5h17q-22 25-21 58.5t25 56.5 57.5 23 58-23 25.5-56.5-22-58.5h186q-23 25-21.5 58.5T693 878t57.5 23 57.5-23 25-56.5-21-58.5h17q15 0 25.5-10.5T865 727v-8q0-15-11-25.5T828 683H409q-6 0-10.5-4t-5.5-9l-10-54q-1-8 4-14t12-5h460q13 0 22.5-8t11.5-21l33-219q3-16-7.5-28.5T891 308z" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm">@lang('messages.purchase')</span>
                        <span aria-hidden="true" class="ml-auto">
                            <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </a>
                    @can('supplier-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="purchase">
                            <a href="{{ route('supplier.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M22,7.82a1.25,1.25,0,0,0,0-.19v0h0l-2-5A1,1,0,0,0,19,2H5a1,1,0,0,0-.93.63l-2,5h0v0a1.25,1.25,0,0,0,0,.19A.58.58,0,0,0,2,8H2V8a4,4,0,0,0,2,3.4V21a1,1,0,0,0,1,1H19a1,1,0,0,0,1-1V11.44A4,4,0,0,0,22,8V8h0A.58.58,0,0,0,22,7.82ZM13,20H11V16h2Zm5,0H15V15a1,1,0,0,0-1-1H10a1,1,0,0,0-1,1v5H6V12a4,4,0,0,0,3-1.38,4,4,0,0,0,6,0A4,4,0,0,0,18,12Zm0-10a2,2,0,0,1-2-2,1,1,0,0,0-2,0,2,2,0,0,1-4,0A1,1,0,0,0,8,8a2,2,0,0,1-4,.15L5.68,4H18.32L20,8.15A2,2,0,0,1,18,10Z" />
                                    </svg>
                                    @lang('messages.supplier')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('po-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="purchase">
                            <a href="{{ route('purchase-plan.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg class="size-5" viewBox="0 0 15 15" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path fill-rule="evenodd" clip-rule="evenodd"
                                            d="M4.5 1C4.77614 1 5 1.22386 5 1.5V2H10V1.5C10 1.22386 10.2239 1 10.5 1C10.7761 1 11 1.22386 11 1.5V2H12.5C13.3284 2 14 2.67157 14 3.5V12.5C14 13.3284 13.3284 14 12.5 14H2.5C1.67157 14 1 13.3284 1 12.5V3.5C1 2.67157 1.67157 2 2.5 2H4V1.5C4 1.22386 4.22386 1 4.5 1ZM10 3V3.5C10 3.77614 10.2239 4 10.5 4C10.7761 4 11 3.77614 11 3.5V3H12.5C12.7761 3 13 3.22386 13 3.5V5H2V3.5C2 3.22386 2.22386 3 2.5 3H4V3.5C4 3.77614 4.22386 4 4.5 4C4.77614 4 5 3.77614 5 3.5V3H10ZM2 6V12.5C2 12.7761 2.22386 13 2.5 13H12.5C12.7761 13 13 12.7761 13 12.5V6H2ZM7 7.5C7 7.22386 7.22386 7 7.5 7C7.77614 7 8 7.22386 8 7.5C8 7.77614 7.77614 8 7.5 8C7.22386 8 7 7.77614 7 7.5ZM9.5 7C9.22386 7 9 7.22386 9 7.5C9 7.77614 9.22386 8 9.5 8C9.77614 8 10 7.77614 10 7.5C10 7.22386 9.77614 7 9.5 7ZM11 7.5C11 7.22386 11.2239 7 11.5 7C11.7761 7 12 7.22386 12 7.5C12 7.77614 11.7761 8 11.5 8C11.2239 8 11 7.77614 11 7.5ZM11.5 9C11.2239 9 11 9.22386 11 9.5C11 9.77614 11.2239 10 11.5 10C11.7761 10 12 9.77614 12 9.5C12 9.22386 11.7761 9 11.5 9ZM9 9.5C9 9.22386 9.22386 9 9.5 9C9.77614 9 10 9.22386 10 9.5C10 9.77614 9.77614 10 9.5 10C9.22386 10 9 9.77614 9 9.5ZM7.5 9C7.22386 9 7 9.22386 7 9.5C7 9.77614 7.22386 10 7.5 10C7.77614 10 8 9.77614 8 9.5C8 9.22386 7.77614 9 7.5 9ZM5 9.5C5 9.22386 5.22386 9 5.5 9C5.77614 9 6 9.22386 6 9.5C6 9.77614 5.77614 10 5.5 10C5.22386 10 5 9.77614 5 9.5ZM3.5 9C3.22386 9 3 9.22386 3 9.5C3 9.77614 3.22386 10 3.5 10C3.77614 10 4 9.77614 4 9.5C4 9.22386 3.77614 9 3.5 9ZM3 11.5C3 11.2239 3.22386 11 3.5 11C3.77614 11 4 11.2239 4 11.5C4 11.7761 3.77614 12 3.5 12C3.22386 12 3 11.7761 3 11.5ZM5.5 11C5.22386 11 5 11.2239 5 11.5C5 11.7761 5.22386 12 5.5 12C5.77614 12 6 11.7761 6 11.5C6 11.2239 5.77614 11 5.5 11ZM7 11.5C7 11.2239 7.22386 11 7.5 11C7.77614 11 8 11.2239 8 11.5C8 11.7761 7.77614 12 7.5 12C7.22386 12 7 11.7761 7 11.5ZM9.5 11C9.22386 11 9 11.2239 9 11.5C9 11.7761 9.22386 12 9.5 12C9.77614 12 10 11.7761 10 11.5C10 11.2239 9.77614 11 9.5 11Z"
                                            fill="currentColor" />
                                    </svg>
                                    @lang('messages.plan')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('po-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="purchase">
                            <a href="{{ route('purchase-order.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 1024 1024"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M533.959 424.126v242.812c0 12.162-9.773 22.022-21.829 22.022s-21.829-9.859-21.829-22.022V424.126h-6.654c-1.886.2-3.8.303-5.737.303h-82.373c-156.731 0-283.783-128.17-283.783-286.28 0-76.3 61.313-138.152 136.947-138.152 118.246 0 219.599 72.954 262.243 176.679C553.588 72.951 654.941-.003 773.187-.003c75.634 0 136.947 61.852 136.947 138.152 0 158.11-127.052 286.28-283.783 286.28h-82.373a54.39 54.39 0 01-5.737-.303h-4.28zm-53.538-44.043c4.774-1.168 8.403-5.572 8.403-10.708v-83.098c0-133.785-107.505-242.237-240.124-242.237-51.522 0-93.288 42.133-93.288 94.109 0 132.025 104.695 239.379 234.903 242.18a21.87 21.87 0 013.278-.247h86.828zm145.322.303h.608c132.619 0 240.124-108.451 240.124-242.237 0-51.975-41.766-94.109-93.288-94.109-132.619 0-240.124 108.451-240.124 242.237v83.098c0 5.136 3.628 9.54 8.403 10.708h80.65c1.236 0 2.448.104 3.628.303zM937.456 751.78c-74.665 64.718-237.417 105.999-425.511 105.999-188.128 0-350.904-41.296-425.551-106.034v76.504c0 .55-.02 1.095-.059 1.634.087.801.132 1.614.132 2.439 0 74.167 189.814 145.089 425.423 145.089s425.423-70.922 425.423-145.089c0-.854.048-1.696.142-2.525V751.78zm43.452-85.135c.137.996.207 2.014.207 3.048v162.959c0 1.036-.071 2.055-.208 3.053-4.256 108.638-213.251 185.747-469.016 185.747-258.413 0-469.082-78.714-469.082-189.132 0-.55.02-1.095.059-1.634a22.571 22.571 0 01-.132-2.439V672.992a86 86 0 010-6.614v-3.293c0-2.187.316-4.3.905-6.295 12.455-82.401 143.918-144.902 327.226-166.509a21.682 21.682 0 015.379.034c22.28-2.544 45.28-4.477 68.873-5.761 12.039-.655 22.324 8.659 22.974 20.803s-8.583 22.521-20.622 23.176C240.48 539.799 86.567 605.201 86.567 670.262c0 7.083 1.777 14.139 5.2 21.106 32.344 64.67 205.219 121.467 414.783 121.467 232.727 0 420.217-70.052 420.217-143.14 0-56.645-118.34-115.768-291.269-135.863a21.762 21.762 0 01-4.332-.956 1097.148 1097.148 0 00-54.572-4.332c-12.038-.657-21.269-11.035-20.618-23.179s10.939-21.456 22.977-20.799c226.148 12.347 397.817 84.304 401.956 182.077z" />
                                    </svg>
                                    @lang('messages.order')
                                </span>
                            </a>
                        </div>
                    @endcan
                </div>
            @endcan

            @can('customer-list')
                <div x-data="{{ substr(request()->getRequestUri(), 0, 14) == '/sale/customer' ||
                substr(request()->getRequestUri(), 0, 11) == '/sale/order'
                    ? '{isActive: true, open: true}'
                    : '{isActive: false, open: false}' }}">
                    <a href="#" @click="$event.preventDefault(); open = !open"
                        class="flex items-center p-2 text-gray-600 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                        :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }" role="button"
                        aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'">
                        <span aria-hidden="true">
                            <svg class="w-5 h-5" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M3.00999 11.22V15.71C3.00999 20.2 4.80999 22 9.29999 22H14.69C19.18 22 20.98 20.2 20.98 15.71V11.22"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M12 12C13.83 12 15.18 10.51 15 8.68L14.34 2H9.67L9 8.68C8.82 10.51 10.17 12 12 12Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M18.31 12C20.33 12 21.81 10.36 21.61 8.35L21.33 5.6C20.97 3 19.97 2 17.35 2H14.3L15 9.01C15.17 10.66 16.66 12 18.31 12Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path
                                    d="M5.64 12C7.29 12 8.78 10.66 8.94 9.01L9.16 6.8L9.64001 2H6.59C3.97001 2 2.97 3 2.61 5.6L2.34 8.35C2.14 10.36 3.62 12 5.64 12Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                                <path d="M12 17C10.33 17 9.5 17.83 9.5 19.5V22H14.5V19.5C14.5 17.83 13.67 17 12 17Z"
                                    stroke="currentColor" stroke-width="1.5" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm">@lang('messages.sale')</span>
                        <span aria-hidden="true" class="ml-auto">
                            <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </a>
                    @can('customer-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="sale">
                            <a href="{{ route('customer.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 1024 1024"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M106.544 501.695l385.403-380.262c11.913-11.754 31.079-11.722 42.955.075l382.71 380.14c8.025 7.971 20.992 7.927 28.963-.098s7.927-20.992-.098-28.963l-382.71-380.14c-27.811-27.625-72.687-27.7-100.589-.171L77.775 472.539c-8.051 7.944-8.139 20.911-.194 28.962s20.911 8.139 28.962.194z" />
                                        <path
                                            d="M783.464 362.551v517.12c0 16.962-13.758 30.72-30.72 30.72h-481.28c-16.962 0-30.72-13.758-30.72-30.72v-517.12c0-11.311-9.169-20.48-20.48-20.48s-20.48 9.169-20.48 20.48v517.12c0 39.583 32.097 71.68 71.68 71.68h481.28c39.583 0 71.68-32.097 71.68-71.68v-517.12c0-11.311-9.169-20.48-20.48-20.48s-20.48 9.169-20.48 20.48z" />
                                        <path
                                            d="M551.175 473.257l-27.341 53.8c-5.124 10.083-1.104 22.412 8.979 27.536s22.412 1.104 27.536-8.979l28.549-56.177c14.571-28.693-2.885-57.14-35.061-57.14h-83.466c-32.176 0-49.632 28.447-35.064 57.135l28.552 56.182c5.124 10.083 17.453 14.103 27.536 8.979s14.103-17.453 8.979-27.536l-27.341-53.8h78.143z" />
                                        <path
                                            d="M594.039 777.562c38.726 0 70.124-31.395 70.124-70.124 0-80.871-66.26-147.128-147.139-147.128h-9.841c-80.879 0-147.139 66.257-147.139 147.128 0 38.728 31.398 70.124 70.124 70.124h163.871zm0 40.96H430.168c-61.347 0-111.084-49.733-111.084-111.084 0-103.493 84.599-188.088 188.099-188.088h9.841c103.5 0 188.099 84.595 188.099 188.088 0 61.35-49.737 111.084-111.084 111.084z" />
                                    </svg>
                                    @lang('messages.customer')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('so-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="sale">
                            <a href="{{ route('sale-order.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg" data-name="Layer 1">
                                        <path
                                            d="M21.22,12A3,3,0,0,0,22,10a3,3,0,0,0-3-3H13.82A3,3,0,0,0,11,3H5A3,3,0,0,0,2,6a3,3,0,0,0,.78,2,3,3,0,0,0,0,4,3,3,0,0,0,0,4A3,3,0,0,0,2,18a3,3,0,0,0,3,3H19a3,3,0,0,0,2.22-5,3,3,0,0,0,0-4ZM11,19H5a1,1,0,0,1,0-2h6a1,1,0,0,1,0,2Zm0-4H5a1,1,0,0,1,0-2h6a1,1,0,0,1,0,2Zm0-4H5A1,1,0,0,1,5,9h6a1,1,0,0,1,0,2Zm0-4H5A1,1,0,0,1,5,5h6a1,1,0,0,1,0,2Zm8.69,11.71A.93.93,0,0,1,19,19H13.82a2.87,2.87,0,0,0,0-2H19a1,1,0,0,1,1,1A1,1,0,0,1,19.69,18.71Zm0-4A.93.93,0,0,1,19,15H13.82a2.87,2.87,0,0,0,0-2H19a1,1,0,0,1,1,1A1,1,0,0,1,19.69,14.71Zm0-4A.93.93,0,0,1,19,11H13.82a2.87,2.87,0,0,0,0-2H19a1,1,0,0,1,1,1A1,1,0,0,1,19.69,10.71Z" />
                                    </svg>
                                    @lang('messages.order')
                                </span>
                            </a>
                        </div>
                    @endcan
                </div>
            @endcan

            @can('recipe-list')
                <div x-data="{{ substr(request()->getRequestUri(), 0, 18) == '/production/recipe' ||
                substr(request()->getRequestUri(), 0, 17) == '/production/order'
                    ? '{isActive: true, open: true}'
                    : '{isActive: false, open: false}' }}">
                    <a href="#" @click="$event.preventDefault(); open = !open"
                        class="flex items-center p-2 text-gray-600 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                        :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }" role="button"
                        aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'">
                        <span aria-hidden="true">
                            <svg class="w-5 h-5" fill="currentColor" version="1.1" id="Capa_1"
                                xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px"
                                y="0px" viewBox="0 0 491.1 491.1" style="enable-background:new 0 0 491.1 491.1;"
                                xml:space="preserve">
                                <g>
                                    <g>
                                        <g>
                                            <g>
                                                <path
                                                    d="M469.2,309.15H20.9c-11.5,0-20.9-9.4-20.9-20.9s9.4-20.9,20.9-20.9h449.3c11.5,0,20.9,9.4,20.9,20.9 C491.1,299.75,480.7,309.15,469.2,309.15z" />
                                            </g>
                                            <g>
                                                <g>
                                                    <path
                                                        d="M382.7,490.55c-39.6,0-71.9-32.3-71.9-71.9s32.3-71.9,71.9-71.9s71.9,32.3,71.9,71.9 C453.6,458.25,421.3,490.55,382.7,490.55z M382.7,388.35c-16.7,0-30.2,13.6-30.2,31.3c0,16.7,13.6,31.3,30.2,31.3 s30.2-13.6,30.2-31.3C412.9,401.95,399.4,388.35,382.7,388.35z" />
                                                </g>
                                                <g>
                                                    <path
                                                        d="M107.5,490.55c-39.6,0-71.9-32.3-71.9-71.9s32.3-71.9,71.9-71.9s71.9,32.3,71.9,71.9S147.1,490.55,107.5,490.55z M107.5,388.35c-16.7,0-30.2,13.6-30.2,31.3c0,16.7,13.6,31.3,30.2,31.3s30.2-13.6,30.2-31.3 C138.7,401.95,125.2,388.35,107.5,388.35z" />
                                                </g>
                                            </g>
                                            <g>
                                                <path
                                                    d="M351.4,221.55H138.7c-11.5,0-20.9-9.4-20.9-20.9V21.45c0-11.5,9.4-20.9,20.9-20.9h211.6c11.5,0,20.9,9.4,20.9,20.9 v180.4C371.2,212.15,361.8,221.55,351.4,221.55z M159.6,180.95h171V41.25h-171V180.95z" />
                                            </g>
                                        </g>
                                    </g>
                                </g>
                            </svg>
                        </span>
                        <span class="ml-2 text-sm">@lang('messages.production')</span>
                        <span aria-hidden="true" class="ml-auto">
                            <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </a>
                    @can('recipe-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="production">
                            <a href="{{ route('recipe.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <span aria-hidden="true">
                                        <svg class="w-5 h-5" viewBox="0 0 512 512" xmlns="http://www.w3.org/2000/svg">
                                            <path fill="currentColor"
                                                d="M468.166 24.156c-13.8-.31-30.977 9.192-42.46 16.883-22.597 15.13-45.255 67.882-45.255 67.882s-17.292-5.333-22.626 0c-5.333 5.333 0 22.627 0 22.627l-4.95 4.948 22.628 22.63 4.95-4.952s17.293 5.333 22.626 0c5.333-5.334 0-22.627 0-22.627s52.75-22.66 67.883-45.255c10.7-15.978 24.91-42.97 11.313-56.568-3.824-3.825-8.707-5.45-14.107-5.57zM312.568 121.65L121.65 312.568l77.782 77.782L390.35 199.432l-77.782-77.782zm-176.07 231.223l-4.95 4.95s-17.293-5.332-22.626 0c-5.333 5.335 0 22.628 0 22.628s-52.75 22.66-67.883 45.255c-10.7 15.978-24.91 42.97-11.313 56.568 13.597 13.598 40.59-.612 56.568-11.312 22.596-15.13 45.254-67.882 45.254-67.882s17.292 5.333 22.626 0c5.333-5.333 0-22.627 0-22.627l4.95-4.948-22.628-22.63z" />
                                        </svg>
                                    </span>
                                    @lang('messages.recipe')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('prodo-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="production">
                            <a href="{{ route('production-order.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <span aria-hidden="true">
                                        <svg class="size-5" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg"
                                            version="1.1">
                                            <path style="fill:#555555;stroke:#000000;stroke-width:1.5px;"
                                                d="m 40,2 -2,9 -10,5 -8,-6 -9,9 6,9 -4,10 -10,2 0,12 10,1 4,10 -6,9 9,9 9,-6 9,4 2,10 13,0 1,-11 8,-4 9,7 9,-8 -6,-10 4,-9 11,-2 0,-12 -11,-2 -3,-9 6,-10 -9,-9 -8,6 -11,-5 -1,-9 z m 5,18 C 58,20 69,31 69,44 69,58 58,68 45,68 32,68 21,58 21,44 21,31 32,20 45,20 z" />
                                            <circle style="fill:none;stroke:#eeeeee;stroke-width:3" cx="65"
                                                cy="65" r="34" />
                                            <circle style="fill:#444444;fill-opacity:0.7" cx="65" cy="65"
                                                r="32" />
                                            <path style="stroke:none;fill:#00C60A;fill-opacity:0.7"
                                                d="m 58,33 7,34 32,-7 C 97,60 92,29 58,33" />
                                            <circle style=";stroke-width:5pt;stroke:#222222;fill:none;" cx="65"
                                                cy="65" r="30" />
                                            <g style="fill:#aaaaaa;">
                                                <circle cx="65" cy="35" r="2.5" />
                                                <circle cx="95" cy="65" r="2.5" />
                                                <circle cx="65" cy="95" r="2.5" />
                                                <circle cx="35" cy="65" r="2.5" />
                                            </g>
                                            <path style="stroke:#ffffff;stroke-width:4;fill:none;" d="M 65,65 60,42" />
                                            <path style="stroke:#ffffff;stroke-width:3;fill:none;" d="M 65,65 44,87" />
                                            <circle style="fill:#ffffff;" cx="65" cy="65" r="3.5" />
                                        </svg>
                                    </span>
                                    @lang('messages.productionorder')
                                </span>
                            </a>
                        </div>
                    @endcan
                </div>
            @endcan

            @can('delivery-list')
                <div x-data="{{ substr(request()->getRequestUri(), 0, 15) == '/delivery/order'
                    ? '{isActive: true, open: true}'
                    : '{isActive: false, open: false}' }}">
                    <a href="#" @click="$event.preventDefault(); open = !open"
                        class="flex items-center p-2 text-gray-600 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                        :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }" role="button"
                        aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'">
                        <span aria-hidden="true">
                            <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M1,12.5v5a1,1,0,0,0,1,1H3a3,3,0,0,0,6,0h6a3,3,0,0,0,6,0h1a1,1,0,0,0,1-1V5.5a3,3,0,0,0-3-3H11a3,3,0,0,0-3,3v2H6A3,3,0,0,0,3.6,8.7L1.2,11.9a.61.61,0,0,0-.07.14l-.06.11A1,1,0,0,0,1,12.5Zm16,6a1,1,0,1,1,1,1A1,1,0,0,1,17,18.5Zm-7-13a1,1,0,0,1,1-1h9a1,1,0,0,1,1,1v11h-.78a3,3,0,0,0-4.44,0H10Zm-2,6H4L5.2,9.9A1,1,0,0,1,6,9.5H8Zm-3,7a1,1,0,1,1,1,1A1,1,0,0,1,5,18.5Zm-2-5H8v2.78a3,3,0,0,0-4.22.22H3Z" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm">@lang('messages.delivery')</span>
                        <span aria-hidden="true" class="ml-auto">
                            <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </a>
                    @can('delivery-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="delivery">
                            <a href="{{ route('delivery-order.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <svg class="w-5 h-5" viewBox="0 0 48 48" fill="none"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M20 33L26 35C26 35 41 32 43 32C45 32 45 34 43 36C41 38 34 44 28 44C22 44 18 41 14 41C10 41 4 41 4 41"
                                            stroke="currentColor" stroke-width="4" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M4 29C6 27 10 24 14 24C18 24 27.5 28 29 30C30.5 32 26 35 26 35"
                                            stroke="currentColor" stroke-width="4" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <path d="M16 18V10C16 8.89543 16.8954 8 18 8H42C43.1046 8 44 8.89543 44 10V26"
                                            stroke="currentColor" stroke-width="4" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                        <rect x="25" y="8" width="10" height="9" fill="#2F88FF"
                                            stroke="currentColor" stroke-width="4" stroke-linecap="round"
                                            stroke-linejoin="round" />
                                    </svg>
                                    @lang('messages.order')
                                </span>
                            </a>
                        </div>
                    @endcan
                </div>
            @endcan

            @can('user-list')
                <div x-data="{{ substr(request()->getRequestUri(), 0, 12) == '/admin/users' ||
                substr(request()->getRequestUri(), 0, 12) == '/admin/roles' ||
                substr(request()->getRequestUri(), 0, 10) == '/admin/coa' ||
                substr(request()->getRequestUri(), 0, 13) == '/admin/qrcode'
                    ? '{isActive: true, open: true}'
                    : '{isActive: false, open: false}' }}">
                    <a href="#" @click="$event.preventDefault(); open = !open"
                        class="flex items-center p-2 text-gray-600 transition-colors rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary"
                        :class="{ 'bg-primary-100 dark:bg-primary': isActive || open }" role="button"
                        aria-haspopup="true" :aria-expanded="(open || isActive) ? 'true' : 'false'">
                        <span aria-hidden="true">
                            <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 24 24"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M21.32,9.55l-1.89-.63.89-1.78A1,1,0,0,0,20.13,6L18,3.87a1,1,0,0,0-1.15-.19l-1.78.89-.63-1.89A1,1,0,0,0,13.5,2h-3a1,1,0,0,0-.95.68L8.92,4.57,7.14,3.68A1,1,0,0,0,6,3.87L3.87,6a1,1,0,0,0-.19,1.15l.89,1.78-1.89.63A1,1,0,0,0,2,10.5v3a1,1,0,0,0,.68.95l1.89.63-.89,1.78A1,1,0,0,0,3.87,18L6,20.13a1,1,0,0,0,1.15.19l1.78-.89.63,1.89a1,1,0,0,0,.95.68h3a1,1,0,0,0,.95-.68l.63-1.89,1.78.89A1,1,0,0,0,18,20.13L20.13,18a1,1,0,0,0,.19-1.15l-.89-1.78,1.89-.63A1,1,0,0,0,22,13.5v-3A1,1,0,0,0,21.32,9.55ZM20,12.78l-1.2.4A2,2,0,0,0,17.64,16l.57,1.14-1.1,1.1L16,17.64a2,2,0,0,0-2.79,1.16l-.4,1.2H11.22l-.4-1.2A2,2,0,0,0,8,17.64l-1.14.57-1.1-1.1L6.36,16A2,2,0,0,0,5.2,13.18L4,12.78V11.22l1.2-.4A2,2,0,0,0,6.36,8L5.79,6.89l1.1-1.1L8,6.36A2,2,0,0,0,10.82,5.2l.4-1.2h1.56l.4,1.2A2,2,0,0,0,16,6.36l1.14-.57,1.1,1.1L17.64,8a2,2,0,0,0,1.16,2.79l1.2.4ZM12,8a4,4,0,1,0,4,4A4,4,0,0,0,12,8Zm0,6a2,2,0,1,1,2-2A2,2,0,0,1,12,14Z" />
                            </svg>
                        </span>
                        <span class="ml-2 text-sm">@lang('messages.setting')</span>
                        <span aria-hidden="true" class="ml-auto">
                            <svg class="w-4 h-4 transition-transform transform" :class="{ 'rotate-180': open }"
                                xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </span>
                    </a>
                    @can('user-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="setting">
                            <a href="{{ route('users.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <span aria-hidden="true">
                                        <svg fill="currentColor" class="w-5 h-5" viewBox="-2 -1.5 24 24"
                                            xmlns="http://www.w3.org/2000/svg" preserveAspectRatio="xMinYMin"
                                            class="jam jam-users">
                                            <path
                                                d='M3.534 11.07a1 1 0 1 1 .733 1.86A3.579 3.579 0 0 0 2 16.26V18a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1.647a3.658 3.658 0 0 0-2.356-3.419 1 1 0 1 1 .712-1.868A5.658 5.658 0 0 1 14 16.353V18a3 3 0 0 1-3 3H3a3 3 0 0 1-3-3v-1.74a5.579 5.579 0 0 1 3.534-5.19zM7 1a4 4 0 0 1 4 4v2a4 4 0 1 1-8 0V5a4 4 0 0 1 4-4zm0 2a2 2 0 0 0-2 2v2a2 2 0 1 0 4 0V5a2 2 0 0 0-2-2zm9 17a1 1 0 0 1 0-2h1a1 1 0 0 0 1-1v-1.838a3.387 3.387 0 0 0-2.316-3.213 1 1 0 1 1 .632-1.898A5.387 5.387 0 0 1 20 15.162V17a3 3 0 0 1-3 3h-1zM13 2a1 1 0 0 1 0-2 4 4 0 0 1 4 4v2a4 4 0 0 1-4 4 1 1 0 0 1 0-2 2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z' />
                                        </svg>
                                    </span>
                                    @lang('messages.user')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('role-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="setting">
                            <a href="{{ route('roles.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <span aria-hidden="true">
                                        <svg fill="currentColor" class="w-5 h-5" viewBox="0 0 52 52" data-name="Layer 1"
                                            xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M38.3,27.2A11.4,11.4,0,1,0,49.7,38.6,11.46,11.46,0,0,0,38.3,27.2Zm2,12.4a2.39,2.39,0,0,1-.9-.2l-4.3,4.3a1.39,1.39,0,0,1-.9.4,1,1,0,0,1-.9-.4,1.39,1.39,0,0,1,0-1.9l4.3-4.3a2.92,2.92,0,0,1-.2-.9,3.47,3.47,0,0,1,3.4-3.8,2.39,2.39,0,0,1,.9.2c.2,0,.2.2.1.3l-2,1.9a.28.28,0,0,0,0,.5L41.1,37a.38.38,0,0,0,.6,0l1.9-1.9c.1-.1.4-.1.4.1a3.71,3.71,0,0,1,.2.9A3.57,3.57,0,0,1,40.3,39.6Z" />
                                            <circle cx="21.7" cy="14.9" r="12.9" />
                                            <path
                                                d="M25.2,49.8c2.2,0,1-1.5,1-1.5h0a15.44,15.44,0,0,1-3.4-9.7,15,15,0,0,1,1.4-6.4.77.77,0,0,1,.2-.3c.7-1.4-.7-1.5-.7-1.5h0a12.1,12.1,0,0,0-1.9-.1A19.69,19.69,0,0,0,2.4,47.1c0,1,.3,2.8,3.4,2.8H24.9C25.1,49.8,25.1,49.8,25.2,49.8Z" />
                                        </svg>
                                    </span>
                                    @lang('messages.role')
                                </span>
                            </a>
                        </div>
                    @endcan
                    @can('coa-list')
                        <div x-show="open" class="mt-2 space-y-2 px-7" role="menu" arial-label="setting">
                            <a href="{{ route('coa.index') }}" role="menuitem"
                                class="block p-2 text-sm text-gray-500 transition-colors duration-200 rounded-md dark:text-light hover:bg-primary-100 dark:hover:bg-primary">
                                <span class="flex flex-row gap-1">
                                    <span aria-hidden="true">
                                        <svg class="w-5 h-5" viewBox="0 0 1024 1024" fill="currentColor" class="icon"
                                            version="1.1" xmlns="http://www.w3.org/2000/svg">
                                            <path
                                                d="M53.6 1023.2c-6.4 0-12.8-2.4-17.6-8-4.8-4.8-7.2-11.2-6.4-18.4L80 222.4c0.8-12.8 11.2-22.4 24-22.4h211.2v-3.2c0-52.8 20.8-101.6 57.6-139.2C410.4 21.6 459.2 0.8 512 0.8c108 0 196.8 88 196.8 196.8 0 0.8-0.8 1.6-0.8 2.4v0.8H920c12.8 0 23.2 9.6 24 22.4l49.6 768.8c0.8 2.4 0.8 4 0.8 6.4-0.8 13.6-11.2 24.8-24.8 24.8H53.6z m25.6-48H944l-46.4-726.4H708v57.6h0.8c12.8 8.8 20 21.6 20 36 0 24.8-20 44.8-44.8 44.8s-44.8-20-44.8-44.8c0-14.4 7.2-27.2 20-36h0.8v-57.6H363.2v57.6h0.8c12.8 8.8 20 21.6 20 36 0 24.8-20 44.8-44.8 44.8-24.8 0-44.8-20-44.8-44.8 0-14.4 7.2-27.2 20-36h0.8v-57.6H125.6l-46.4 726.4zM512 49.6c-81.6 0-148.8 66.4-148.8 148.8v3.2h298.4l-0.8-1.6v-1.6c0-82.4-67.2-148.8-148.8-148.8z"
                                                fill="" />
                                        </svg>
                                    </span>
                                    @lang('messages.chartofaccount')
                                </span>
                            </a>
                        </div>
                    @endcan
                </div>
            @endcan

        </nav>
    </div>
</aside>
