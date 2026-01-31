@section('title', __('messages.saleinvoice'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('sale-invoice.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 15 15" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M3.5 12.5H1.5C0.947715 12.5 0.5 12.0523 0.5 11.5V7.5C0.5 6.94772 0.947715 6.5 1.5 6.5H13.5C14.0523 6.5 14.5 6.94772 14.5 7.5V11.5C14.5 12.0523 14.0523 12.5 13.5 12.5H11.5M3.5 6.5V1.5C3.5 0.947715 3.94772 0.5 4.5 0.5H10.5C11.0523 0.5 11.5 0.947715 11.5 1.5V6.5M3.5 10.5H11.5V14.5H3.5V10.5Z"
                        stroke="currentColor" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.sale')</span>
                    <span>@lang('messages.invoice')</span>
                </div>
            </a>
        </h1>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('sale-invoice.partials.feedback')
            </div>

            <div class="w-full">
                @include('sale-invoice.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('sale-invoice.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function() {
                $("#pp-dropdown, #isactive-dropdown, #tunai-dropdown, #customer-dropdown, #search-no_order, #search-tanggal")
                    .on("change keyup paste", function() {
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

                        var newURL = '{{ url('/sale/invoice') }}';
                        var newState = {
                            page: 'index-sale-invoice'
                        };
                        var newTitle = '{{ __('messages.saleinvoice') }}';

                        window.history.pushState(newState, newTitle, newURL);

                        $.ajax({
                            url: '{{ url('/sale/invoice/fetchdb') }}' + "/" + xpp + "/" + xisactive + "/" +
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

                $("#isprintall").on("change", function() {
                    $("#list-table").find("input[type='checkbox']").prop("checked", this.checked);
                });

                $("#print-laporan").on("click", function(e) {
                    e.preventDefault();
                    $('#print-icon').addClass('animate-spin');
                    $('#print-icon').parent().prop('disabled', true);

                    $.ajax({
                        url: '{{ route('sale-invoice.print-selected') }}',
                        type: 'post',
                        dataType: 'json',
                        data: $('form#index-form').serialize(),
                        success: function(result) {
                            if (result.status ===
                                'Stok Barang Tidak Mencukupi! Tidak dapat mencetak invoice.') {
                                flasher.error(result.status, "Success");
                            }
                            if (result.status !== 'Not Found' && result.status !==
                                'Stok Barang Tidak Mencukupi! Tidak dapat mencetak invoice.') {
                                var namafile = result.namafile;
                                window.open(namafile, '_blank');
                            }
                            $('#print-icon').parent().prop('disabled', false);
                            $('#print-icon').removeClass('animate-spin');
                        }
                    });
                });

                print_one = function(xid) {
                    let aname = '#print_one-anchor-' + xid;
                    let idname = '#print_one-icon-' + xid;

                    $(aname).addClass('hidden');
                    $(idname).addClass('animate-spin');
                    $(idname).removeClass('hidden');

                    $.ajax({
                        url: '{{ url('/sale/invoice/print') }}' + '/' + xid,
                        type: 'get',
                        success: function(result) {
                            if (result.status ===
                                'Stok Barang Tidak Mencukupi! Tidak dapat mencetak invoice.') {
                                flasher.error(result.status, "Success");
                            }
                            if (result.status !== 'Not Found' && result.status !==
                                'Stok Barang Tidak Mencukupi! Tidak dapat mencetak invoice.') {
                                var namafile = result.namafile;
                                window.open(namafile, '_blank');
                            }
                            $(idname).removeClass('animate-spin');
                            $(idname).addClass('hidden');
                            $(aname).removeClass('hidden');
                        }
                    });
                };
            });
        </script>
    @endpush
</x-app-layout>
