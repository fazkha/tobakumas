<html lang="en">

<head>
    <title>@lang('messages.mutationreport')</title>
    <style>
        @page {
            margin: 0;
            margin-top: 110px;
            margin-bottom: 20px;
            margin-left: 20px;
            margin-right: 20px;
            font-family: 'Dosis', sans-serif;
            font-size: 14px;
            font-weight: normal;
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
                        <th style="width: 18%">Transaksi</th>
                        <th style="width: 18%">No. Bukti</th>
                        <th style="width: auto">Tanggal</th>
                        <th style="width: auto">Jumlah</th>
                        <th style="width: auto">Satuan</th>
                        <th style="width: 25%">Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        @if ($kel_barang !== $data->c4)
                            @php
                                $kel_barang = $data->c4;
                            @endphp

                            <tr>
                                <td colspan="7" style="font-weight: bold;">
                                    {{ $data->c4 }}
                                </td>
                            </tr>
                        @endif
                        <tr>
                            <td style="vertical-align: top; text-align: center;">{{ $i }}</td>
                            <td style="vertical-align: top;">{{ $data->c1 }}</td>
                            <td style="vertical-align: top;">{{ $data->c2 }}</td>
                            <td style="vertical-align: top;">{{ date_format(date_create($data->c3), 'd/m/Y') }}</td>
                            <td style="vertical-align: top; text-align: right;">
                                {{ number_format($data->c6, '1', ',', '.') }}</td>
                            <td style="vertical-align: top;">{{ $data->c5 }}</td>
                            <td style="vertical-align: top;">{{ $data->c7 }}</td>
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
                            <td style="height: 50px;">&nbsp;</td>
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
    </div> --}}
    </main>

    @include('barang.pdf.mutasi-stock-footer')

</body>

</html>
