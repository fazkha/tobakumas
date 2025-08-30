@php
    use Illuminate\Support\Facades\Crypt;
@endphp
@section('title', __('messages.chartofaccount'))

<x-app-layout>
    <div class="flex items-center justify-between px-4 py-4 border-b border-primary-100 lg:py-6 dark:border-primary-800">
        <h1 class="text-xl flex items-center justify-center">
            <a href="{{ route('coa.index') }}" class="flex items-center justify-center">
                <svg class="w-7 h-7" viewBox="0 0 1024 1024" fill="currentColor" class="icon" version="1.1"
                    xmlns="http://www.w3.org/2000/svg">
                    <path
                        d="M53.6 1023.2c-6.4 0-12.8-2.4-17.6-8-4.8-4.8-7.2-11.2-6.4-18.4L80 222.4c0.8-12.8 11.2-22.4 24-22.4h211.2v-3.2c0-52.8 20.8-101.6 57.6-139.2C410.4 21.6 459.2 0.8 512 0.8c108 0 196.8 88 196.8 196.8 0 0.8-0.8 1.6-0.8 2.4v0.8H920c12.8 0 23.2 9.6 24 22.4l49.6 768.8c0.8 2.4 0.8 4 0.8 6.4-0.8 13.6-11.2 24.8-24.8 24.8H53.6z m25.6-48H944l-46.4-726.4H708v57.6h0.8c12.8 8.8 20 21.6 20 36 0 24.8-20 44.8-44.8 44.8s-44.8-20-44.8-44.8c0-14.4 7.2-27.2 20-36h0.8v-57.6H363.2v57.6h0.8c12.8 8.8 20 21.6 20 36 0 24.8-20 44.8-44.8 44.8-24.8 0-44.8-20-44.8-44.8 0-14.4 7.2-27.2 20-36h0.8v-57.6H125.6l-46.4 726.4zM512 49.6c-81.6 0-148.8 66.4-148.8 148.8v3.2h298.4l-0.8-1.6v-1.6c0-82.4-67.2-148.8-148.8-148.8z"
                        fill="" />
                </svg>
                <span class="px-2">@lang('messages.chartofaccount')</span>
            </a>
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">Modify</span>
        </h1>
    </div>

    <div x-data="{
        openModal: false,
        modalTitle: 'Choices',
        imagePreview: '/images/0cd6be830e32f80192d496e50cfa9dbc.jpg',
        imageOrientation: 'p',
        openModalImage: false,
        questionIndex: 0,
        questionId: 0,
    }">
        <form id="coa-form" action="{{ route('coa.update', Crypt::Encrypt($datas->id)) }}" method="POST"
            enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="py-2 flex flex-col">

                <div class="w-full">
                    <div class="flex flex-col items-center">

                        <div class="w-full" role="alert">
                            @include('coa.partials.feedback')
                        </div>

                        <div
                            class="w-full shadow-lg rounded-md border bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                            <div class="p-4 space-y-2">

                                <div class="flex flex-col
                                lg:flex-row">
                                    <div class="w-full lg:w-1/2 px-2">

                                        <div class="pb-4">
                                            <label for="title"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Title</label>
                                            <x-text-input type="text" id="title" name="title" required
                                                placeholder="Enter title" value="{{ old('title', $datas->title) }}" />

                                            <x-input-error class="mt-2" :messages="$errors->get('title')" />
                                        </div>

                                        <div class="flex flex-row justify-between gap-2">
                                            <div class="w-auto pb-4">
                                                <label for="quest_amount"
                                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Number
                                                    Of Question(s)</label>
                                                <x-text-input type="number" min="0" id="quest_amount"
                                                    name="quest_amount" required placeholder="Enter amount"
                                                    value="{{ old('quest_amount', $datas->quest_amount) }}" />

                                                <x-input-error class="mt-2" :messages="$errors->get('quest_amount')" />
                                            </div>

                                            <div class="w-1/2 pb-4">
                                                <label for="price"
                                                    class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Price</label>
                                                <x-text-input type="text" id="price" name="price" required
                                                    placeholder="Enter price"
                                                    value="{{ old('price', $datas->price) }}" />

                                                <x-input-error class="mt-2" :messages="$errors->get('price')" />
                                            </div>
                                        </div>

                                        <div class="pb-4">
                                            <label for="educationlevel_id"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Educational
                                                Level</label>
                                            <select name="educationlevel_id" id="educationlevel_id"
                                                class="w-full block text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary-100 bg-primary-20 dark:text-gray dark:placeholder-gray-700 dark:border-primary-800 dark:bg-primary-700 dark:text-gray-300">
                                                <option value="">Choose level...</option>
                                                @foreach ($educlvl as $id => $name)
                                                    <option value="{{ $id }}"
                                                        {{ old('educationlevel_id', $datas->educationlevel_id) == $id ? 'selected' : '' }}>
                                                        {{ $name }}</option>
                                                @endforeach
                                            </select>

                                            <x-input-error class="mt-2" :messages="$errors->get('educationlevel_id')" />
                                        </div>
                                    </div>

                                    <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                        <div class="w-auto pb-4 lg:pb-12">
                                            <label for="description"
                                                class="block mb-2 font-medium text-primary-600 dark:text-primary-500">Description</label>
                                            <x-textarea-input id="description" name="description" rows="5"
                                                cols="50" required placeholder="Enter description">
                                                {{ old('description', $datas->description) }}
                                            </x-textarea-input>

                                            <x-input-error class="mt-2" :messages="$errors->get('description')" />
                                        </div>

                                        <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                            <div class="pr-2">
                                                <div class="inline-flex items-center">
                                                    <input type="checkbox" id="isactive" name="isactive"
                                                        class="form-control"
                                                        {{ $datas->isactive == '1' ? 'checked' : '' }}>
                                                    <label for="isactive"
                                                        class="ml-2 block font-medium text-primary-600 dark:text-primary-500">Active</label>
                                                </div>

                                                <x-input-error class="mt-2" :messages="$errors->get('isactive')" />
                                            </div>

                                            <x-primary-button type="submit">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                                </svg>
                                                <span class="pl-1">Save</span>
                                            </x-primary-button>
                                            <x-anchor-secondary href="{{ route('coa.index') }}">
                                                <svg xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor"
                                                    class="size-5">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="M6 18 18 6M6 6l12 12" />
                                                </svg>
                                                <span class="pl-1">Cancel</span>
                                            </x-anchor-secondary>
                                        </div>
                                    </div>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="flex flex-col lg:flex-row gap-4 px-4 py-2">

                    <div class="w-full">
                        <div class="flex flex-col items-center">

                            <div class="w-full" role="alert">
                                @include('coa.partials.feedback-detail')
                            </div>

                            <div
                                class="w-full shadow-lg rounded-md border bg-primary-50 border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                                <div class="p-4 space-y-2">
                                    <div
                                        class="flex flex-row
                                items-center justify-between">
                                        <span
                                            class="block font-medium text-primary-600 dark:text-primary-500">Question(s)</span>
                                    </div>

                                    <div
                                        class="border rounded-md border-primary-100 bg-primary-100 dark:border-primary-800 dark:bg-primary-850">
                                        <div id="table-question-container" class="p-0 lg:p-4">
                                            @include('coa.partials.table-question', $questions)
                                        </div>
                                        <div class="m-4 flex flex-row justify-between">
                                            <x-primary-button id="add_row">Add</x-primary-button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div x-show="openModal" x-cloak x-transition
            class="fixed inset-0 bg-white bg-opacity-75 dark:bg-black dark:bg-opacity-75 flex items-center justify-center px-4 md:px-0">
            <div @click.away="fetchquestion(questionId, questionIndex);"
                class="flex flex-col p-4 h-full w-full shadow-2xl rounded-lg border-2 bg-white border-gray-300 dark:bg-darker dark:border-gray-700">
                <div class="flex justify-between mb-4">
                    <h4 class="font-bold text-gray-900 dark:text-primary-500">
                        <span>Choices</span>
                    </h4>
                    <button @click="openModal = false; fetchquestion(questionId, questionIndex);" class="ml-4">
                        <svg class="w-5 h-5 text-gray-900 dark:text-primary-500" viewBox="0 0 24 24"
                            fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5.293 5.293a1 1 0 0 1 1.414 0L12 10.586l5.293-5.293a1 1 0 1 1 1.414 1.414L13.414 12l5.293 5.293a1 1 0 0 1-1.414 1.414L12 13.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L10.586 12 5.293 6.707a1 1 0 0 1 0-1.414z"
                                fill="currentColor" />
                        </svg>
                    </button>
                </div>

                <div id="loader" class="flex h-full items-center justify-center">
                    <svg class="x-10 h-10 animate-spin" viewBox="0 0 48 48" fill="none"
                        xmlns="http://www.w3.org/2000/svg">
                        <rect width="48" height="48" fill="white" fill-opacity="0.01" />
                        <path
                            d="M4 24C4 35.0457 12.9543 44 24 44V44C35.0457 44 44 35.0457 44 24C44 12.9543 35.0457 4 24 4"
                            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                        <path
                            d="M36 24C36 17.3726 30.6274 12 24 12C17.3726 12 12 17.3726 12 24C12 30.6274 17.3726 36 24 36V36"
                            stroke="currentColor" stroke-width="4" stroke-linecap="round" stroke-linejoin="round" />
                    </svg>
                </div>

                <div id="panels" class="relative overflow-auto flex flex-col-reverse lg:flex-row gap-4">
                    <div id="left-panel"
                        class="relative min-w-96 w-96 p-2 rounded-lg border border-primary-500 bg-primary-50 dark:border-primary-800 dark:bg-primary-800">
                        <div
                            class="h-full overflow-auto flex flex-col rounded-lg border border-primary-500 bg-white dark:border-primary-800 dark:bg-black">
                            <div id="pertanyaan" x-html="modalTitle" class="px-6 py-4 text-sm"></div>
                            <div id="gambar" class="px-6 py-4 relative">
                                <form id="form-gambar" enctype="multipart/form-data">
                                    @csrf

                                    <input type="file" id="file_gambar" class="sr-only" accept="image/*" />
                                    <input type="hidden" id="qid" :value="questionId" />
                                    <img @click="openModalImage = true;" id="gambar_soal_preview"
                                        :src="imagePreview" alt="o.o"
                                        class="relative w-full h-auto rounded-lg border border-primary-500 bg-primary-50 dark:border-primary-800 dark:bg-primary-800" />
                                </form>
                                <div class="absolute top-6 right-2 flex flex-col gap-1">
                                    <button id="triggerBtn"
                                        class="p-2 rounded-full border border-primary-500 bg-blue-400 dark:border-primary-800 dark:bg-blue-600"
                                        title="Assign">
                                        <svg class="size-4 text-white bg-blue-400 dark:bg-blue-600"
                                            viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                            <path d="M15.5 10a1.5 1.5 0 1 0 0-3 1.5 1.5 0 0 0 0 3z"
                                                fill="currentColor" />
                                            <path
                                                d="M3 5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V5zm16 0H5v7.92l3.375-2.7a1 1 0 0 1 1.25 0l4.3 3.44 1.368-1.367a1 1 0 0 1 1.414 0L19 14.586V5zM5 19h14v-1.586l-3-3-1.293 1.293a1 1 0 0 1-1.332.074L9 12.28l-4 3.2V19z"
                                                fill="currentColor" />
                                        </svg>
                                    </button>
                                    <button @click="pasangGambar(questionId, 'hapus');" id="removeBtn"
                                        class="p-2 rounded-full border border-primary-500 bg-red-400 dark:border-primary-800 dark:bg-red-600"
                                        title="Remove">
                                        <svg class="size-4 text-white bg-red-400 dark:bg-red-600"
                                            xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                            stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                            <div id="jawaban">
                            </div>
                        </div>
                    </div>

                    <div id="right-panel" class="relative w-full flex flex-col">
                        <div id="container-button" class="py-2 flex flex-row items-start justify-around">
                            <x-primary-button id="add_choice_row">Add</x-primary-button>
                            <div class="flex flex-row items-center">
                                <x-primary-button id="submit-choice">Save</x-primary-button>
                                <span id="submit-response" class="pl-2"></span>
                            </div>
                        </div>

                        <div id="table-choice-container" class="relative overflow-auto">
                            <div
                                class="border border-primary-500 bg-primary-50 dark:border-primary-800 dark:bg-primary-800">
                                <div class="flex items-center justify-center">
                                    <form id="choice-form" class="w-full p-2">
                                        @csrf

                                        <table id="choice_table" class="border-separate border-spacing-2">
                                            <thead>
                                                <tr>
                                                    <th class="w-20">Sequence</th>
                                                    <th class="w-4/6">Choice</th>
                                                    <th class="w-auto">Correct</th>
                                                    <th class="w-1/6">Score</th>
                                                    <th class="w-auto">&nbsp;</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr id="choice0">
                                                    <td>
                                                        <input type="hidden" name="id[]" value="" />
                                                        <input type="hidden" name="qid[]" :value="questionId" />
                                                        <x-text-input type="number" min="0" name="seq[]"
                                                            value="1" />
                                                    </td>
                                                    <td>
                                                        <x-text-input type="text" name="choice[]"
                                                            value="" />
                                                    </td>
                                                    <td>
                                                        <input type="checkbox" name="correct[]" value="0"
                                                            class="cb" onchange="cbChange(this)" />
                                                    </td>
                                                    <td>
                                                        <x-text-input type="number" min="0" name="value[]"
                                                            value="1" />
                                                    </td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div x-show="openModalImage" x-cloak x-transition
            class="z-50 fixed inset-0 bg-white bg-opacity-75 dark:bg-black dark:bg-opacity-75 flex items-center justify-center px-4 md:px-0">
            <div @click.away="openModalImage = false;"
                class="flex flex-col p-0 h-full w-full shadow-2xl rounded-lg border-2 bg-white border-gray-300 dark:bg-darker dark:border-gray-700">
                <div class="absolute top-4 right-4">
                    <button @click="openModalImage = false;" class="p-2 rounded-full bg-red-600">
                        <svg class="w-5 h-5 text-white dark:text-white-700" viewBox="0 0 24 24" fill="currentColor"
                            xmlns="http://www.w3.org/2000/svg">
                            <path
                                d="M5.293 5.293a1 1 0 0 1 1.414 0L12 10.586l5.293-5.293a1 1 0 1 1 1.414 1.414L13.414 12l5.293 5.293a1 1 0 0 1-1.414 1.414L12 13.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L10.586 12 5.293 6.707a1 1 0 0 1 0-1.414z"
                                fill="currentColor" />
                        </svg>
                    </button>
                </div>
                <div
                    class="flex h-full items-center justify-center overflow-auto rounded-lg border border-primary-500 bg-primary-50 dark:border-primary-800 dark:bg-primary-800">
                    <img id="gambar_soal" :src="imagePreview" alt="o.o"
                        :class="imageOrientation == 'p' ? 'w-auto h-full' : 'w-full h-auto'" />
                </div>
            </div>
        </div>

    </div>

    @push('scripts')
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"
            integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
        <script type="text/javascript" src="{{ url('js/jquery.maskMoney.min.js') }}"></script>
        <script type="text/javascript">
            $(document).ready(function(e) {
                $(function() {
                    $('#quest_amount').maskMoney({
                        prefix: '',
                        allowNegative: false,
                        thousands: '.',
                        decimal: ',',
                        precision: 0,
                        affixesStay: false
                    });
                    $('#price').maskMoney({
                        prefix: 'Rp. ',
                        allowNegative: false,
                        thousands: '.',
                        decimal: ',',
                        precision: 0,
                        affixesStay: false
                    });
                })
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                inputOnFocus = function($input) {
                    var input_value = $input.val();
                    var textarea = $('<textarea/>', {
                        'class': $input.attr('class'),
                        'name': $input.attr('name'),
                        'rows': 3
                    }).val(input_value);
                    $input.replaceWith(textarea);
                    textarea.focus();
                }

                inputOnBlur = function($textarea) {
                    var textarea_value = $textarea.val();
                    var input = $('<input/>', {
                        'type': 'text',
                        'class': $textarea.attr('class'),
                        'name': $textarea.attr('name'),
                        'value': textarea_value
                    });
                    $textarea.replaceWith(input);
                }

                $('td').on('focus', 'input[type="text"]', function() {
                    var $input = $(this);
                    inputOnFocus($input)
                });

                $('td').on('blur', 'textarea', function() {
                    var $textarea = $(this);
                    inputOnBlur($textarea)
                });
            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                let q_row_number = $("#question_table tr").length;

                $("#add_row").click(function(e) {
                    e.preventDefault();

                    q_row_number = $("#question_table tr").length;
                    let new_q_row_number = q_row_number - 1;

                    $('#question_table').append('<tr id="question' + (new_q_row_number) + '">' +
                        '<td><input type="number" min="0" name="seq[]" value="' + q_row_number +
                        '" class="w-full text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary dark:text-gray dark:placeholder-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" /></td>' +
                        '<td><input type="text" name="question[]" value="" placeholder="Enter question" class="w-full text-sm rounded-lg shadow-md text-gray-700 placeholder-gray-300 border-primary dark:text-gray dark:placeholder-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />' +
                        '<input type="hidden" name="ids[]" value="" /></td><td>&nbsp;</td><td>&nbsp;</td></tr>'
                    );
                    q_row_number++;
                    $('input[name="question[]"]').focus();
                });

                deleteQuestion = function(questionId) {
                    let idname = '#a-delete-question-' + questionId;
                    var confirmation = confirm("Are you sure you want to delete this?");
                    if (confirmation) {
                        $.ajax({
                            url: '{{ url('/admin/coa/question-delete') }}' + '/' + questionId,
                            type: 'delete',
                            dataType: 'json',
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(result) {
                                if (result.status !== 'Not Found') {
                                    $(idname).closest("tr").remove();
                                }
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                };

                fetchquestion = function(id, index) {
                    rowid = '#question' + index;

                    $.ajax({
                        url: '{{ url('/admin/coa/fetch-question') }}' + '/' + id + '/' + index,
                        type: 'get',
                        dataType: 'json',
                        success: function(result) {
                            $(rowid).closest("tr").find("td").eq(2).html(result);
                            $(rowid).focus();
                        }
                    });
                }

            });
        </script>
        <script type="text/javascript">
            $(document).ready(function() {
                let row_number = $("#choice_table tr").length;
                row_number = (row_number === 0) ? 2 : row_number;

                $("#add_choice_row").click(function(e) {
                    e.preventDefault();

                    row_number = $("#choice_table tr").length;
                    row_number = (row_number === 0) ? 2 : row_number;
                    let new_row_number = row_number - 1;

                    $('#choice_table').append('<tr id="choice' + (new_row_number) + '"><td>' +
                        '<input type="hidden" name="id[]" value="" />' +
                        '<input type="hidden" name="qid[]" :value="questionId" />' +
                        '<input type="number" min="0" name="seq[]" value="' + row_number +
                        '" class="w-full text-sm rounded-md shadow-sm text-gray-700 placeholder-gray-300 border-primary dark:text-gray dark:placeholder-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" /></td><td>' +
                        '<input type="text" name="choice[]" value="" class="w-full text-sm rounded-md shadow-sm text-gray-700 placeholder-gray-300 border-primary dark:text-gray dark:placeholder-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" /></td>' +
                        '<td align="center"><input type="checkbox" name="correct[]" value="' + (row_number -
                            1) +
                        '" class="cb" onchange="cbChange(this)" /></td><td><input type="number" min="0" name="value[]" value="1" class="w-full text-sm rounded-md shadow-sm text-gray-700 placeholder-gray-300 border-primary dark:text-gray dark:placeholder-gray-700 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300" />' +
                        '</td><td>&nbsp;</td></tr>'
                    );
                    row_number++;

                    $('input[name="choice[]"]').focus();
                });

                $("#delete_choice_row").click(function(e) {
                    e.preventDefault();
                    if (row_number > 1) {
                        $("#choice" + (row_number - 0)).closest('tr').remove();
                        row_number--;
                    }
                });

                $("#submit-choice").on("click", function(e) {
                    e.preventDefault();
                    let data = $("form#choice-form").serializeArray();
                    let v = data[2].value;
                    jQuery.each(data, function(i, data) {});

                    $.ajax({
                        url: '{{ url('/admin/coa/choice-store') }}' + '/' + v,
                        type: 'post',
                        dataType: 'json',
                        data: $('form#choice-form').serialize(),
                        success: function(result) {
                            if (result.status !== 'Not Found') {
                                $('#table-choice-container').html(result.view);
                                $('#jawaban').html(result.view2);
                                $("#submit-response").html(result.status);
                                $('#submit-response').show(0).delay(5000).hide(0);
                            }
                        }
                    });
                });

                loadChoice = function(questionId) {
                    $('#loader').show();
                    $('#panels').hide();

                    $.ajax({
                        url: '{{ url('/admin/coa/fetch-choice') }}' + '/' + questionId,
                        type: 'get',
                        dataType: 'json',
                        success: function(result) {
                            $('#table-choice-container').html(result.view);
                            $("#table-choice-container").focus();
                            $('#jawaban').html(result.view2);
                        },
                        complete: function() {
                            $('#loader').hide();
                            $('#panels').show();
                        }
                    });
                };

                deleteChoice = function(choiceId) {
                    let idname = '#a-delete-choice-' + choiceId;
                    var confirmation = confirm("Are you sure you want to delete this?");
                    if (confirmation) {
                        $.ajax({
                            url: '{{ url('/admin/coa/choice-delete') }}' + '/' + choiceId,
                            type: 'delete',
                            dataType: 'json',
                            data: {
                                '_token': '{{ csrf_token() }}',
                            },
                            success: function(result) {
                                if (result.status === 'Deleted') {
                                    $(idname).closest("tr").remove();
                                    $('#jawaban').html(result.view2);
                                }
                            },
                            error: function(xhr) {
                                console.log(xhr.responseText);
                            }
                        });
                    }
                };

                cbChange = function(obj) {
                    var cbs = document.getElementsByClassName("cb");
                    for (var i = 0; i < cbs.length; i++) {
                        cbs[i].checked = false;
                    }
                    obj.checked = true;
                }

                pasangGambar = function(questionId, status) {
                    $.ajax({
                        url: '{{ url('/admin/coa/pasang-gambar') }}' + '/' + questionId + '/' +
                            status,
                        type: 'post',
                        dataType: 'json',
                        data: {
                            '_token': '{{ csrf_token() }}',
                        },
                        success: function(result) {
                            $('#gambar_soal').attr('src', result);
                            $('#gambar_soal_preview').attr('src', result);
                        },
                        complete: function() {}
                    });
                };

                $("#triggerBtn").click(function() {
                    $("#file_gambar").click();
                });

                $("#file_gambar").on("change", function(e) {
                    var questionId = $('#qid').val();
                    var gambar = $('#file_gambar').prop('files')[0];
                    var form_data = new FormData();
                    form_data.append('file', gambar);
                    form_data.append('_token', '{{ csrf_token() }}');

                    $.ajax({
                        url: '{{ url('/admin/coa/pasang-gambar') }}' + '/' + questionId +
                            '/pasang',
                        type: 'post',
                        dataType: 'json',
                        cache: false,
                        contentType: false,
                        processData: false,
                        data: form_data,
                        success: function(result) {
                            $('#gambar_soal').attr('src', result);
                            $('#gambar_soal_preview').attr('src', result);
                        },
                        complete: function() {
                            $('#form-gambar').trigger('reset');
                        }
                    });
                })

            });
        </script>
    @endpush
</x-app-layout>
