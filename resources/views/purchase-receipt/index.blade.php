@section('title', __('messages.goodsreceipt'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('purchase-receipt.index') }}" class="flex items-center justify-center">
                <svg class="size-7" version="1.1" id="_x32_" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 512 512" xml:space="preserve">
                    <style type="text/css">
                        .st0 {
                            fill: currentColor;
                        }
                    </style>
                    <g>
                        <path class="st0"
                            d="M447.77,33.653c-36.385-5.566-70.629,15.824-82.588,49.228h-44.038v37.899h40.902 c5.212,31.372,29.694,57.355,62.855,62.436c41.278,6.316,79.882-22.042,86.222-63.341C517.428,78.575,489.07,39.969,447.77,33.653z" />
                        <path class="st0"
                            d="M162.615,338.222c0-6.88-5.577-12.468-12.468-12.468H96.16c-6.891,0-12.467,5.588-12.467,12.468 c0,6.868,5.576,12.467,12.467,12.467h53.988C157.038,350.689,162.615,345.091,162.615,338.222z" />
                        <path class="st0"
                            d="M392.999,237.965L284.273,340.452l-37.966,9.398v-86.619H0v215.996h246.307v-59.454l35.547-5.732 c16.95-2.418,29.396-6.692,44.336-15.018l46.302-24.228v104.432h132.435V270.828C504.927,202.618,428.016,202.43,392.999,237.965z M215.996,448.913H30.313v-155.37h185.683v63.805l-36.419,9.01c-15.968,4.395-25.708,20.518-22.174,36.696l0.298,1.247 c3.478,15.912,18.651,26.436,34.785,24.14l23.51-3.788V448.913z" />
                    </g>
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.warehouse')</span>
                    <span>@lang('messages.goodsreceipt')</span>
                </div>
            </a>
        </h1>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('purchase-receipt.partials.feedback')
            </div>

            <div class="w-full">
                @include('purchase-receipt.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('purchase-receipt.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            $("#pp-dropdown, #isactive-dropdown, #tunai-dropdown, #supplier-dropdown, #search-no_order, #search-tanggal")
                .on(
                    "change keyup paste",
                    function() {
                        var xpp = $('#pp-dropdown option:selected').val();
                        var xisactive = $('#isactive-dropdown option:selected').val();
                        var xtunai = $('#tunai-dropdown option:selected').val();
                        var xsupplier = $('#supplier-dropdown option:selected').val();
                        var xno_order = $('#search-no_order').val();
                        var xtanggal = $('#search-tanggal').val();
                        if (!xno_order.trim()) {
                            xno_order = '_';
                        }
                        if (!xtanggal.trim()) {
                            xtanggal = '_';
                        }

                        $('#filter-loading').show();

                        var newURL = '{{ url('/warehouse/purchase-receipt') }}';
                        var newState = {
                            page: 'index-purchase-receipt'
                        };
                        var newTitle = '{{ __('messages.goodsreceipt') }}';

                        window.history.pushState(newState, newTitle, newURL);

                        $.ajax({
                            url: '{{ url('/warehouse/purchase-receipt/fetchdb') }}' + "/" + xpp + "/" + xisactive +
                                "/" +
                                xtunai + "/" + xsupplier + "/" + xno_order + "/" + xtanggal,
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
