@section('title', __('messages.division'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <a href="{{ route('division.index') }}">
            <h1 class="flex items-center justify-center text-xl">
                <svg fill="currentColor" class="size-7" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 24 24" enable-background="new 0 0 24 24"
                    xml:space="preserve">
                    <g id="chart-partition">
                        <path
                            d="M24,23H0V0h24V23z M18,21h4v-5h-4V21z M12,21h4v-5h-4V21z M2,21h8v-5H2V21z M15,14h7V9h-7V14z M2,14h11V9H2V14z M13,7h9V2 H2v5H13z" />
                    </g>
                </svg>
                <span class="px-2">@lang('messages.division')</span>
            </h1>
        </a>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('division.partials.feedback')
            </div>

            <div class="w-full">
                @include('division.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('division.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
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

                    var newURL = '{{ url('/general-affair/division') }}';
                    var newState = {
                        page: 'index-division'
                    };
                    var newTitle = '{{ __('messages.division') }}';

                    window.history.pushState(newState, newTitle, newURL);

                    $.ajax({
                        url: '{{ url('/general-affair/division/fetchdb') }}' + "/" + xpp + "/" + xisactive + "/" +
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
