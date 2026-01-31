<html lang="en">

<head>
    <title>Invoice</title>
    <style>
        @page {
            margin: 0;
            margin-top: 2px;
            margin-bottom: 2px;
            margin-left: 2px;
            margin-right: 2px;
            font-family: Arial, sans-serif;
            font-size: 10px;
            font-weight: normal;
        }

        .table_left {
            overflow: auto;
            width: 100%;
        }

        .table_left table {
            border: 1px solid #dededf;
            height: auto;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 1px;
            text-align: left;
        }

        .table_left caption {
            caption-side: top;
            text-align: left;
        }

        .table_left th {
            border: 1px solid #dededf;
            background-color: #eceff1;
            color: #000000;
            padding: 3px;
        }

        .table_left td {
            border: 1px solid #dededf;
            background-color: #ffffff;
            color: #000000;
            padding: 3px;
        }

        .table_right {
            overflow: auto;
            width: 100%;
        }

        .table_right table {
            border: 1px solid #dededf;
            height: auto;
            width: 100%;
            table-layout: fixed;
            border-collapse: collapse;
            border-spacing: 1px;
            text-align: left;
        }

        .table_right caption {
            caption-side: top;
            text-align: left;
        }

        .table_right th {
            border: 1px solid #dededf;
            background-color: #eceff1;
            color: #000000;
            padding: 3px;
        }

        .table_right td {
            border: 1px solid #dededf;
            background-color: #ffffff;
            color: #000000;
            padding: 3px;
        }
    </style>
</head>

<body>
    @php
        $urut = 0;
    @endphp

    <table style="width: {{ count($selected) > 1 ? '100' : '50' }}%">
        <tr>
            @foreach ($selected as $select)
                @php
                    $datas = App\Models\SaleOrder::find($select);
                    $details = App\Models\SaleOrderDetail::where('sale_order_id', $select)->orderBy('barang_id')->get();
                    $details0 = SaleOrderDetail::join('barangs', 'sale_order_details.barang_id', '=', 'barangs.id')
                        ->where('sale_order_details.sale_order_id', $datas->id)
                        ->where('barangs.stock', '<=', 0)
                        ->first();
                    $adonans = App\Models\SaleOrderMitra::where('sale_order_id', $select)
                        ->orderBy('gerobak_id')
                        ->orderBy('barang_id')
                        ->get();
                    $adonans0 = SaleOrderMitra::join('barangs', 'sale_order_mitras.barang_id', '=', 'barangs.id')
                        ->where('sale_order_mitras.sale_order_id', $datas->id)
                        ->where('barangs.stock', '<=', 0)
                        ->first();

                    if ($details0 || $adonans0) {
                        return response()->json([
                            'status' => 'Stok Barang Tidak Mencukupi! Tidak dapat mencetak invoice.',
                        ], 200);
                    }
                    ++$urut;
                @endphp

                <td>
                    <table style="width: 100%" border="0">
                        <tr>
                            <td style="width: 80%; padding-right: 8px; vertical-align: top;">
                                <table style="width: 100%">
                                    <tr>
                                        <td>{{ $datas->no_order }}</td>
                                        <td style="text-align: right">HKE: {{ $datas->hke }}</td>
                                    </tr>
                                </table>
                                <div class="table_left">
                                    <div>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th style="width: 60%">Nama barang</th>
                                                    <th style="width: 40%">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($details as $detail)
                                                    <tr>
                                                        <td>{{ $detail->barang->nama }}</td>
                                                        <td style="text-align: right">
                                                            <div>
                                                                <span>{{ $detail->satuan->singkatan }}</span>
                                                                <span>{{ $detail->kuantiti }}</span>
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
                            </td>

                            <td style="width: 120%; vertical-align: top;">
                                <table style="width: 100%">
                                    <tr>
                                        <td>{{ $datas->customer->nama }}</td>
                                        <td style="text-align: right">
                                            {{ date_format(date_create($datas->tanggal), 'd/m/Y') }}
                                        </td>
                                    </tr>
                                </table>
                                <div class="table_right">
                                    <div>
                                        <table>
                                            <thead>
                                                <tr>
                                                    <th style="width: 20%">Gerobak</th>
                                                    <th style="width: 60%">Nama barang</th>
                                                    <th style="width: 20%">Jumlah</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @php
                                                    $total_adonan = 0;
                                                    if (count($adonans) > 0) {
                                                @endphp
                                                @foreach ($adonans as $adonan)
                                                    <tr>
                                                        <td>{{ $adonan->gerobak_id ? $adonan->gerobak->kode : ($adonan->pegawai_id ? $adonan->pegawai->nama_lengkap : ($adonan->nama_mitra ? $adonan->nama_mitra : '-')) }}
                                                        </td>
                                                        <td>
                                                            {{ $adonan->barang->nama }}
                                                            ({{ str_replace('Adonan ', '', $adonan->keterangan) }})
                                                        </td>
                                                        <td style="text-align: right">
                                                            <div>
                                                                <span>{{ $adonan->satuan->singkatan }}</span>
                                                                <span>{{ $adonan->kuantiti }}</span>
                                                            </div>
                                                        </td>
                                                    </tr>
                                                    @php
                                                        $total_adonan += $adonan->kuantiti;
                                                    @endphp
                                                @endforeach
                                                @php
                                                    }
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
                                                    <td colspan="2">Jumlah</td>
                                                    <td style="text-align: right;">
                                                        <div>
                                                            <span>{{ $cnt > 0 ?? $adonan->satuan->singkatan }}</span>
                                                            <span>{{ number_format($total_adonan, 2) }}</span>
                                                        </div>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </table>
                </td>

                @if ($urut % 2 == 0)
        </tr>
        <tr>
            @endif
            @endforeach
        </tr>

    </table>
</body>

</html>
