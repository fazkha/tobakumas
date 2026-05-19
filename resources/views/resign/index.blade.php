@section('title', __('messages.resign'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('resign.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="size-5" viewBox="0 0 24 24" id="sign-out-double-arrow-left"
                    data-name="Line Color" xmlns="http://www.w3.org/2000/svg" class="icon line-color">
                    <polyline id="secondary" points="6 15 3 12 6 9"
                        style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                    </polyline>
                    <polyline id="secondary-2" data-name="secondary" points="11 15 8 12 11 9"
                        style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                    </polyline>
                    <line id="secondary-3" data-name="secondary" x1="8" y1="12" x2="17"
                        y2="12"
                        style="fill: none; stroke: rgb(44, 169, 188); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                    </line>
                    <path id="primary" d="M10,5V4a1,1,0,0,1,1-1h9a1,1,0,0,1,1,1V20a1,1,0,0,1-1,1H11a1,1,0,0,1-1-1V19"
                        style="fill: none; stroke: rgb(0, 0, 0); stroke-linecap: round; stroke-linejoin: round; stroke-width: 2;">
                    </path>
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.humanresource')</span>
                    <span>@lang('messages.resign')</span>
                </div>
            </a>
        </h1>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('resign.partials.feedback')
            </div>

            <div class="w-full">
                @include('resign.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('resign.partials.table')
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

                    var newURL = '{{ url('/human-resource/resign') }}';
                    var newState = {
                        page: 'index-resign'
                    };
                    var newTitle = '{{ __('messages.resign') }}';

                    window.history.pushState(newState, newTitle, newURL);

                    $.ajax({
                        url: '{{ url('/human-resource/resign/fetchdb') }}' + "/" + xpp + "/" + xshow + "/" +
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
