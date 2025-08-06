@section('title', __('messages.saleorder'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <a href="{{ route('sale-order.index') }}">
            <h1 class="flex items-center justify-center text-xl">
                <svg fill="currentColor" class="w-7 h-7" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"
                    data-name="Layer 1">
                    <path
                        d="M21.22,12A3,3,0,0,0,22,10a3,3,0,0,0-3-3H13.82A3,3,0,0,0,11,3H5A3,3,0,0,0,2,6a3,3,0,0,0,.78,2,3,3,0,0,0,0,4,3,3,0,0,0,0,4A3,3,0,0,0,2,18a3,3,0,0,0,3,3H19a3,3,0,0,0,2.22-5,3,3,0,0,0,0-4ZM11,19H5a1,1,0,0,1,0-2h6a1,1,0,0,1,0,2Zm0-4H5a1,1,0,0,1,0-2h6a1,1,0,0,1,0,2Zm0-4H5A1,1,0,0,1,5,9h6a1,1,0,0,1,0,2Zm0-4H5A1,1,0,0,1,5,5h6a1,1,0,0,1,0,2Zm8.69,11.71A.93.93,0,0,1,19,19H13.82a2.87,2.87,0,0,0,0-2H19a1,1,0,0,1,1,1A1,1,0,0,1,19.69,18.71Zm0-4A.93.93,0,0,1,19,15H13.82a2.87,2.87,0,0,0,0-2H19a1,1,0,0,1,1,1A1,1,0,0,1,19.69,14.71Zm0-4A.93.93,0,0,1,19,11H13.82a2.87,2.87,0,0,0,0-2H19a1,1,0,0,1,1,1A1,1,0,0,1,19.69,10.71Z" />
                </svg>
                <span class="px-2">@lang('messages.saleorder')</span>
            </h1>
        </a>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('sale-order.partials.feedback')
            </div>

            <div class="w-full">
                @include('sale-order.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('sale-order.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $("#pp-dropdown, #isactive-dropdown, #tunai-dropdown, #customer-dropdown, #search-no_order, #search-tanggal")
                .on(
                    "change keyup paste",
                    function() {
                        var xpp = $('#pp-dropdown option:selected').val();
                        var xisactive = $('#isactive-dropdown option:selected').val();
                        var xtunai = $('#tunai-dropdown option:selected').val();
                        var xcustomer = $('#customer-dropdown option:selected').val();
                        var xno_order = $('#search-no_order').val();
                        var xtanggal = $('#search-tanggal').val();
                        if (!xno_order.trim()) {
                            xno_order = '_';
                        }
                        if (!xtanggal.trim()) {
                            xtanggal = '_';
                        }

                        $('#filter-loading').show();

                        var newURL = '{{ url('/sale/order') }}';
                        var newState = {
                            page: 'index-sale-order'
                        };
                        var newTitle = '{{ __('messages.saleorder') }}';

                        window.history.pushState(newState, newTitle, newURL);

                        $.ajax({
                            url: '{{ url('/sale/order/fetchdb') }}' + "/" + xpp + "/" + xisactive + "/" +
                                xtunai + "/" + xcustomer + "/" + xno_order + "/" + xtanggal,
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
