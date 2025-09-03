<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Facades\Storage;

class PdfController extends Controller
{
    public function show(string $filename)
    {
        $path = 'pdfs/' . $filename;

        if (!Storage::exists($path)) {
            abort(404);
        }

        return Response::make(Storage::get($path), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="' . $filename . '"',
        ]);
    }
}
