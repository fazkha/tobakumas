<div style="width: 100%; margin: 0 auto; text-align: center;">
    <h1 style="font-size: 20px">Laporan @lang('messages.delivery')</h1>
    <div style="font-size: 14px;">
        <table style="width: auto;">
            <tr>
                <td style="text-align: left;">{{ $bulan == 'Semua' ? 'Tahun' : 'Bulan' }}</td>
                <td style="width: 20px; text-align: center;">:</td>
                <td style="text-align: left;">
                    {{ $bulan == 'Semua' ? date('Y') : $bulan . ' ' . date('Y') }}
                </td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: left;">Staff</td>
                <td style="width: 20px; text-align: center;">:</td>
                <td style="width: 50%; text-align: left;">{{ $staff }}</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: left;">Jabatan</td>
                <td style="width: 20px; text-align: center;">:</td>
                <td style="width: 50%; text-align: left;">{{ $jabatan }}</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: left;">Jam Pengiriman</td>
                <td style="width: 20px; text-align: center;">:</td>
                <td style="width: 50%; text-align: left;">{{ $jam1 . ' - ' . $jam2 }}</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: left;">Rute Penugasan</td>
                <td style="width: 20px; text-align: center;">:</td>
                <td style="width: 50%; text-align: left;">{{ $rute }}</td>
            </tr>
        </table>
    </div>
</div>
