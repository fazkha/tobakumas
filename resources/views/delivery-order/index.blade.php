@section('title', __('messages.delivery'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('delivery-order.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M20 33L26 35C26 35 41 32 43 32C45 32 45 34 43 36C41 38 34 44 28 44C22 44 18 41 14 41C10 41 4 41 4 41"
                        stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M4 29C6 27 10 24 14 24C18 24 27.5 28 29 30C30.5 32 26 35 26 35" stroke="currentColor"
                        stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    <path d="M16 18V10C16 8.89543 16.8954 8 18 8H42C43.1046 8 44 8.89543 44 10V26" stroke="currentColor"
                        stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    <rect x="25" y="8" width="10" height="9" fill="#2F88FF" stroke="currentColor"
                        stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.delivery')</span>
                    <span>@lang('messages.order')</span>
                </div>
            </a>
        </h1>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('delivery-order.partials.feedback')
            </div>

            <div class="w-full">
                @include('delivery-order.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('delivery-order.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $("#pp-dropdown, #produksi-dropdown, #search-tanggal, #search-nomor").on("change keyup paste", function() {
                var xpp = $('#pp-dropdown option:selected').val();
                var xpr = $('#produksi-dropdown option:selected').val();
                var xtanggal = $('#search-tanggal').val();
                var xnomor = $('#search-nomor').val();
                if (!xtanggal.trim()) {
                    xtanggal = '_';
                }
                if (!xnomor.trim()) {
                    xnomor = '_';
                }

                $('#filter-loading').show();

                var newURL = '{{ url('/delivery/order') }}';
                var newState = {
                    page: 'index-delivery-order'
                };
                var newTitle = '{{ __('messages.delivery') }}';

                window.history.pushState(newState, newTitle, newURL);

                $.ajax({
                    url: '{{ url('/delivery/order/fetchdb') }}' + "/" + xpp + "/" + xpr + "/" + xtanggal +
                        "/" + xnomor,
                    type: "get",
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
