<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Guest - {{ config('custom.product_short') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans text-gray-900 antialiased">
    <div style="background-image: url('{{ url('/images/landing.jpg') }}'); background-size: cover; background-repeat: no-repeat; background-position: center center; background-attachment: fixed;"
        class="min-h-screen flex flex-col justify-center items-left pt-6 sm:pt-0 bg-primary-20 dark:bg-primary-900">
        <div class="flex flex-col items-center justify-center w-full md:w-3/5">
            <div>
                <a href="/">
                    <x-application-logo class="size-15" />
                </a>
            </div>

            <div
                class="w-full sm:max-w-md mt-6 px-6 py-6 bg-primary-50 dark:bg-gray-850 shadow-lg overflow-hidden sm:rounded-lg">
                {{ $slot }}
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>
