<?php

namespace App\Services;

use App\Models\AppSetting;
use App\Models\GoogleSheet;
use Google\Client;
use Google\Service\Sheets;

class GoogleSheetService
{
    protected Sheets $service;

    public function __construct()
    {
        $client = new Client();

        $client->setApplicationName(
            config('app.name')
        );

        $credentials = storage_path(
            'app/' . config('google.sheets.credentials')
        );

        $client->setAuthConfig($credentials);

        $client->setScopes([
            Sheets::SPREADSHEETS_READONLY,
        ]);

        $this->service = new Sheets($client);
    }

    public function getValues(string $range): array
    {
        $lpp_toko_tahun = AppSetting::where('parm', 'lpp_toko_tahun')->first();
        $lpp_toko_bulan = AppSetting::where('parm', 'lpp_toko_bulan')->first();

        $tahun = $lpp_toko_tahun ? (int) $lpp_toko_tahun->value : date('Y');
        $bulan = $lpp_toko_bulan ? (int) $lpp_toko_bulan->value : date('n');

        // $spreadsheetId = config('google.sheets.spreadsheet_id');
        // $spreadsheetId = GoogleSheet::where('jenis', 'lpp-toko')->where('tahun', date('Y'))->where('bulan', 8)->where('isactive', 1)->value('sheet_id');
        $spreadsheetId = GoogleSheet::where('jenis', 'lpp-toko')->where('tahun', $tahun)->where('bulan', $bulan)->where('isactive', 1)->value('sheet_id');

        $response = $this->service
            ->spreadsheets_values
            ->get(
                $spreadsheetId,
                $range,
                [
                    'valueRenderOption' => 'UNFORMATTED_VALUE',
                ]
            );

        return $response->getValues() ?? [];
    }
}
