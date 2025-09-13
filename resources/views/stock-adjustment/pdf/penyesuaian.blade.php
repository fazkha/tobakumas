<html lang="en">

<head>
    <title>Penyesuaian Persediaan</title>
    <style>
        body {
            font-family: 'Dosis', sans-serif;
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
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    @php
        $i = 1;
        $pg = 1;
    @endphp
    <div class="table_container">
        <table>
            <thead>
                <tr>
                    <th rowspan="2" class="w-10">No.</th>
                    <th rowspan="2" class="w-1/6">Nama Barang</th>
                    <th rowspan="2" class="w-auto">Satuan</th>
                    <th colspan="3" class="w-auto">Persediaan</th>
                    <th rowspan="2" class="w-auto">Harga Disesuaikan (Rp.)</th>
                    <th rowspan="2" class="w-1/4">Keterangan</th>
                </tr>
                <tr>
                    <th class="w-1/12">Sistem</th>
                    <th class="w-1/12">Disesuaikan</th>
                    <th class="w-1/12">Penyesuaian</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $detail)
                    @php
                        $hasil = $detail->before_stock + $detail->adjust_stock;
                        $harga = ($detail->before_stock + $detail->adjust_stock) * $detail->adjust_harga;
                    @endphp
                    <tr>
                        <td class="align-top text-center">{{ $i }}</td>
                        <td class="align-top">{{ $detail->barang->nama }}</td>
                        <td class="align-top">{{ $detail->satuan->nama_lengkap }}</td>
                        <td class="align-top text-right">{{ number_format($detail->before_stock, '1', ',', '.') }}</td>
                        <td class="align-top text-right">{{ number_format($detail->adjust_stock, '1', ',', '.') }}</td>
                        <td class="align-top text-right">{{ number_format($hasil, '1', ',', '.') }}</td>
                        <td class="align-top text-right">{{ number_format($harga, '0', ',', '.') }}</td>
                        <td class="align-top">{{ $detail->keterangan_adjustment }}</td>
                    </tr>
                    @php
                        $i++;
                    @endphp
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="p-4">
        <table style="width: 100%; font-size: 14px;">
            <tr>
                <td class="text-left" colspan="3">
                    <table style="width: auto; font-size: 14px;">
                        <tr>
                            <td class="p-2 align-top">Catatan:</td>
                            <td class="p-2 align-top">{!! nl2br($datas->keterangan_adjustment) !!}</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td>&nbsp;</td>
                <td>&nbsp;</td>
                <td class="p-2 text-center">
                    @php
                        $find = ['kabupaten', 'Kabupaten', 'kota', 'Kota'];
                        $replace = ['', '', '', ''];
                    @endphp
                    {{ trim(str_replace($find, $replace, $datas->gudang->kabupaten->nama)) . ', ' . date('j', strtotime(date('Y-m-d H:i:s'))) . ' ' . $bulanini . ' ' . date('Y', strtotime(date('Y-m-d H:i:s'))) }}
                </td>
            </tr>
            <tr>
                <td class="w-1/3">
                    <table style="width: 100%; font-size: 14px;">
                        <tr>
                            <td class="p-2 text-center">Petugas Gudang</td>
                        </tr>
                        <tr>
                            <td class="h-20">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="p-2 text-center border-t border-neutral-950">
                                {{ $datas->petugas_1_id ? $datas->petugas_1->nama_lengkap : '-' }}</td>
                        </tr>
                    </table>
                </td>
                <td class="w-1/3">&nbsp;</td>
                <td class="w-1/3">
                    <table style="width: 100%; font-size: 14px;">
                        <tr>
                            <td class="p-2 text-center">Mengetahui</td>
                        </tr>
                        <tr>
                            <td class="h-20">&nbsp;</td>
                        </tr>
                        <tr>
                            <td class="p-2 text-center border-t border-neutral-950">
                                {{ $datas->tanggungjawab_id ? $datas->tanggungjawab->nama_lengkap : '-' }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>
</body>

</html>
