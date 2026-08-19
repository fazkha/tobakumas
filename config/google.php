<?php

return [
    'sheets' => [
        'spreadsheet_id' => env(
            'GOOGLE_SHEETS_SPREADSHEET_ID'
        ),
        'credentials' => env(
            'GOOGLE_SHEETS_CREDENTIALS'
        ),
    ],
];
