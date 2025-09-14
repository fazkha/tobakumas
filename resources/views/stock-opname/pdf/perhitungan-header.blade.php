<div style="width: 100%; margin: 0 auto; text-align: center;">
    <h1 style="font-size: 16px">Laporan @lang('messages.stockopname')</h1>
    <div style="font-size: 14px;">
        <table style="width: 100%;">
            <tr>
                <td style="width: 48%; text-align: right;">Hari:</td>
                <td style="width: 52%; text-align: left;">{{ $hari }}</td>
            </tr>
            <tr>
                <td style="text-align: right;">Tanggal:</td>
                <td style="text-align: left;">
                    {{ date('j', strtotime($datas->tanggal)) . ' ' . $bulan . ' ' . date('Y', strtotime($datas->tanggal)) }}
                </td>
            </tr>
        </table>
    </div>
</div>
