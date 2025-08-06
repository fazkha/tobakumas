@section('title', __('messages.warehouse'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <a href="{{ route('gudang.index') }}">
            <h1 class="flex items-center justify-center text-xl">
                <svg fill="currentColor" class="w-7 h-7" viewBox="0 0 15 15" version="1.1" id="warehouse"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M13.5,5c-0.0762,0.0003-0.1514-0.0168-0.22-0.05L7.5,2L1.72,4.93C1.4632,5.0515,1.1565,4.9418,1.035,4.685&#xA;&#x9;S1.0232,4.1215,1.28,4L7.5,0.92L13.72,4c0.2761,0.0608,0.4508,0.3339,0.39,0.61C14.0492,4.8861,13.7761,5.0608,13.5,5z M5,10H2v3h3&#xA;&#x9;V10z M9,10H6v3h3V10z M13,10h-3v3h3V10z M11,6H8v3h3V6z M7,6H4v3h3V6z" />
                </svg>
                <span class="px-2">@lang('messages.location')</span>
            </h1>
        </a>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('gudang.partials.feedback')
            </div>

            <div class="w-full">
                @include('gudang.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('gudang.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $("#pp-dropdown, #isactive-dropdown, #search-kode, #search-nama, #search-alamat").on(
                "change keyup paste",
                function() {
                    var xpp = $('#pp-dropdown option:selected').val();
                    var xisactive = $('#isactive-dropdown option:selected').val();
                    var xkode = $('#search-kode').val();
                    var xnama = $('#search-nama').val();
                    var xalamat = $('#search-alamat').val();
                    if (!xkode.trim()) {
                        xkode = '_';
                    }
                    if (!xnama.trim()) {
                        xnama = '_';
                    }
                    if (!xalamat.trim()) {
                        xalamat = '_';
                    }

                    $('#filter-loading').show();

                    var newURL = '{{ url('/warehouse/gudang') }}';
                    var newState = {
                        page: 'index-gudang'
                    };
                    var newTitle = '{{ __('messages.warehouse') }}';

                    window.history.pushState(newState, newTitle, newURL);

                    $.ajax({
                        url: '{{ url('/warehouse/gudang/fetchdb') }}' + "/" + xpp + "/" + xisactive + "/" + xkode +
                            "/" + xnama + "/" + xalamat,
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
