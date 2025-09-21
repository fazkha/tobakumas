<div style="width: 100%; margin: 0 auto; text-align: center;">
    <h1 style="font-size: 20px">Laporan @lang('messages.stockadjustment')</h1>
    <div style="font-size: 14px;">
        <table style="width: auto;">
            <tr>
                <td style="text-align: left;">Hari</td>
                <td style="width: 20px; text-align: center;">:</td>
                <td style="text-align: left;">{{ $hari }}</td>
            </tr>
            <tr>
                <td style="width: 50%; text-align: left;">Tanggal</td>
                <td style="width: 20px; text-align: center;">:</td>
                <td style="width: 50%; text-align: left;">
                    {{ date('j', strtotime($datas->tanggal_adjustment)) . ' ' . $bulan . ' ' . date('Y', strtotime($datas->tanggal_adjustment)) }}
                </td>
            </tr>
        </table>
    </div>
</div>
