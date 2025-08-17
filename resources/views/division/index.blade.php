@section('title', __('messages.branch'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <a href="{{ route('branch.index') }}">
            <h1 class="flex items-center justify-center text-xl">
                <svg class="size-7" viewBox="0 0 1024 1024" t="1569683632175" class="icon" version="1.1"
                    xmlns="http://www.w3.org/2000/svg" p-id="12593" xmlns:xlink="http://www.w3.org/1999/xlink">
                    <defs>
                        <style type="text/css"></style>
                    </defs>
                    <path
                        d="M640.6 429.8h257.1c7.9 0 14.3-6.4 14.3-14.3V158.3c0-7.9-6.4-14.3-14.3-14.3H640.6c-7.9 0-14.3 6.4-14.3 14.3v92.9H490.6c-3.9 0-7.1 3.2-7.1 7.1v221.5h-85.7v-96.5c0-7.9-6.4-14.3-14.3-14.3H126.3c-7.9 0-14.3 6.4-14.3 14.3v257.2c0 7.9 6.4 14.3 14.3 14.3h257.1c7.9 0 14.3-6.4 14.3-14.3V544h85.7v221.5c0 3.9 3.2 7.1 7.1 7.1h135.7v92.9c0 7.9 6.4 14.3 14.3 14.3h257.1c7.9 0 14.3-6.4 14.3-14.3v-257c0-7.9-6.4-14.3-14.3-14.3h-257c-7.9 0-14.3 6.4-14.3 14.3v100h-78.6v-393h78.6v100c0 7.9 6.4 14.3 14.3 14.3z m53.5-217.9h150V362h-150V211.9zM329.9 587h-150V437h150v150z m364.2 75.1h150v150.1h-150V662.1z"
                        p-id="12594"></path>
                </svg>
                <span class="px-2">@lang('messages.branch')</span>
            </h1>
        </a>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('branch.partials.feedback')
            </div>

            <div class="w-full">
                @include('branch.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('branch.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $("#pp-dropdown, #isactive-dropdown, #search-nama, #search-alamat").on(
                "change keyup paste",
                function() {
                    var xpp = $('#pp-dropdown option:selected').val();
                    var xisactive = $('#isactive-dropdown option:selected').val();
                    var xnama = $('#search-nama').val();
                    var xalamat = $('#search-alamat').val();
                    if (!xnama.trim()) {
                        xnama = '_';
                    }
                    if (!xalamat.trim()) {
                        xalamat = '_';
                    }

                    $('#filter-loading').show();

                    var newURL = '{{ url('/general-affair/branch') }}';
                    var newState = {
                        page: 'index-branch'
                    };
                    var newTitle = '{{ __('messages.branch') }}';

                    window.history.pushState(newState, newTitle, newURL);

                    $.ajax({
                        url: '{{ url('/general-affair/branch/fetchdb') }}' + "/" + xpp + "/" + xisactive + "/" +
                            xnama + "/" + xalamat,
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
