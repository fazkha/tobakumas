<html lang="en">

<head>
    <title>@lang('messages.stockopname')</title>
    <style>
        body {
            font-family: 'Dosis', sans-serif;
            margin: 0;
        }

        @page {
            margin-top: 110px;
            margin-bottom: 20px;
            margin-left: 20px;
            margin-right: 20px;
        }

        header {
            position: fixed;
            top: -90px;
            left: 0px;
            right: 0px;
        }

        .table_container {
            overflow: auto;
            width: 100%;
            font-size: 12px;
        }

        .table_container table {
            border: 1px solid #dededf;
            height: auto;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 1px;
            text-align: container;
            font-size: 12px;
        }

        .table_container caption {
            caption-side: top;
            text-align: container;
            font-size: 12px;
        }

        .table_container th {
            border: 1px solid #dededf;
            background-color: #eceff1;
            color: #000000;
            padding: 6px;
            font-size: 12px;
        }

        .table_container td {
            border: 1px solid #dededf;
            background-color: #ffffff;
            color: #000000;
            padding: 6px;
            font-size: 12px;
        }
    </style>
</head>

<body>
    @php
        $i = 1;
        $pdf_line_per_page = config('custom.pdf_line_per_page');
    @endphp

    <header>
        @include('stock-opname.pdf.perhitungan-header', [
            'datas' => $datas,
            'hari' => $hari,
            'bulan' => $bulan,
        ])
    </header>

    <main>
        <div class="table_container">
            <table>
                <thead>
                    <tr>
                        <th rowspan="2" style="width: 5%">No.</th>
                        <th rowspan="2" style="width: 15%">Nama Barang</th>
                        <th rowspan="2" style="width: auto">Merk</th>
                        <th rowspan="2" style="width: auto">Satuan</th>
                        <th colspan="3" style="width: auto">Persediaan</th>
                        <th rowspan="2" style="width: 25%">Keterangan</th>
                    </tr>
                    <tr>
                        <th style="width: 8%">Sistem</th>
                        <th style="width: 8%">Fisik</th>
                        <th style="width: 8%">Selisih</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($details as $detail)
                        <tr>
                            <td style="vertical-align: top; text-align: center;">{{ $i }}</td>
                            <td style="vertical-align: top;">{{ $detail->barang->nama }}</td>
                            <td style="vertical-align: top;">{{ $detail->barang->merk }}</td>
                            <td style="vertical-align: top;">{{ $detail->satuan->nama_lengkap }}</td>
                            <td style="vertical-align: top; text-align: right;">{{ $detail->before_stock }}</td>
                            <td style="vertical-align: top; text-align: right;">{{ $detail->stock }}</td>
                            <td style="vertical-align: top; text-align: right;">{{ $detail->selisih_stock }}</td>
                            <td style="vertical-align: top;">{{ $detail->keterangan }}</td>
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

        <div style="padding: 10px;">
            <table style="width: 100%; font-size: 14px;">
                <tr>
                    <td style="text-align: left;" colspan="3">
                        <table style="width: auto; font-size: 14px;">
                            <tr>
                                <td style="padding: 10px; vertical-align: top">Catatan:</td>
                                <td style="padding: 8px; vertical-align: top; line-height: 20px;">
                                    {!! nl2br($datas->keterangan) !!}</td>
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
                    <td style="width: 25%">
                        <table style="width: 100%; font-size: 14px;">
                            <tr>
                                <td style="padding: 10px; text-align: center;">Petugas Gudang</td>
                            </tr>
                            <tr>
                                <td style="height: 50px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; text-align: center; border-top: 1px solid #909090;">
                                    {{ $datas->petugas_1_id ? $datas->petugas_1->nama_lengkap : '-' }}</td>
                            </tr>
                        </table>
                    </td>
                    <td style="width: 50%">&nbsp;</td>
                    <td style="width: 25%">
                        <table style="width: 100%; font-size: 14px;">
                            <tr>
                                <td style="padding: 10px; text-align: center;">Mengetahui</td>
                            </tr>
                            <tr>
                                <td style="height: 50px;">&nbsp;</td>
                            </tr>
                            <tr>
                                <td style="padding: 10px; text-align: center; border-top: 1px solid #909090;">
                                    {{ $datas->tanggungjawab_id ? $datas->tanggungjawab->nama_lengkap : '-' }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
        </div>
    </main>

    @include('stock-opname.pdf.perhitungan-footer')

</body>

</html>
