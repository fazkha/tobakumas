<html>

<head>
    <style>
        body {
            font-family: 'Dosis', sans-serif;
        }

        .table_component {
            overflow: auto;
            width: 100%;
        }

        .table_component table {
            border: 1px solid #dededf;
            height: auto;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 1px;
            text-align: left;
        }

        .table_component caption {
            caption-side: top;
            text-align: left;
        }

        .table_component th {
            border: 1px solid #dededf;
            background-color: #eceff1;
            color: #000000;
            padding: 3px;
        }

        .table_component td {
            border: 1px solid #dededf;
            background-color: #ffffff;
            color: #000000;
            padding: 3px;
        }
    </style>
</head>

<body>
    <div>
        <div>{{ $datas->customer->nama }}</div>
    </div>

    <div class="table_component" role="region" tabindex="0">
        <table>
            <caption>{{ $datas->no_order }}</caption>
            <thead>
                <tr>
                    <th>Nama Barang</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($details as $detail)
                    <tr>
                        <td>{{ $detail->barang->nama }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="table_component" role="region" tabindex="0">
        <table>
            <thead>
                <tr>
                    <th>Nama Mitra</th>
                    <th>Nama Barang</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($adonans as $adonan)
                    <tr>
                        <td>{{ $adonan->pegawai->nama_lengkap }}</td>
                        <td>{{ $adonan->barang->nama }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</body>

</html>
