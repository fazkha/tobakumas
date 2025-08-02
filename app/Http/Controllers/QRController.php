<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QRController extends Controller
{
    public function index()
    {
        return view('qrcode.index');
    }

    // public function submit(Request $request)
    // {
    //     $this->validate($request, [
    //         'link' => 'required|url',
    //     ]);

    //     $code = time();

    //     // untuk format, temen-temen bisa sesuaiin 
    //     // (format yang tersedia: png, eps, dan svg)
    //     // lalu temen-temen bisa menyesuaikan ukuran image QR-nya
    //     // dengan menambahkan ->size(ukuranDalamPixel, contoh: 100);
    //     // QrCode::format('png')->size(100)->generate($request->link);  
    //     $qr = QrCode::format('png')->generate($request->link);
    //     $qrImageName = $code . '.png';

    //     Storage::put('public/qrcodes/' . $qrImageName, $qr);

    //     return view('qrcode.scanner', compact('code'));
    // }
}
