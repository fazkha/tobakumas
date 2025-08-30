@section('title', __('messages.brandivjabkab'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('brandivjabkab.index') }}" class="flex items-center justify-center">
                <svg class="size-7" version="1.1" xmlns="http://www.w3.org/2000/svg"
                    xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 329.966 329.966"
                    style="enable-background:new 0 0 329.966 329.966;" xml:space="preserve">
                    <path id="XMLID_822_" d="M218.317,139.966h-38.334v-45V15c0-8.284-6.716-15-15-15h-120c-8.284,0-15,6.716-15,15v79.966
c0,8.284,6.716,15,15,15h105v30h-38.334c-52.383,0-95,42.617-95,95s42.617,95,95,95h106.668c52.383,0,95-42.617,95-95
S270.7,139.966,218.317,139.966z M59.983,79.966V30h90v49.966H59.983z M218.317,299.966H111.649c-35.841,0-65-29.159-65-65
s29.159-65,65-65h38.334v65c0,8.284,6.716,15,15,15c8.284,0,15-6.716,15-15v-65h38.334c35.841,0,65,29.159,65,65
S254.158,299.966,218.317,299.966z" />
                </svg>
                <div class="relative px-2 pt-2">
                    <span class="absolute top-0 left-2 text-xs w-40">@lang('messages.marketing')</span>
                    <span>@lang('messages.brandivjabkab')</span>
                </div>
            </a>
        </h1>
    </div>

    <div class="mx-auto px-4 py-2">
        <div class="flex flex-col items-center">

            <div class="w-full" role="alert">
                @include('brandivjabkab.partials.feedback')
            </div>

            <div class="w-full">
                @include('brandivjabkab.partials.filter')
            </div>

            <div id="table-container" class="w-full">
                @include('brandivjabkab.partials.table')
            </div>

        </div>
    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script>
            $("#pp-dropdown, #isactive-dropdown, #propinsi-dropdown, #kabupaten-dropdown").on(
                "change keyup paste",
                function() {
                    var xpp = $('#pp-dropdown option:selected').val();
                    var xisactive = $('#isactive-dropdown option:selected').val();
                    var xprop = $('#propinsi-dropdown option:selected').val();
                    var xkab = $('#kabupaten-dropdown option:selected').val();

                    $('#filter-loading').show();

                    var newURL = '{{ url('/marketing/brandivjabkab') }}';
                    var newState = {
                        page: 'index-brandivjabkab'
                    };
                    var newTitle = '{{ __('messages.brandivjabkab') }}';

                    window.history.pushState(newState, newTitle, newURL);

                    $.ajax({
                        url: '{{ url('/marketing/brandivjabkab/fetchdb') }}' + "/" + xpp + "/" + xisactive + "/" +
                            xprop + "/" + xkab,
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
