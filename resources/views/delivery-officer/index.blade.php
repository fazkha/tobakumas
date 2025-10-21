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
                @include('delivery-officer.partials.feedback')
            </div>

            <div class="w-full">
                @include('delivery-officer.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('delivery-officer.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function(e) {
                $("#pp-dropdown, #isdone-dropdown, #propinsi-dropdown, #kabupaten-dropdown").on(
                    "change keyup paste",
                    function() {
                        var xpp = $('#pp-dropdown option:selected').val();
                        var xisdone = $('#isdone-dropdown option:selected').val();
                        var xprop = $('#propinsi-dropdown option:selected').val();
                        var xkab = $('#kabupaten-dropdown option:selected').val();

                        $('#filter-loading').show();

                        var newURL = '{{ url('/delivery/order') }}';
                        var newState = {
                            page: 'index-delivery-order'
                        };
                        var newTitle = '{{ __('messages.delivery') }}';

                        window.history.pushState(newState, newTitle, newURL);

                        $.ajax({
                            url: '{{ url('/delivery/order/fetchdb') }}' + "/" + xpp + "/" + xisdone +
                                "/" + xprop + "/" + xkab,
                            type: "GET",
                            dataType: 'json',
                            success: function(result) {
                                $('#table-container').html(result);
                                $("#table-container").focus();
                                $('#filter-loading').hide();
                            }
                        });

                        $.ajax({
                            url: '{{ url('/marketing/kecamatan/depend-drop-kab') }}' + "/" + xprop,
                            type: "GET",
                            dataType: 'json',
                            success: function(result) {
                                $('#kabupaten-dropdown').empty();
                                $('#kabupaten-dropdown').append($('<option>', {
                                    value: 'all',
                                    text: "{{ __('messages.all') }}"
                                }));
                                var data = result.kabs;
                                $.each(data, function(item, index) {
                                    $('#kabupaten-dropdown').append($('<option>', {
                                        value: index,
                                        text: item,
                                        selected: (index == xkab ? true : false)
                                    }));
                                });
                                $("#kabupaten-dropdown").focus();
                            }
                        });
                    }
                );

                $("#print-rekap").on("click", function(e) {
                    e.preventDefault();
                    $('#print-icon').addClass('animate-spin');
                    var xbulan = $('#bulan-dropdown option:selected').val();
                    var xtahun = $('#search-tahun').val();
                    if (!xtahun.trim()) {
                        xtahun = '_';
                        xbulan = 'all';
                        $("#bulan-dropdown").val("all");
                    }

                    $.ajax({
                        url: "{{ url('/delivery/order/print-rekap') }}" + "/" + xtahun + "/" + xbulan,
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
                        url: "{{ url('/delivery/order/print-one') }}" + "/" + xtahun + "/" + xbulan + "/" +
                            xid,
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
