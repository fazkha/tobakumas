@section('title', __('messages.mitraubah'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('mitraubah.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" version="1.1">
                    <path style="fill:none;stroke:#444444;stroke-width:2" d="M 8,16 15,14 83,6 92,78 29,94 22,92 z" />
                    <path style="fill:#287293;stroke:#888888" d="m 8,16 7,-2 68,-8 3,25 -67,9 -6,1 z" />
                    <path style="fill:none;stroke:#dddddd" d="m 15,15 c 1,7 3,19 4,23" />
                    <path style="fill:#cccccc;stroke:#888888"
                        d="m 19,39 -6,1 9,52 7,2 C 29,94 92,79 92,78 92,77 86,30 86,30 z" />
                    <path style="fill:#eeeeee;stroke:#aaaaaa;"
                        d="m 86,30 c 0,0 4,22 11,33 -3,6 -14,14 -19,15 -6,1 -45,13 -45,13 0,0 -4,-2 -6,-11 L 19,39 z" />
                    <path style="fill:#dddddd;stroke:#aaaaaa;"
                        d="M 97,63 C 96,62 93,60 93,60 L 81,77 c 0,0 11,-6 16,-14" />
                    <path style="fill:#4444444"
                        d="m 56,41 22,-4 1,5 c 0,0 -8,13 -5,31 l -6,2 c 0,0 -4,-13 4,-31 L 57,47 z M 32,54 c 6,-2 7,-7 8,-11 l 6,-1 8,36 -7,2 -6,-26 c 0,0 -3,5 -7,6 z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.humanresource')</span>
                    <span>@lang('messages.mitraubah')</span>
                </div>
            </a>
        </h1>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('mitraubah.partials.feedback')
            </div>

            <div class="w-full">
                @include('mitraubah.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('mitraubah.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            $("#pp-dropdown, #show-dropdown, #branch-dropdown, #user-dropdown, #search-tanggal").on(
                "change keyup paste",
                function() {
                    var xpp = $('#pp-dropdown option:selected').val();
                    var xshow = $('#show-dropdown option:selected').val();
                    var xbr = $('#branch-dropdown option:selected').val();
                    var xpg = $('#user-dropdown option:selected').val();
                    var xtanggal = $('#search-tanggal').val();
                    if (!xtanggal.trim()) {
                        xtanggal = '_';
                    }

                    $('#filter-loading').show();

                    var newURL = '{{ url('/human-resource/mitraubah') }}';
                    var newState = {
                        page: 'index-mitraubah'
                    };
                    var newTitle = '{{ __('messages.mitraubah') }}';

                    window.history.pushState(newState, newTitle, newURL);

                    $.ajax({
                        url: '{{ url('/human-resource/mitraubah/fetchdb') }}' + "/" + xpp + "/" + xshow + "/" +
                            xbr + "/" + xpg + "/" + xtanggal,
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
