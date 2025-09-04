<html lang="en">

<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Dosis', sans-serif;
            font-size: 10px;
        }

        .table_left {
            overflow: auto;
            width: 90%;
            font-size: 10px;
        }

        .table_left table {
            border: 1px solid #dededf;
            height: auto;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 1px;
            text-align: left;
            font-size: 10px;
        }

        .table_left caption {
            caption-side: top;
            text-align: left;
            font-size: 10px;
        }

        .table_left th {
            border: 1px solid #dededf;
            background-color: #eceff1;
            color: #000000;
            padding: 3px;
            font-size: 10px;
        }

        .table_left td {
            border: 1px solid #dededf;
            background-color: #ffffff;
            color: #000000;
            padding: 3px;
            font-size: 10px;
        }

        .table_right {
            overflow: auto;
            width: 110%;
            font-size: 10px;
        }

        .table_right table {
            border: 1px solid #dededf;
            height: auto;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 1px;
            text-align: left;
            font-size: 10px;
        }

        .table_right caption {
            caption-side: top;
            text-align: left;
            font-size: 10px;
        }

        .table_right th {
            border: 1px solid #dededf;
            background-color: #eceff1;
            color: #000000;
            padding: 3px;
            font-size: 10px;
        }

        .table_right td {
            border: 1px solid #dededf;
            background-color: #ffffff;
            color: #000000;
            padding: 3px;
            font-size: 10px;
        }
    </style>
    <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
    <div class="grid grid-cols-2 divide-x divide-y">
        <div class="flex flex-row">
            <div class="table_left" role="region" tabindex="0">
                <div class="flex flex-col p-2">
                    <div class="flex flex-row justify-between">
                        <div>{{ $datas->no_order }}</div>
                        <div>HKE: {{ $datas->hke }}</div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th class="w-auto">Nama barang</th>
                                <th class="w-1/4">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($details as $detail)
                                <tr>
                                    <td>{{ $detail->barang->nama }}</td>
                                    <td>
                                        <div class="flex flex-row justify-between">
                                            <span>{{ $detail->satuan->singkatan }}</span>
                                            <span class="text-right">{{ $detail->kuantiti }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                            @php
                                $cnt = count($details);
                                $max = config('custom.total_baris_suratjalan');
                            @endphp
                            @for ($i = $cnt; $i < $max; $i++)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            @endfor
                        </tbody>
                    </table>
                </div>
            </div>

            <div class="table_right" role="region" tabindex="0">
                <div class="flex flex-col p-2">
                    <div class="flex flex-row justify-between">
                        <div>{{ $datas->customer->nama }}</div>
                        <div>{{ date_format(date_create($datas->tanggal), 'd/m/Y') }}</div>
                    </div>
                    <table>
                        <thead>
                            <tr>
                                <th class="w-auto">Nama mitra</th>
                                <th class="w-1/3">Nama barang</th>
                                <th class="w-1/4">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $total_adonan = 0;
                            @endphp
                            @foreach ($adonans as $adonan)
                                <tr>
                                    <td>{{ $adonan->pegawai->nama_lengkap }}</td>
                                    <td>{{ $adonan->barang->nama }}</td>
                                    <td>
                                        <div class="flex flex-row justify-between">
                                            <span>{{ $adonan->satuan->singkatan }}</span>
                                            <span class="text-right">{{ $adonan->kuantiti }}</span>
                                        </div>
                                    </td>
                                </tr>
                                @php
                                    $total_adonan += $adonan->kuantiti;
                                @endphp
                            @endforeach
                            @php
                                $cnt = count($adonans);
                            @endphp
                            @for ($i = $cnt; $i < $max - 1; $i++)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                    <td>&nbsp;</td>
                                </tr>
                            @endfor
                            <tr>
                                <td colspan="2" class="text-center">Jumlah</td>
                                <td>
                                    <div class="flex flex-row justify-between">
                                        <span>{{ $adonan->satuan->singkatan }}</span>
                                        <span class="text-right">{{ number_format($total_adonan, 2) }}</span>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
