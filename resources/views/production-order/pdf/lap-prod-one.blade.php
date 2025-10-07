<html lang="en">

<head>
    <title>@lang('messages.productionreport')</title>
    <style>
        @page {
            margin: 0;
            margin-top: 170px;
            margin-bottom: 20px;
            margin-left: 20px;
            margin-right: 20px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: normal;
        }

        header {
            position: fixed;
            top: -170px;
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
        $jmlprod = $datas->sum('c8');
        $jmlsas = $datas[0]->c11;
        $jmlrus = $datas[0]->c12;
        $jmlsis = $jmlsas - $jmlprod - $jmlrus;
        $petugas = $datas[0]->c2;
        $tanggungjawab = $datas[0]->c3;
        $kota = $datas[0]->c14;
        $hke = $datas[0]->c4;
        $satuan = $datas[0]->c10;
    @endphp

    <header>
        @include('production-order.pdf.lap-prod-one-header', [
            'petugas' => $petugas,
            'tanggungjawab' => $tanggungjawab,
            'hke' => $hke,
        ])
    </header>

    <main>
        <div class="table_container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No.</th>
                        <th style="width: auto">Cabang</th>
                        <th style="width: auto">Mitra</th>
                        <th style="width: auto">Jenis Adonan</th>
                        <th style="width: 8%">Kuantitas</th>
                        <th style="width: 10%">Satuan</th>
                        <th style="width: 12%">Harga (Rp.)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        <tr>
                            <td style="vertical-align: top; text-align: center;">{{ $i }}</td>
                            <td style="vertical-align: top;">{{ $data->c5 }}</td>
                            <td style="vertical-align: top;">{{ $data->c6 }}</td>
                            <td style="vertical-align: top;">{{ $data->c7 }}</td>
                            <td style="vertical-align: top; text-align: right;">
                                {{ number_format($data->c8, '2', ',', '.') }}
                            </td>
                            <td style="vertical-align: top;">{{ $data->c10 }}</td>
                            <td style="vertical-align: top; text-align: right;">
                                {{ number_format($data->c9, '0', ',', '.') }}
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

        <table style="width: auto; margin-top: 20px;">
            <tr>
                <td style="text-align: left;">Jumlah Pesanan</td>
                <td style="width: 20px; text-align: center;">:</td>
                <td style="text-align: right;">{{ number_format($jmlprod, '2', ',', '.') }}</td>
                <td style="text-align: left;">{{ $satuan }}</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: left;">Jumlah Produksi</td>
                <td style="width: 20px; text-align: center;">:</td>
                <td style="width: 50%; text-align: right;">{{ number_format($jmlsas, '2', ',', '.') }}</td>
                <td style="text-align: left;">{{ $satuan }}</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: left;">Rusak</td>
                <td style="width: 20px; text-align: center;">:</td>
                <td style="width: 50%; text-align: right;">{{ number_format($jmlrus, '2', ',', '.') }}</td>
                <td style="text-align: left;">{{ $satuan }}</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: left;">Sisa Persediaan</td>
                <td style="width: 20px; text-align: center;">:</td>
                <td style="width: 50%; text-align: right;">{{ number_format($jmlsis, '2', ',', '.') }}</td>
                <td style="text-align: left;">{{ $satuan }}</td>
            </tr>
        </table>

        <div style="padding: 10px;">
            <table style="width: 100%;">
                <tr>
                    <td style="text-align: left;" colspan="3">
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
                        {{ trim(str_replace($find, $replace, $kota)) . ', ' . date('j', strtotime(date('Y-m-d H:i:s'))) . ' ' . $bulanini . ' ' . date('Y', strtotime(date('Y-m-d H:i:s'))) }}
                    </td>
                </tr>
                <tr>
                    <td style="width: 30%">
                        <table style="width: 100%;">
                            <tr>
                                <td style="padding: 10px; text-align: center;">Petugas Produksi</td>
                            </tr>
                            <tr>
                                <td style="height: 80px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; text-align: center; border-top: 1px solid #909090;">
                                    {{ $petugas }}</td>
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
                                    {{ $tanggungjawab }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </main>

    @include('production-order.pdf.lap-prod-footer')
</body>

</html>
