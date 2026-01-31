@php
    use Illuminate\Support\Facades\Crypt;
@endphp
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
            <span class="px-2">&raquo;</span>
            <span class="px-2 font-semibold">@lang('messages.edit')</span>
        </h1>
    </div>

    <form id="pengumuman-form" action="{{ route('announcement.update', Crypt::Encrypt($datas->id)) }}" method="POST"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="py-2 flex flex-col">

            <div class="w-full px-4 py-2">
                <div class="flex flex-col items-center">

                    <div class="w-full" role="alert">
                        @include('pengumuman.partials.feedback')
                    </div>

                    <div
                        class="w-full shadow-lg bg-primary-50 rounded-md border border-primary-100 dark:bg-primary-900 dark:border-primary-800">
                        <div class="p-4 space-y-2">

                            <div class="flex flex-col lg:flex-row">
                                <div class="w-full lg:w-1/2 px-2">

                                    <div class="w-auto pb-4">
                                        <label for="tanggal"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.date')</label>
                                        <x-text-input type="date" name="tanggal" id="tanggal"
                                            data-date-format="dd-mm-yyyy" tabindex="1" placeholder="Enter date"
                                            required value="{{ old('tanggal', $datas->tanggal) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('tanggal')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="judul"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.title_head')</label>
                                        <x-text-input type="text" name="judul" id="judul" tabindex="2"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.title_head') }}"
                                            required value="{{ old('judul', $datas->judul) }}" />

                                        <x-input-error class="mt-2" :messages="$errors->get('judul')" />
                                    </div>

                                    <div class="w-auto pb-4">
                                        <label for="keterangan"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.description')</label>
                                        <x-textarea-input name="keterangan" id="keterangan" tabindex="3"
                                            rows="7" maxlength="200"
                                            placeholder="{{ __('messages.enter') }} {{ __('messages.description') }}">{{ old('keterangan', $datas->keterangan) }}</x-textarea-input>

                                        <x-input-error class="mt-2" :messages="$errors->get('keterangan')" />
                                    </div>
                                </div>

                                <div class="w-full lg:w-1/2 px-2 flex flex-col justify-start">
                                    <div class="w-auto pb-4 lg:pb-12">
                                        <label for="gambar"
                                            class="block mb-2 font-medium text-primary-600 dark:text-primary-500">@lang('messages.picture')</label>
                                        <x-text-input type="file" name="gambar" id="gambar" tabindex="4"
                                            accept=".jpg,.jpeg" placeholder="@lang('messages.choose')"
                                            class="!rounded-none border" />

                                        <x-input-error class="mt-2" :messages="$errors->get('gambar')" />

                                        <div class="mt-2 flex justify-center">
                                            <img id="image-preview" class="w-full lg:w-3/5 h-auto border rounded-lg"
                                                @if ($datas->gambar) src="{{ asset($datas->lokasi . '/' . $datas->gambar) }}" @else src="{{ url('/') }}/images/0cd6be830e32f80192d496e50cfa9dbc.jpg" @endif
                                                alt="o.o" />
                                        </div>
                                    </div>

                                    <div class="flex flex-row flex-wrap items-center justify-end gap-2 md:gap-4">
                                        <div class="w-auto">
                                            <label
                                                class="cursor-pointer flex flex-col items-center md:flex-row md:gap-2">
                                                <input type="checkbox" id="isactive" name="isactive"
                                                    class="dark:border-white-400/20 dark:scale-100 transition-all duration-500 ease-in-out dark:hover:scale-110 dark:checked:scale-100 w-7 h-7 rounded-lg shadow-md"
                                                    {{ $datas->isactive == '1' ? 'checked' : '' }}>
                                                <span
                                                    class="pr-4 group-hover:text-blue-500 transition-colors duration-300 text-right w-1/2 md:w-full">
                                                    @lang('messages.active')
                                                </span>
                                            </label>
                                        </div>

                                        <x-primary-button type="submit" class="block" tabindex="5">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375m16.5 0v3.75m-16.5-3.75v3.75m16.5 0v3.75C20.25 16.153 16.556 18 12 18s-8.25-1.847-8.25-4.125v-3.75m16.5 0c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.save')</span>
                                        </x-primary-button>
                                        <x-anchor-secondary href="{{ route('announcement.index') }}" tabindex="6">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                                                stroke-width="1.5" stroke="currentColor" class="size-5">
                                                <path stroke-linecap="round" stroke-linejoin="round"
                                                    d="M6 18 18 6M6 6l12 12" />
                                            </svg>
                                            <span class="pl-1">@lang('messages.close')</span>
                                        </x-anchor-secondary>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </form>

    @push('scripts')
        <script type="text/javascript">
            $(document).ready(function(e) {
                $(function() {
                    $('#gambar').change(function() {
                        let reader = new FileReader();
                        reader.onload = (e) => {
                            $('#image-preview').attr('src', e.target.result);
                        }
                        reader.readAsDataURL(this.files[0]);
                    });
                })
            });
        </script>
    @endpush
</x-app-layout>
