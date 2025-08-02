@section('title', __('messages.user'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <a href="{{ route('users.index') }}">
            <h1 class="flex items-center justify-center text-xl">
                <svg fill="currentColor" class="w-7 h-7" viewBox="-2 -1.5 24 24" xmlns="http://www.w3.org/2000/svg"
                    preserveAspectRatio="xMinYMin" class="jam jam-users">
                    <path
                        d='M3.534 11.07a1 1 0 1 1 .733 1.86A3.579 3.579 0 0 0 2 16.26V18a1 1 0 0 0 1 1h8a1 1 0 0 0 1-1v-1.647a3.658 3.658 0 0 0-2.356-3.419 1 1 0 1 1 .712-1.868A5.658 5.658 0 0 1 14 16.353V18a3 3 0 0 1-3 3H3a3 3 0 0 1-3-3v-1.74a5.579 5.579 0 0 1 3.534-5.19zM7 1a4 4 0 0 1 4 4v2a4 4 0 1 1-8 0V5a4 4 0 0 1 4-4zm0 2a2 2 0 0 0-2 2v2a2 2 0 1 0 4 0V5a2 2 0 0 0-2-2zm9 17a1 1 0 0 1 0-2h1a1 1 0 0 0 1-1v-1.838a3.387 3.387 0 0 0-2.316-3.213 1 1 0 1 1 .632-1.898A5.387 5.387 0 0 1 20 15.162V17a3 3 0 0 1-3 3h-1zM13 2a1 1 0 0 1 0-2 4 4 0 0 1 4 4v2a4 4 0 0 1-4 4 1 1 0 0 1 0-2 2 2 0 0 0 2-2V4a2 2 0 0 0-2-2z' />
                </svg>
                <span class="px-2">@lang('messages.user')</span>
            </h1>
        </a>
    </div>

    <div class="mx-auto px-2 sm:px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('users.partials.feedback')
            </div>

            <div class="w-full">
                @include('users.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('users.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $("#pp-dropdown, #isactive-dropdown, #search-name, #search-email").on(
                "change keyup paste",
                function() {
                    var xpp = $('#pp-dropdown option:selected').val();
                    var xisactive = $('#isactive-dropdown option:selected').val();
                    var xname = $('#search-name').val();
                    var xemail = $('#search-email').val();
                    if (!xname.trim()) {
                        xname = '_';
                    }
                    if (!xemail.trim()) {
                        xemail = '_';
                    }

                    $('#filter-loading').show();

                    var newURL = '{{ url('/admin/users') }}';
                    var newState = {
                        page: 'index-users'
                    };
                    var newTitle = '{{ __('messages.user') }}';

                    window.history.pushState(newState, newTitle, newURL);

                    $.ajax({
                        url: '{{ url('/admin/users/fetchdb') }}' + "/" + xpp + "/" + xisactive + "/" + xname + "/" +
                            xemail,
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
