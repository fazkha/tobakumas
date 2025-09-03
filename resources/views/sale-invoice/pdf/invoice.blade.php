<html lang="en">

<head>
    <title>Invoice</title>
    <style>
        body {
            font-family: 'Dosis', sans-serif;
            font-size: 10px;
        }

        .table_component {
            overflow: auto;
            width: 50%;
            font-size: 10px;
        }

        .table_component table {
            border: 1px solid #dededf;
            height: auto;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 1px;
            text-align: left;
            font-size: 10px;
        }

        .table_component caption {
            caption-side: top;
            text-align: left;
            font-size: 10px;
        }

        .table_component th {
            border: 1px solid #dededf;
            background-color: #eceff1;
            color: #000000;
            padding: 3px;
            font-size: 10px;
        }

        .table_component td {
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
        <div class="flex flex-col p-4">
            <div class="flex flex-row justify-between">
                <div>{{ $datas->no_order }}</div>
                <div>{{ $datas->customer->nama }}</div>
                <div>{{ date_format(date_create($datas->tanggal), 'd/m/Y') }}</div>
            </div>

            <div class="flex flex-row gap-4">
                <div class="table_component" role="region" tabindex="0">
                    <table>
                        <thead>
                            <tr>
                                <th class="w-1/6">HKE</th>
                                <th class="w-1/2">Nama barang</th>
                                <th class="w-auto">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($details as $detail)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>{{ $detail->barang->nama }}</td>
                                    <td>
                                        <div class="flex flex-row justify-between">
                                            <span>{{ $detail->satuan->singkatan }}</span>
                                            <span class="text-right">{{ $detail->kuantiti }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <div class="table_component" role="region" tabindex="0">
                    <table>
                        <thead>
                            <tr>
                                <th class="w-1/6">HKE</th>
                                <th class="w-1/3">Nama mitra</th>
                                <th class="w-1/3">Nama barang</th>
                                <th class="w-auto">Jumlah</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($adonans as $adonan)
                                <tr>
                                    <td>&nbsp;</td>
                                    <td>{{ $adonan->pegawai->nama_lengkap }}</td>
                                    <td>{{ $adonan->barang->nama }}</td>
                                    <td>
                                        <div class="flex flex-row justify-between">
                                            <span>{{ $adonan->satuan->singkatan }}</span>
                                            <span class="text-right">{{ $adonan->kuantiti }}</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>
