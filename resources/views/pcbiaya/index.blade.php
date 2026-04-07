@section('title', __('messages.pcbiaya'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('pcbiaya.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g clip-path="url(#clip0_901_1341)">
                        <path
                            d="M15 17C15 15.343 13.657 14 12 14M12 14C10.343 14 9 15.343 9 17C9 18.657 10.343 20 12 20C13.657 20 15 21.343 15 23C15 24.657 13.657 26 12 26M12 14V13M12 26C10.343 26 9 24.657 9 23M12 26V27M22 31H31V29M25 26H31V24M26 21H31V19M26 16H31V14M23 11H31V9M10 6H31V1H7V6M23 20C23 13.926 18.074 9 12 9C5.926 9 1 13.926 1 20C1 26.074 5.926 31 12 31C18.074 31 23 26.074 23 20Z"
                            stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" />
                    </g>
                    <defs>
                        <clipPath id="clip0_901_1341">
                            <rect width="32" height="32" fill="white" />
                        </clipPath>
                    </defs>
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.finance')</span>
                    <span>@lang('messages.pcbiaya')</span>
                </div>
            </a>
        </h1>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('pcbiaya.partials.feedback')
            </div>

            <div class="w-full">
                @include('pcbiaya.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('pcbiaya.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            $("#pp-dropdown, #branch-dropdown, #search-tanggal").on(
                "change keyup paste",
                function() {
                    var xpp = $('#pp-dropdown option:selected').val();
                    var xbr = $('#branch-dropdown option:selected').val();
                    var xtanggal = $('#search-tanggal').val();
                    if (!xtanggal.trim()) {
                        xtanggal = '_';
                    }

                    $('#filter-loading').show();

                    var newURL = '{{ url('/finance/pcbiaya') }}';
                    var newState = {
                        page: 'index-pcbiaya'
                    };
                    var newTitle = '{{ __('messages.pcbiaya') }}';

                    window.history.pushState(newState, newTitle, newURL);

                    $.ajax({
                        url: '{{ url('/finance/pcbiaya/fetchdb') }}' + "/" + xpp + "/" + xbr + "/" + xtanggal,
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
