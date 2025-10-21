@section('title', __('messages.kabupaten'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('kabupaten.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg">
                    <g transform="matrix(1.1485 0 0 1.2471 -1.233 -1.917)" fill="#373737" stroke-width=".82858px">
                        <rect x="1.0737" y="1.5368" width="13.931" height="12.83" fill-opacity=".25" />
                        <path
                            d="M1.074 1.537v12.83h13.93V1.538H1.075zm.835.836h2.41c-.28 1.934.04 3.95 1.045 5.678.803 1.428 1.797 2.841 1.932 4.49-.05.342.328 1.14-.312.99H1.908V2.374zm3.253 0h9.006v2.854L6.434 8.24c-.86-1.37-1.42-2.926-1.37-4.523.001-.45.035-.899.098-1.344zm9.006 3.753v7.406H8.173c.072-1.603-.46-3.177-1.312-4.56 2.435-.95 4.87-1.898 7.307-2.846z"
                            color="currentColor" style="-inkscape-stroke:none" />
                    </g>
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.marketing')</span>
                    <span>@lang('messages.kabupaten')</span>
                </div>
            </a>
        </h1>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('kabupaten.partials.feedback')
            </div>

            <div class="w-full">
                @include('kabupaten.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('kabupaten.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            $("#pp-dropdown, #isactive-dropdown, #search-nama").on(
                "change keyup paste",
                function() {
                    var xpp = $('#pp-dropdown option:selected').val();
                    var xisactive = $('#isactive-dropdown option:selected').val();
                    var xnama = $('#search-nama').val();
                    if (!xnama.trim()) {
                        xnama = '_';
                    }

                    $('#filter-loading').show();

                    var newURL = '{{ url('/marketing/kabupaten') }}';
                    var newState = {
                        page: 'index-kabupaten'
                    };
                    var newTitle = '{{ __('messages.kabupaten') }}';

                    window.history.pushState(newState, newTitle, newURL);

                    $.ajax({
                        url: '{{ url('/marketing/kabupaten/fetchdb') }}' + "/" + xpp + "/" + xisactive + "/" +
                            xnama,
                        type: "GET",
                        dataType: 'json',
                        success: function(result) {
                            $('#table-container').html(result);
                            $("#table-container").focus();
                            $('#filter-loading').hide();
                        }
                    });
                });
        </script>
    @endpush
</x-app-layout>
