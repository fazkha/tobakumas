@section('title', __('messages.mitraizin'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('mitraizin.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="size-7" viewBox="0 0 16 16" id="request-16px"
                    xmlns="http://www.w3.org/2000/svg">
                    <path id="Path_49" data-name="Path 49"
                        d="M30.5,16a.489.489,0,0,1-.191-.038A.5.5,0,0,1,30,15.5V13h-.5A2.5,2.5,0,0,1,27,10.5v-8A2.5,2.5,0,0,1,29.5,0h11A2.5,2.5,0,0,1,43,2.5v8A2.5,2.5,0,0,1,40.5,13H33.707l-2.853,2.854A.5.5,0,0,1,30.5,16Zm-1-15A1.5,1.5,0,0,0,28,2.5v8A1.5,1.5,0,0,0,29.5,12h1a.5.5,0,0,1,.5.5v1.793l2.146-2.147A.5.5,0,0,1,33.5,12h7A1.5,1.5,0,0,0,42,10.5v-8A1.5,1.5,0,0,0,40.5,1ZM36,9a1,1,0,1,0-1,1A1,1,0,0,0,36,9Zm1-4a2,2,0,0,0-4,0,.5.5,0,0,0,1,0,1,1,0,1,1,1,1,.5.5,0,0,0,0,1A2,2,0,0,0,37,5Z"
                        transform="translate(-27)" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.humanresource')</span>
                    <span>@lang('messages.mitraizin')</span>
                </div>
            </a>
        </h1>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('mitraizin.partials.feedback')
            </div>

            <div class="w-full">
                @include('mitraizin.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('mitraizin.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            $("#pp-dropdown, #show-dropdown, #branch-dropdown, #mitra-dropdown, #search-tanggal").on(
                "change keyup paste",
                function() {
                    var xpp = $('#pp-dropdown option:selected').val();
                    var xshow = $('#show-dropdown option:selected').val();
                    var xbr = $('#branch-dropdown option:selected').val();
                    var xmt = $('#mitra-dropdown option:selected').val();
                    var xtanggal = $('#search-tanggal').val();
                    if (!xtanggal.trim()) {
                        xtanggal = '_';
                    }

                    $('#filter-loading').show();

                    var newURL = '{{ url('/human-resource/mitraizin') }}';
                    var newState = {
                        page: 'index-mitraizin'
                    };
                    var newTitle = '{{ __('messages.mitraizin') }}';

                    window.history.pushState(newState, newTitle, newURL);

                    $.ajax({
                        url: '{{ url('/human-resource/mitraizin/fetchdb') }}' + "/" + xpp + "/" + xshow + "/" +
                            xbr + "/" + xmt + "/" + xtanggal,
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
