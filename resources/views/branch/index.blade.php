@section('title', __('messages.branch'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('branch.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="size-7" viewBox="0 0 1024 1024" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M815 576h145c35 0 64 29 64 64v320c0 35-29 64-64 64H640c-35 0-64-29-64-64V640c0-35 29-64 64-64h113v-38H270v38h114c35 0 64 29 64 64v320c0 35-29 64-64 64H64c-35 0-64-29-64-64V640c0-35 29-64 64-64h144v-60c0-22 28-33 53-33h220v-36H343c-35 0-64-29-64-64V63c0-35 29-64 64-64h320c35 0 64 29 64 64v320c0 35-29 64-64 64H545v37c83 0 134-1 217-1 25 0 53 10 53 33v60zm145 64H640v320h320V640zM663 63H343v320h320V63zM384 640H64v320h320V640z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.generalaffair')</span>
                    <span>@lang('messages.branch')</span>
                </div>
            </a>
        </h1>
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
        <script type="text/javascript">
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
