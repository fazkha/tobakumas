@section('title', __('messages.announcement'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('announcement.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path fill="none" stroke="currentColor" stroke-width="2"
                        d="M11,15 C14,15 19,19 19,19 L19,3 C19,3 14,7 11,7 C11,7 11,15 11,15 Z M5,15 L8,23 L12,23 L9,15 M19,14 C20.657,14 22,12.657 22,11 C22,9.343 20.657,8 19,8 M11,19 C11.9999997,18.9999994 14,18 14,16 M2,11 C2,7.88888889 3.7912,7 6,7 L11,7 L11,15 L6,15 C3.7912,15 2,14.1111111 2,11 Z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.humanresource')</span>
                    <span>@lang('messages.announcement')</span>
                </div>
            </a>
        </h1>
    </div>

    <div id="mainDiv" x-data="{
        openModal: false,
        modalTitle: 'Title'
    }" class="mx-auto px-4 py-2">

        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('pengumuman.partials.feedback')
            </div>

            <div class="w-full">
                @include('pengumuman.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('pengumuman.partials.table')
            </div>

        </div>

        <div x-show.transition.duration.500ms="openModal"
            class="fixed inset-0 flex items-center justify-center px-4 md:px-0 bg-white bg-opacity-75 dark:bg-black dark:bg-opacity-75">
            <div @click.away="openModal = false"
                class="flex flex-col p-2 h-full w-full shadow-2xl rounded-lg border-1 border-primary-100 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                <div class="flex justify-between mb-2">
                    <div class="font-bold text-lg text-gray-900 dark:text-gray-50"><span x-html="modalTitle"></span>
                    </div>
                    <button @click="openModal = false">
                        <svg class="w-5 h-5 text-gray-900 dark:text-gray-50" viewBox="0 0 24 24" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5.293 5.293a1 1 0 0 1 1.414 0L12 10.586l5.293-5.293a1 1 0 1 1 1.414 1.414L13.414 12l5.293 5.293a1 1 0 0 1-1.414 1.414L12 13.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L10.586 12 5.293 6.707a1 1 0 0 1 0-1.414z"
                                fill="currentColor" />
                        </svg>
                    </button>
                </div>
                <div class="flex items-center justify-center overflow-hidden rounded-lg h-full">
                    <iframe id="iframe-laporan" src="" frameborder="0"
                        style="width:100%; height:100%;"></iframe>
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function(e) {
                $("#pp-dropdown, #isactive-dropdown, #search-judul, #search-keterangan")
                    .on("change keyup paste", function() {
                        var xpp = $('#pp-dropdown option:selected').val();
                        var xisactive = $('#isactive-dropdown option:selected').val();
                        var xjudul = $('#search-judul').val();
                        var xketerangan = $('#search-keterangan').val();
                        if (!xjudul.trim()) {
                            xjudul = '_';
                        }
                        if (!xketerangan.trim()) {
                            xketerangan = '_';
                        }

                        $('#filter-loading').show();

                        var newURL = '{{ url('/human-resource/announcement') }}';
                        var newState = {
                            page: 'index-pengumuman'
                        };
                        var newTitle = '{{ __('messages.announcement') }}';

                        window.history.pushState(newState, newTitle, newURL);

                        $.ajax({
                            url: "{{ url('/human-resource/announcement/fetchdb') }}" + "/" + xpp + "/" +
                                xisactive + "/" + xjudul + "/" + xketerangan,
                            type: "GET",
                            dataType: 'json',
                            success: function(result) {
                                $('#table-container').html(result);
                                $("#table-container").focus();
                                $('#filter-loading').hide();
                            }
                        });
                    });

            });
        </script>
    @endpush
</x-app-layout>
