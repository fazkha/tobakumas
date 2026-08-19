<?php

namespace App\Services;

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
        $spreadsheetId = config('google.sheets.spreadsheet_id');

        $response = $this->service
            ->spreadsheets_values
            ->get(
                $spreadsheetId,
                $range
            );

        return $response->getValues() ?? [];
    }
}
