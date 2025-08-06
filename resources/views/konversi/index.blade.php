@section('title', __('messages.conversion'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <a href="{{ route('units.index') }}">
            <h1 class="flex items-center justify-center text-xl">
                <svg class="w-7 h-7" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 204.045 204.045"
                    style="enable-background:new 0 0 204.045 204.045;" xml:space="preserve">
                    <g>
                        <g>
                            <path style="fill:#010002;"
                                d="M5.239,97.656c0-23.577,19.186-42.764,42.771-42.764h146.661l-38.931,38.931l3.461,3.461
       l44.843-44.843L159.202,7.601l-3.461,3.464l38.931,38.924H48.01c-26.287,0-47.663,21.387-47.663,47.663v0.494h4.896v-0.49H5.239z" />
                            <path style="fill:#010002;" d="M198.805,106.388c0,23.577-19.19,42.764-42.767,42.764H9.377l38.931-38.931l-3.461-3.461L0,151.604
       l44.843,44.839l3.461-3.468L9.377,154.052h146.661c26.283,0,47.663-21.387,47.663-47.663v-0.494h-4.896V106.388z" />
                        </g>
                    </g>
                </svg>
                <span class="px-2">@lang('messages.conversion')</span>
            </h1>
        </a>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('konversi.partials.feedback')
            </div>

            <div class="w-full">
                @include('konversi.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('konversi.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $("#pp-dropdown, #isactive-dropdown").on(
                "change keyup paste",
                function() {
                    var xpp = $('#pp-dropdown option:selected').val();
                    var xisactive = $('#isactive-dropdown option:selected').val();

                    $('#filter-loading').show();

                    var newURL = '{{ url('/warehouse/conversions') }}';
                    var newState = {
                        page: 'index-konversi'
                    };
                    var newTitle = '{{ __('messages.conversion') }}';

                    window.history.pushState(newState, newTitle, newURL);

                    $.ajax({
                        url: '{{ url('/warehouse/conversions/fetchdb') }}' + "/" + xpp + "/" + xisactive,
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
