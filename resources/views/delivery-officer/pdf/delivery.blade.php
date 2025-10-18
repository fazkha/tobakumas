<html lang="en">

<head>
    <title>@lang('messages.deliveryreport')</title>
    <style>
        @page {
            margin: 0;
            margin-top: 100px;
            margin-bottom: 20px;
            margin-left: 20px;
            margin-right: 20px;
            font-family: Arial, sans-serif;
            font-size: 14px;
            font-weight: normal;
        }

        header {
            position: fixed;
            top: -100px;
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

        .table_container tfoot td {
            border: 1px solid #dededf;
            background-color: #eceff1;
            color: #000000;
            padding: 6px;
            font-weight: bold;
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
        $grp1 = '';
        $grp1x = true;
        $grp2 = '';
        $grp2x = true;
        $grp3 = '';
        $grp3x = true;
    @endphp

    <header>
        @include('delivery-officer.pdf.delivery-header', [
            'jam1' => $datas[0]->c4,
            'jam2' => $datas[0]->c5,
            'bulan' => $bulan,
        ])
    </header>

    <main>
        <div class="table_container">
            <table>
                <thead>
                    <tr>
                        <th style="width: 5%">No.</th>
                        <th style="width: auto">Nomor DO/Surat Jalan<br />Tanggal</th>
                        <th style="width: auto">Nama Pengirim</th>
                        <th style="width: 8%">Rute/ Tujuan</th>
                        <th style="width: auto">Pesanan</th>
                        <th style="width: 9%">Status Pengiriman</th>
                        <th style="width: auto">Keterangan/Kendala</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($datas as $data)
                        @php
                            if ($grp1 !== $data->c6) {
                                $grp1 = $data->c6;
                                $grp1x = true;
                            } else {
                                $grp1x = false;
                            }
                            if ($grp2 !== $data->c7) {
                                $grp2 = $data->c7;
                                $grp2x = true;
                            } else {
                                $grp2x = false;
                            }
                            if ($grp3 !== $data->c1) {
                                $grp3 = $data->c1;
                                $grp3x = true;
                            } else {
                                $grp3x = false;
                            }
                        @endphp
                        <tr>
                            <td style="vertical-align: top; text-align: center;">{{ $i }}</td>
                            <td style="vertical-align: top;">
                                {!! $grp2x ? $data->c7 . '<br />' . date_format(date_create($data->c6), 'd/m/Y') : ' ' !!}
                            </td>
                            <td style="vertical-align: top;">
                                {!! $grp3x ? $data->c1 . '<br />' . $data->c11 : ' ' !!}
                            </td>
                            <td style="vertical-align: top;">{{ $data->c2 }}</td>
                            <td style="vertical-align: top;">{{ $data->c3 }}</td>
                            <td style="vertical-align: top;">{{ $data->c9 == 1 ? 'Selesai' : 'Proses' }}</td>
                            <td style="vertical-align: top;">{{ $data->c8 }}</td>
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

    @include('delivery-officer.pdf.delivery-footer')

</body>

</html>
