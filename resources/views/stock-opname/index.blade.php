@section('title', __('messages.stockopname'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('stock-opname.index') }}" class="flex items-center justify-center">
                <svg class="w-7 h-7" viewBox="0 0 16 16" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink">
                    <path fill="currentColor"
                        d="M12 6v-6h-8v6h-4v7h16v-7h-4zM7 12h-6v-5h2v1h2v-1h2v5zM5 6v-5h2v1h2v-1h2v5h-6zM15 12h-6v-5h2v1h2v-1h2v5z">
                    </path>
                    <path fill="currentColor" d="M0 16h3v-1h10v1h3v-2h-16v2z"></path>
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.warehouse')</span>
                    <span>@lang('messages.stockopname')</span>
                </div>
            </a>
        </h1>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('stock-opname.partials.feedback')
            </div>

            <div class="w-full">
                @include('stock-opname.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('stock-opname.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $("#pp-dropdown, #gudang-dropdown, #search-tanggal")
                .on(
                    "change keyup paste",
                    function() {
                        var xpp = $('#pp-dropdown option:selected').val();
                        var xgudang = $('#gudang-dropdown option:selected').val();
                        var xtanggal = $('#search-tanggal').val();
                        if (!xtanggal.trim()) {
                            xtanggal = '_';
                        }

                        $('#filter-loading').show();

                        var newURL = '{{ url('/warehouse/stock-opname') }}';
                        var newState = {
                            page: 'index-stock-opname'
                        };
                        var newTitle = '{{ __('messages.stockopname') }}';

                        window.history.pushState(newState, newTitle, newURL);

                        $.ajax({
                            url: '{{ url('/warehouse/stock-opname/fetchdb') }}' + "/" + xpp + "/" + xgudang + "/" +
                                xtanggal,
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
