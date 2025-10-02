@section('title', __('messages.production'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('production-order.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 100 100" xmlns="http://www.w3.org/2000/svg" version="1.1">
                    <path style="fill:#555555;stroke:#000000;stroke-width:1.5px;"
                        d="m 40,2 -2,9 -10,5 -8,-6 -9,9 6,9 -4,10 -10,2 0,12 10,1 4,10 -6,9 9,9 9,-6 9,4 2,10 13,0 1,-11 8,-4 9,7 9,-8 -6,-10 4,-9 11,-2 0,-12 -11,-2 -3,-9 6,-10 -9,-9 -8,6 -11,-5 -1,-9 z m 5,18 C 58,20 69,31 69,44 69,58 58,68 45,68 32,68 21,58 21,44 21,31 32,20 45,20 z" />
                    <circle style="fill:none;stroke:#eeeeee;stroke-width:3" cx="65" cy="65" r="34" />
                    <circle style="fill:#444444;fill-opacity:0.7" cx="65" cy="65" r="32" />
                    <path style="stroke:none;fill:#00C60A;fill-opacity:0.7"
                        d="m 58,33 7,34 32,-7 C 97,60 92,29 58,33" />
                    <circle style=";stroke-width:5pt;stroke:#222222;fill:none;" cx="65" cy="65" r="30" />
                    <g style="fill:#aaaaaa;">
                        <circle cx="65" cy="35" r="2.5" />
                        <circle cx="95" cy="65" r="2.5" />
                        <circle cx="65" cy="95" r="2.5" />
                        <circle cx="35" cy="65" r="2.5" />
                    </g>
                    <path style="stroke:#ffffff;stroke-width:4;fill:none;" d="M 65,65 60,42" />
                    <path style="stroke:#ffffff;stroke-width:3;fill:none;" d="M 65,65 44,87" />
                    <circle style="fill:#ffffff;" cx="65" cy="65" r="3.5" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.production')</span>
                    <span>@lang('messages.productionorder')</span>
                </div>
            </a>
        </h1>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('production-order.partials.feedback')
            </div>

            <div class="w-full">
                @include('production-order.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('production-order.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function(e) {
                $("#pp-dropdown, #produksi-dropdown, #search-tanggal, #search-nomor").on("change keyup paste",
                    function() {
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

                        var newURL = '{{ url('/production/order') }}';
                        var newState = {
                            page: 'index-production-order'
                        };
                        var newTitle = '{{ __('messages.production') }}';

                        window.history.pushState(newState, newTitle, newURL);

                        $.ajax({
                            url: '{{ url('/production/order/fetchdb') }}' + "/" + xpp + "/" + xpr + "/" +
                                xtanggal +
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

                $("#print-laporan").on("click", function(e) {
                    e.preventDefault();
                    $('#print-icon').addClass('animate-spin');
                    var xbulan = $('#bulan-dropdown option:selected').val();
                    var xtahun = $('#search-tahun').val();
                    if (!xtahun.trim()) {
                        xtahun = '_';
                    }

                    $.ajax({
                        url: "{{ url('/production/order/print-rekap') }}" + "/" + xtahun + "/" + xbulan,
                        type: 'get',
                        dataType: 'json',
                        success: function(result) {
                            if (result.status !== 'Not Found') {
                                var namafile = result.namafile;
                                $("#iframe-laporan").attr('src', namafile);
                                window.open(namafile, '_blank');
                            } else {
                                flasher.error("{{ __('messages.notfound') }}!", "Error");
                            }
                            $('#print-icon').removeClass('animate-spin');
                        }
                    });
                });

                print_one = function(xid) {
                    let aname = '#print_one-anchor-' + xid;
                    let idname = '#print_one-icon-' + xid;
                    var xbulan = $('#bulan-dropdown option:selected').val();
                    var xtahun = $('#search-tahun').val();
                    if (!xtahun.trim()) {
                        xtahun = '_';
                    }

                    $(aname).addClass('hidden');
                    $(idname).addClass('animate-spin');
                    $(idname).removeClass('hidden');

                    $.ajax({
                        url: "{{ url('/production/order/print-one') }}" + "/" + xtahun + "/" +
                            xbulan + "/" + xid,
                        type: 'get',
                        success: function(result) {
                            if (result.status !== 'Not Found') {
                                var namafile = result.namafile;
                                window.open(namafile, '_blank');
                            } else {
                                flasher.error("{{ __('messages.notfound') }}!", "Error");
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
