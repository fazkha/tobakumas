<?php

namespace App\Http\Controllers;

use App\Models\JabatanPegawai;
use App\Http\Requests\JabatanPegawaiRequest;
use Illuminate\Http\Request;

class JabatanPegawaiController extends Controller
{
    public function store(JabatanPegawaiRequest $request)
    {
        dd($request->isactive);

        if ($request->validated()) {
            $jabatan = JabatanPegawai::create([
                'branch_id' => $request->branch_id,
                'division_id' => $request->division_id,
                'pegawai_id' => $request->pegawai_id,
                'jabatan_id' => $request->jabatan_id,
                'tanggal_mulai' => $request->tanggal_mulai,
                'tanggal_akhir' => $request->tanggal_akhir,
                'keterangan' => $request->keterangan,
                'isactive' => ($request->isactive == 'on' ? 1 : 0),
                'created_by' => auth()->user()->email,
                'updated_by' => auth()->user()->email,
            ]);

            if ($jabatan) {
                $riwayats = JabatanPegawai::where('pegawai_id', $request->pegawai_id)->get();
                $viewMode = false;

                $view = view('pegawai.partials.details', compact(['riwayats', 'viewMode']))->render();

                return response()->json([
                    'view' => $view,
                ], 200);
            }
        }

        return response()->json([
            'status' => 'Not Found',
        ], 400);
    }

    public function update(Request $request)
    {
        //
    }

    public function destroy(Request $request)
    {
        //
    }
}
