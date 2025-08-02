@section('title', __('503'))

<x-error-layout>
    <div class="flex items-start justify-center min-h-screen bg-black bg-fixed bg-cover bg-bottom error-bg">
        <div class="pt-4">
            <div class="text-white text-center">
                <div class="relative">
                    <h1 class="pt-2 text-9xl tracking-tighter-less text-shadow font-sans font-bold">
                        <span>5</span><span>0</span><span>3</span>
                    </h1>
                    <span class="absolute top-0 left-0 text-white font-semibold">@lang('messages.ooops')!</span>
                </div>
                <h5 class="text-white font-semibold">@lang('messages.maintenance')</h5>
                <p class="text-white mt-2 mb-6">@lang('messages.maintenancewarning')</p>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .error-bg {
                background-image: url("/images/maintenance-mode.jpg");
            }

            .tracking-tighter-less {
                letter-spacing: -0.75rem;
            }

            .text-shadow {
                text-shadow: -8px 0 0 rgb(102 123 242);
            }
        </style>
    @endpush
</x-error-layout>
