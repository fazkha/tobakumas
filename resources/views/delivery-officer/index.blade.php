@section('title', __('messages.brandivjabkec'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('area-officer.index') }}" class="flex items-center justify-center">
                <svg class="size-7" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <g id="style=linear">
                        <g id="notification-direct">
                            <path id="vector"
                                d="M2.99219 14L6.49219 14C7.1217 14 7.71448 14.2964 8.09219 14.8L9.14219 16.2C9.5199 16.7036 10.1127 17 10.7422 17L11.9922 17L13.2422 17C13.8717 17 14.4645 16.7036 14.8422 16.2L15.8922 14.8C16.2699 14.2964 16.8627 14 17.4922 14L20.9922 14"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <path id="vector_2"
                                d="M13.8469 2.75L8.74219 2.75C5.42848 2.75 2.74219 5.43629 2.74219 8.75L2.74219 15.2578C2.74219 18.5715 5.42848 21.2578 8.74218 21.2578L15.25 21.2578C18.5637 21.2578 21.25 18.5715 21.25 15.2578L21.25 10.1531"
                                stroke="currentColor" stroke-width="1.5" stroke-linecap="round" />
                            <circle id="vector_3" cx="18.4738" cy="5.52617" r="2.77617" stroke="currentColor"
                                stroke-width="1.5" />
                        </g>
                    </g>
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.delivery')</span>
                    <span>@lang('messages.brandivjabkec')</span>
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
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script type="text/javascript">
            $(document).ready(function(e) {
                $("#pp-dropdown, #isactive-dropdown, #propinsi-dropdown, #kabupaten-dropdown").on(
                    "change keyup paste",
                    function() {
                        var xpp = $('#pp-dropdown option:selected').val();
                        var xisactive = $('#isactive-dropdown option:selected').val();
                        var xprop = $('#propinsi-dropdown option:selected').val();
                        var xkab = $('#kabupaten-dropdown option:selected').val();

                        $('#filter-loading').show();

                        var newURL = '{{ url('/delivery/officer') }}';
                        var newState = {
                            page: 'index-brandivjabkec'
                        };
                        var newTitle = '{{ __('messages.brandivjabkec') }}';

                        window.history.pushState(newState, newTitle, newURL);

                        $.ajax({
                            url: '{{ url('/delivery/officer/fetchdb') }}' + "/" + xpp + "/" + xisactive +
                                "/" + xprop + "/" + xkab,
                            type: "GET",
                            dataType: 'json',
                            success: function(result) {
                                $('#table-container').html(result);
                                $("#table-container").focus();
                                $('#filter-loading').hide();
                            }
                        });
                    }
                );

                // $("#propinsi-dropdown").on("change keyup paste", function() {
                //     var xpr = $('#propinsi-dropdown option:selected').val();
                //     if (xpr.trim()) {
                //         xprop = xpr;
                //     } else {
                //         xprop = '_';
                //     }

                //     $.ajax({
                //         url: '{{ url('/marketing/kecamatan/depend-drop-kab') }}' + "/" + xprop,
                //         type: "GET",
                //         dataType: 'json',
                //         success: function(result) {
                //             $('#kabupaten-dropdown').empty();
                //             $('#kecamatan-dropdown').empty();
                //             $('#kabupaten-dropdown').append($('<option>', {
                //                 value: null,
                //                 text: "{{ __('messages.choose') }}..."
                //             }));
                //             var data = result.kabs;
                //             $.each(data, function(item, index) {
                //                 $('#kabupaten-dropdown').append($('<option>', {
                //                     value: index,
                //                     text: item,
                //                     selected: (index ===
                //                         {{ old('kabupaten-dropdown') }} ?
                //                         true : false)
                //                 }));
                //             });
                //             $("#kabupaten-dropdown").focus();
                //         }
                //     });
                // });

                // $("#kabupaten-dropdown").on("change keyup paste", function() {
                //     var xpr = $('#propinsi-dropdown option:selected').val();
                //     if (xpr.trim()) {
                //         xprop = xpr;
                //     } else {
                //         xprop = '_';
                //     }
                //     var xkb = $('#kabupaten-dropdown option:selected').val();
                //     if (xkb.trim()) {
                //         xkab = xkb;
                //     } else {
                //         xkab = '_';
                //     }

                //     $.ajax({
                //         url: '{{ url('/marketing/kecamatan/depend-drop-kec') }}' + "/" + xprop + "/" +
                //             xkab,
                //         type: "GET",
                //         dataType: 'json',
                //         success: function(result) {
                //             $('#kecamatan-dropdown').empty();
                //             $('#kecamatan-dropdown').append($('<option>', {
                //                 value: null,
                //                 text: "{{ __('messages.choose') }}..."
                //             }));
                //             var data = result.kecs;
                //             $.each(data, function(item, index) {
                //                 $('#kecamatan-dropdown').append($('<option>', {
                //                     value: index,
                //                     text: item,
                //                     selected: (index ===
                //                         {{ old('kecamatan-dropdown') }} ?
                //                         true : false)
                //                 }));
                //             });
                //             $("#kecamatan-dropdown").focus();
                //         }
                //     });
                // });
            });
        </script>
    @endpush
</x-app-layout>
