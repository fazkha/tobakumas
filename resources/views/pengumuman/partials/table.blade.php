@php
    use Illuminate\Support\Facades\Crypt;
    use App\Models\MitraPengumumanUntuk;
@endphp

<div x-data="{
    openModal: false,
    imagePreview: '{{ asset('images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}',
    modalTitle: 'Title'
}" class="w-full overflow-x-auto">
    <div class="w-full overflow-x-auto">
        <div
            class="inline-block min-w-full shadow-md overflow-hidden rounded-md border border-solid border-primary-100 dark:border-primary-800">
            <table class="min-w-full leading-normal">
                <thead>
                    <tr>
                        <th
                            class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                            #
                        </th>
                        <th
                            class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                            &nbsp;
                        </th>
                        <th
                            class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                            @lang('messages.date')
                        </th>
                        <th
                            class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                            @lang('messages.title_head')
                        </th>
                        <th
                            class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                            @lang('messages.description')
                        </th>
                        <th
                            class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                            @lang('messages.receiver')
                        </th>
                        <th
                            class="px-3 py-3 text-center text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                            @lang('messages.active')
                        </th>
                        <th
                            class="px-3 py-3 text-left text-xs font-semibold uppercase tracking-wider border-b border-primary-100 text-gray-600 bg-primary-50 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                            &nbsp;
                        </th>
                    </tr>
                </thead>
                <tbody>
                    @if ($datas->count() == 0)
                        <tr>
                            <td colspan="8" class="text-sm bg-primary-20 dark:bg-primary-900">
                                <div class="flex items-center justify-center p-5">@lang('messages.datanotavailable')</div>
                            </td>
                        </tr>
                    @endif

                    @foreach ($datas as $data)
                        <tr>
                            <td
                                class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                <p class="text-center text-gray-900 whitespace-no-wrap dark:text-white">
                                    {{ ++$i }}
                                </p>
                            </td>
                            <td
                                class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                <div class="flex items-center justify-center">
                                    <button
                                        @click="openModal = true; modalTitle = '{{ $data->nama }}'; $refs.imgRef.src = '{{ $data->gambar ? asset($data->lokasi . '/' . $data->gambar) : asset('images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}'">
                                        <img class="w-20 h-auto rounded-md"
                                            src="{{ $data->gambar ? asset($data->lokasi . '/' . $data->gambar) : asset('images/0cd6be830e32f80192d496e50cfa9dbc.jpg') }}"
                                            alt="o.o" />
                                    </button>
                                </div>
                            </td>
                            <td
                                class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                <span class="text-gray-900 dark:text-white">{{ $data->tanggal }}</span>
                            </td>
                            <td
                                class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                <span class="text-gray-900 dark:text-white">{{ $data->judul }}</span>
                            </td>
                            <td
                                class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                <span class="text-gray-900 dark:text-white">{{ $data->keterangan }}</span>
                            </td>
                            <td
                                class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                @php
                                    $untuks = MitraPengumumanUntuk::where('mitra_pengumuman_id', $data->id)
                                        ->orderBy('jabatan_id')
                                        ->get();
                                @endphp
                                <div class="flex flex-col flex-wrap lg:flex-row gap-2 text-gray-900 dark:text-white">
                                    @foreach ($untuks as $untuk)
                                        <div
                                            class="flex px-2 py-1 border rounded items-center bg-primary-100 border-primary-400 dark:bg-primary-700 dark:border-primary-600">
                                            <span>&bull;</span><span class="pl-2">{{ $untuk->jabatan->nama }}</span>
                                        </div>
                                    @endforeach
                                </div>
                            </td>
                            <td
                                class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800">
                                <span class="flex items-center justify-center">
                                    @if ($data->isactive == '1')
                                        <span>✔️</span>
                                    @endif
                                    @if ($data->isactive == '0')
                                        <span>❌</span>
                                    @endif
                                </span>
                            </td>
                            <td class="px-3 py-1 text-sm border-b border-primary-100 bg-primary-20 dark:bg-primary-900 dark:border-primary-800"
                                style="vertical-align: middle;">
                                <div class="flex items-center justify-center">
                                    @can('pengumuman-show')
                                        <a href="{{ route('announcement.show', Crypt::Encrypt($data->id)) }}"
                                            title="{{ __('messages.view') }}">
                                            <span
                                                class="relative inline-block px-2 py-2 font-semibold text-blue-800 dark:text-blue-50 leading-tight">
                                                <span aria-hidden
                                                    class="absolute inset-0 bg-blue-200 hover:bg-blue-400 dark:bg-blue-500 hover:dark:bg-blue-700 opacity-50 rounded-full"></span>
                                                <svg class="size-5" viewBox="0 0 16 16" xmlns="http://www.w3.org/2000/svg"
                                                    fill="currentColor">
                                                    <path fill-rule="evenodd" clip-rule="evenodd"
                                                        d="M1 10c0-3.9 3.1-7 7-7s7 3.1 7 7h-1c0-3.3-2.7-6-6-6s-6 2.7-6 6H1zm4 0c0-1.7 1.3-3 3-3s3 1.3 3 3-1.3 3-3 3-3-1.3-3-3zm1 0c0 1.1.9 2 2 2s2-.9 2-2-.9-2-2-2-2 .9-2 2z" />
                                                </svg>
                                            </span>
                                        </a>
                                    @endcan

                                    @can('pengumuman-edit')
                                        <a href="{{ route('announcement.edit', Crypt::Encrypt($data->id)) }}"
                                            title="{{ __('messages.edit') }}" class="ml-2">
                                            <span
                                                class="relative inline-block px-2 py-2 font-semibold text-green-800 dark:text-green-50 leading-tight">
                                                <span aria-hidden
                                                    class="absolute inset-0 bg-green-200 hover:bg-green-400 dark:bg-green-500 hover:dark:bg-green-700 opacity-50 rounded-full"></span>
                                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.863 4.487Zm0 0L19.5 7.125" />
                                                </svg>
                                            </span>
                                        </a>
                                    @endcan

                                    @can('pengumuman-delete')
                                        <a href="{{ route('announcement.delete', Crypt::Encrypt($data->id)) }}"
                                            title="{{ __('messages.delete') }}" class="ml-2">
                                            <span
                                                class="relative inline-block px-2 py-2 font-semibold text-red-800 dark:text-red-50 leading-tight">
                                                <span aria-hidden
                                                    class="absolute inset-0 bg-red-200 hover:bg-red-400 dark:bg-red-500 hover:dark:bg-red-700 opacity-50 rounded-full"></span>
                                                <svg class="size-5" xmlns="http://www.w3.org/2000/svg" fill="none"
                                                    viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round"
                                                        d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673a2.25 2.25 0 0 1-2.244 2.077H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a48.667 48.667 0 0 0-7.5 0" />
                                                </svg>
                                            </span>
                                        </a>
                                    @endcan
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div
                class="px-5 py-5 bg-primary-50 items-center xs:justify-between border-t border-primary-100 dark:text-white dark:bg-primary-800 dark:border-primary-800">
                <div class="mt-2 xs:mt-0">
                    {{ $datas->links() }}
                </div>
            </div>
        </div>

    </div>

    <div x-show.transition.duration.500ms="openModal"
        class="fixed inset-0 flex items-center justify-center px-4 md:px-0 bg-white bg-opacity-75 dark:bg-black dark:bg-opacity-75">
        <div @click.away="openModal = false"
            class="flex flex-col p-6 h-auto w-auto shadow-2xl rounded-lg border-2 bg-white border-gray-400 dark:bg-gray-700 dark:border-gray-900">
            <div class="flex justify-between mb-4">
                <div class="font-bold text-lg text-gray-900 dark:text-gray-50"><span x-html="modalTitle"></span></div>
                <button @click="openModal = false">
                    <svg class="w-5 h-5 text-gray-900 dark:text-gray-50" viewBox="0 0 24 24" fill="currentColor"
                        xmlns="http://www.w3.org/2000/svg">
                        <path
                            d="M5.293 5.293a1 1 0 0 1 1.414 0L12 10.586l5.293-5.293a1 1 0 1 1 1.414 1.414L13.414 12l5.293 5.293a1 1 0 0 1-1.414 1.414L12 13.414l-5.293 5.293a1 1 0 0 1-1.414-1.414L10.586 12 5.293 6.707a1 1 0 0 1 0-1.414z"
                            fill="currentColor" />
                    </svg>
                </button>
            </div>
            <div class="flex items-center justify-center overflow-hidden rounded-lg">
                <img x-ref="imgRef" src="" class="w-auto h-full max-h-96" />
            </div>
        </div>
    </div>
</div>
