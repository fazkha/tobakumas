@section('title', __('messages.kecamatan'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('kecamatan.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="size-7" viewBox="-1.5 0 19 19" xmlns="http://www.w3.org/2000/svg"
                    class="cf-icon-svg">
                    <path
                        d="M15.084 15.2H.916a.264.264 0 0 1-.254-.42l2.36-4.492a.865.865 0 0 1 .696-.42h.827a9.51 9.51 0 0 0 .943 1.108H3.912l-1.637 3.116h11.45l-1.637-3.116h-1.34a9.481 9.481 0 0 0 .943-1.109h.591a.866.866 0 0 1 .696.421l2.36 4.492a.264.264 0 0 1-.254.42zM11.4 7.189c0 2.64-2.176 2.888-3.103 5.46a.182.182 0 0 1-.356 0c-.928-2.572-3.104-2.82-3.104-5.46a3.282 3.282 0 0 1 6.563 0zm-1.86-.005a1.425 1.425 0 1 0-1.425 1.425A1.425 1.425 0 0 0 9.54 7.184z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.marketing')</span>
                    <span>@lang('messages.kecamatan')</span>
                </div>
            </a>
        </h1>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('kecamatan.partials.feedback')
            </div>

            <div class="w-full">
                @include('kecamatan.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('kecamatan.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
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

                    var newURL = '{{ url('/marketing/kecamatan') }}';
                    var newState = {
                        page: 'index-kecamatan'
                    };
                    var newTitle = '{{ __('messages.kecamatan') }}';

                    window.history.pushState(newState, newTitle, newURL);

                    $.ajax({
                        url: '{{ url('/marketing/kecamatan/fetchdb') }}' + "/" + xpp + "/" + xisactive + "/" +
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
