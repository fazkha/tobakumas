@section('title', __('messages.goods'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('goods.index') }}" class="flex items-center justify-center">
                <svg fill="currentColor" class="w-7 h-7" viewBox="0 0 52 52" xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="m45.2 19.6a1.6 1.6 0 0 1 1.59 1.45v22.55a4.82 4.82 0 0 1 -4.59 4.8h-32.2a4.82 4.82 0 0 1 -4.8-4.59v-22.61a1.6 1.6 0 0 1 1.45-1.59h38.55zm-12.39 6.67-.11.08-9.16 9.93-4.15-4a1.2 1.2 0 0 0 -1.61-.08l-.1.08-1.68 1.52a1 1 0 0 0 -.09 1.44l.09.1 5.86 5.55a2.47 2.47 0 0 0 1.71.71 2.27 2.27 0 0 0 1.71-.71l4.9-5.16.39-.41.52-.55 5-5.3a1.25 1.25 0 0 0 .11-1.47l-.07-.09-1.72-1.54a1.19 1.19 0 0 0 -1.6-.1zm12.39-22.67a4.81 4.81 0 0 1 4.8 4.8v4.8a1.6 1.6 0 0 1 -1.6 1.6h-44.8a1.6 1.6 0 0 1 -1.6-1.6v-4.8a4.81 4.81 0 0 1 4.8-4.8z" />
                </svg>
                <div class="relative px-2">
                    <span class="absolute -top-2 left-2 text-xs">@lang('messages.warehouse')</span>
                    <span>@lang('messages.goods')</span>
                </div>
            </a>
        </h1>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('barang.partials.feedback')
            </div>

            <div class="w-full">
                @include('barang.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('barang.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $("#pp-dropdown, #isactive-dropdown, #satuan-dropdown, #jenis_barang-dropdown, #search-nama, #search-merk, #search-keterangan")
                .on(
                    "change keyup paste",
                    function() {
                        var xpp = $('#pp-dropdown option:selected').val();
                        var xisactive = $('#isactive-dropdown option:selected').val();
                        var xsatuan = $('#satuan-dropdown option:selected').val();
                        var xjenis_barang = $('#jenis_barang-dropdown option:selected').val();
                        var xnama = $('#search-nama').val();
                        var xmerk = $('#search-merk').val();
                        if (!xnama.trim()) {
                            xnama = '_';
                        }
                        if (!xmerk.trim()) {
                            xmerk = '_';
                        }

                        $('#filter-loading').show();

                        var newURL = '{{ url('/warehouse/goods') }}';
                        var newState = {
                            page: 'index-barang'
                        };
                        var newTitle = '{{ __('messages.goods') }}';

                        window.history.pushState(newState, newTitle, newURL);

                        $.ajax({
                            url: '{{ url('/warehouse/goods/fetchdb') }}' + "/" + xpp + "/" + xisactive + "/" +
                                xsatuan +
                                "/" + xjenis_barang + "/" + xnama + "/" + xmerk,
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
