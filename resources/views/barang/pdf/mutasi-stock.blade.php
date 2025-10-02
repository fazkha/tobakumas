<html lang="en">

<head>
    <title>@lang('messages.mutationreport')</title>
    <style>
        @page {
            margin: 0;
            margin-top: 140px;
            margin-bottom: 20px;
            margin-left: 20px;
            margin-right: 20px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: normal;
        }

        header {
            position: fixed;
            top: -140px;
            left: 0px;
            right: 0px;
        }

        .table_container {
            overflow: auto;
            width: 100%;
        }

        .table_container table {
            border: 1px solid #dededf;
            height: auto;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 1px;
        }

        .table_container caption {
            caption-side: top;
        }

        .table_container th {
            border: 1px solid #dededf;
            background-color: #eceff1;
            color: #000000;
            padding: 6px;
        }

        .table_container td {
            border: 1px solid #dededf;
            background-color: #ffffff;
            color: #000000;
            padding: 6px;
        }
    </style>
</head>

<body>
    @php
        $i = 1;
        $pdf_line_per_page = config('custom.pdf_line_per_page');
        $kel_barang = '';
    @endphp

    <header>
        @include('barang.pdf.mutasi-stock-header', [
            'gudang' => $gudang,
            'hari' => $hari,
            'bulan' => $bulan,
        ])
    </header>

    <main>
        <div class="table_container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No.</th>
                        <th style="width: auto">Nama Barang</th>
                        <th style="width: auto">Satuan</th>
                        <th style="width: 8%">Saldo Awal</th>
                        <th style="width: 8%">Masuk</th>
                        <th style="width: 8%">Keluar</th>
                        <th style="width: 8%">Penyesu aian</th>
                        <th style="width: 8%">Saldo Akhir</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        @php
                            $stawal = $data->c12 - ($data->c61 + $data->c62 + $data->c63);
                        @endphp
                        <tr>
                            <td style="vertical-align: top; text-align: center;">{{ $i }}</td>
                            <td style="vertical-align: top;">{{ $data->c4 }}</td>
                            <td style="vertical-align: top;">{{ $data->c5 }}</td>
                            <td style="vertical-align: top; text-align: right;">
                                {{ number_format($stawal, '1', ',', '.') }}
                            </td>
                            <td style="vertical-align: top; text-align: right;">
                                {{ number_format($data->c61, '1', ',', '.') }}
                            </td>
                            <td style="vertical-align: top; text-align: right;">
                                {{ number_format($data->c62, '1', ',', '.') }}
                            </td>
                            <td style="vertical-align: top; text-align: right;">
                                {{ number_format($data->c63, '1', ',', '.') }}
                            </td>
                            <td style="vertical-align: top; text-align: right;">
                                {{ number_format($data->c12, '1', ',', '.') }}
                            </td>
                        </tr>
                        @php
                            $i++;
                        @endphp
                    @endforeach
                </tbody>
            </table>
        </div>

        @if (($i - 1) % $pdf_line_per_page == 0)
            @pageBreak
        @endif

        {{-- <div style="padding: 10px;">
        <table style="width: 100%;">
            <tr>
                <td style="text-align: left;" colspan="3">
                    <table style="width: auto;">
                        <tr>
                            <td style="padding: 10px; vertical-align: top">Catatan:</td>
                            <td style="padding: 8px; vertical-align: top; line-height: 20px;">
                                {!! nl2br($datas->keterangan_adjustment) !!}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td style="padding: 10px; text-align: center;">
                    @php
                        $find = ['kabupaten', 'Kabupaten', 'kota', 'Kota'];
                        $replace = ['', '', '', ''];
                    @endphp
                    {{ trim(str_replace($find, $replace, $datas->gudang->kabupaten->nama)) . ', ' . date('j', strtotime(date('Y-m-d H:i:s'))) . ' ' . $bulanini . ' ' . date('Y', strtotime(date('Y-m-d H:i:s'))) }}
                </td>
            </tr>
            <tr>
                <td style="width: 30%">
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 10px; text-align: center;">Petugas Gudang</td>
                        </tr>
                        <tr>
                            <td style="height: 80px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; text-align: center; border-top: 1px solid #909090;">
                                {{ $datas->petugas_1_id ? $datas->petugas_1->nama_lengkap : '-' }}</td>
                        </tr>
                    </table>
                </td>
                <td style="width: 40%">&nbsp;</td>
                <td style="width: 30%">
                    <table style="width: 100%;">
                        <tr>
                            <td style="padding: 10px; text-align: center;">Mengetahui</td>
                        </tr>
                        <tr>
                            <td style="height: 80px;">&nbsp;</td>
                        </tr>
                        <tr>
                            <td style="padding: 10px; text-align: center; border-top: 1px solid #909090;">
                                {{ $datas->tanggungjawab_id ? $datas->tanggungjawab->nama_lengkap : '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div> --}}
    </main>

    @include('barang.pdf.mutasi-stock-footer')

</body>

</html>
