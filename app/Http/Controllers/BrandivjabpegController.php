<?php

namespace App\Http\Controllers;

use App\Models\Brandivjabpeg;
use App\Http\Requests\BrandivjabpegRequest;
use App\Models\Pegawai;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Crypt;

class BrandivjabpegController extends Controller
{
    public function storeJabatan(BrandivjabpegRequest $request): JsonResponse
    {
        if ($request->validated()) {
            $jabatan = Brandivjabpeg::create([
                'brandivjab_id' => $request->brandivjab_id,
                'pegawai_id' => $request->pegawai_id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
                'keterangan' => $request->keterangan,
                'isactive' => $request->isactive,
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if ($jabatan) {
                $details = Brandivjabpeg::where('pegawai_id', $request->pegawai_id)->orderBy('tanggal_mulai', 'desc')->get();
                $viewMode = false;

                $view = view('pegawai.partials.details', compact(['details', 'viewMode']))->render();

                return response()->json([
                    'view' => $view,
                ], 200);
            }
        }

        return response()->json([
            'status' => 'Not Found',
        ], 400);
    }

    public function deleteJabatan(Request $request): JsonResponse
    {
        $detail = Brandivjabpeg::find($request->jabatan);
        $pegawai = Pegawai::where('id', $detail->pegawai_id)->get();

        $pegawai_id = $detail->pegawai_id;
        $view = [];

        $detail->update([
            'isactive' => 3,
            'tanggal_akhir' => $detail->tanggal_akhir ? $detail->tanggal_akhir : date('Y-m-d'),
        ]);

        // try {
        //     $detail->delete();
        // } catch (\Illuminate\Database\QueryException $e) {
        //     return response()->json(['status' => 'Not Found'], 404);
        // }

        $details = Brandivjabpeg::where('pegawai_id', $pegawai_id)->orderBy('tanggal_mulai', 'desc')->get();
        $viewMode = true;

        if ($details->count() > 0) {
            $view = view('pegawai.partials.details', compact(['details', 'viewMode']))->render();
        }

        if ($view) {
            return response()->json([
                'view' => $view,
            ], 200);
        } else {
            return response()->json([
                'status' => 'Not Found',
            ], 200);
        }
    }
}
