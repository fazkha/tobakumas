@section('title', __('404'))

<x-error-layout>
    <div class="flex items-center justify-center min-h-screen bg-black bg-fixed bg-cover bg-bottom error-bg">
        <div class="pt-4">
            <div class="text-white text-center">
                <div class="relative">
                    <h1 class="pt-2 text-9xl tracking-tighter-less text-shadow font-sans font-bold">
                        <span>4</span><span>1</span><span>9</span>
                    </h1>
                    <span class="absolute top-0 left-0 text-white font-semibold">@lang('messages.ooops')!</span>
                </div>
                <h5 class="text-white font-semibold">@lang('messages.pagenotfound')</h5>
                <p class="text-white mt-4 mb-6">@lang('messages.wearesorrypagenotfound')</p>
                <a href="{{ url('/') }}"
                    class="bg-indigo-800 px-5 py-3 text-sm shadow-sm font-medium tracking-wider text-gray-50 rounded-full hover:shadow-lg">
                    @lang('messages.gotolanding')
                </a>
            </div>
        </div>
    </div>

    @push('styles')
        <style>
            .error-bg {
                background-image: url("/images/bg-error.jpg");
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
