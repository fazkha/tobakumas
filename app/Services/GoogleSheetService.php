<?php

namespace App\Services;

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
        // $spreadsheetId = config('google.sheets.spreadsheet_id');
        $spreadsheetId = GoogleSheet::where('tahun', date('Y'))->where('bulan', date('n'))->where('isactive', 1)->value('sheet_id');

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
