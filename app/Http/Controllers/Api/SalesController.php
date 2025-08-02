<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Sales;
use App\Models\SalesDetail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Query\JoinClause;

class SalesController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    public function index(Request $request)
    {
        // note1: bersihkan record2 master yang tidak mempunyai record2 detail
        $q1 = DB::table('sales')
            ->selectRaw('sales.id, COUNT(sales_detail.id) AS cnt')
            ->leftJoin('sales_detail', 'sales.id', '=', 'sales_detail.sales_id')
            ->groupBy('sales.id');

        $q2 = DB::table('sales')
            ->joinSub($q1, 'subsales', function (JoinClause $j) {
                $j->on('sales.id', '=', 'subsales.id');
            })
            ->where('cnt', 0)
            ->delete();
        // end - note1

        switch ($request->key) {
            case 'hari-ini':
                $sumSales = DB::table('sales_detail')
                    ->select('sales_id', DB::raw('SUM(hasil) as hasil'))
                    ->groupBy('sales_id');

                $sales = DB::table('sales')
                    ->where('user_id', $request->user_id)
                    ->where('branch_id', $request->branch_id)
                    ->where('tanggal', date('d-m-Y'))
                    ->joinSub($sumSales, 'sales_detail', function (JoinClause $join) {
                        $join->on('sales.id', '=', 'sales_detail.sales_id');
                    })
                    ->get();

                break;

            default:
                $sumSales = DB::table('sales_detail')
                    ->select('sales_id', DB::raw('SUM(hasil) as hasil'))
                    ->groupBy('sales_id');

                $sales = DB::table('sales')
                    ->where('user_id', $request->user_id)
                    ->where('branch_id', $request->branch_id)
                    ->whereRaw('UNIX_TIMESTAMP(STR_TO_DATE(tanggal, "%d-%m-%Y")) < UNIX_TIMESTAMP()')
                    ->whereNot(function ($query) {
                        $query->where('tanggal', date('d-m-Y'));
                    })
                    ->orderByRaw('UNIX_TIMESTAMP(STR_TO_DATE(tanggal, "%d-%m-%Y")) DESC')
                    ->joinSub($sumSales, 'sales_detail', function (JoinClause $join) {
                        $join->on('sales.id', '=', 'sales_detail.sales_id');
                    })
                    ->get();

                break;
        }

        return [
            'sales' => $sales
        ];
    }

    public function status(Request $request)
    {
        $sales = Sales::find($request->id);

        if (!$sales) {
            return response([
                'message' => 'Data Not Found.'
            ], 404);
        }

        $total_hasil = SalesDetail::where('sales_id', $request->id)->sum('hasil');

        return [
            'tanggal' => $sales->tanggal,
            'total_hasil' => $total_hasil,
            'jarak' => $sales->jarak,
            'waktu' => $sales->waktu,
        ];
    }

    public function getMarkers(Request $request)
    {
        $sales = Sales::find($request->id);

        if (!$sales) {
            return response([
                'message' => 'Data Not Found.'
            ], 404);
        }

        $details = SalesDetail::where('sales_id', $request->id)->get();

        return [
            'markers' => $details,
        ];
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'branch_id' => ['required', 'exists:branches,id'],
            'user_id' => ['required', 'exists:users,id'],
            'tanggal' => ['required', 'min:10', 'max:10'],
            'waktu' => ['required'],
            'waktu_detik' => ['required'],
            'jarak' => ['required'],
            'jam' => ['required'],
            'lat' => ['required'],
            'lng' => ['required'],
            'hasil' => ['required'],
            'catatan' => ['max:50'],
        ]);

        if ($validator->fails()) {
            $errors = $validator->errors();

            foreach ($errors->all() as $message) {
                return response([
                    'message' => $message
                ], 422);
            }
        }

        $data_master = $validator->safe()->only(['branch_id', 'user_id', 'tanggal', 'waktu', 'waktu_detik', 'jarak']);
        $data_detail = $validator->safe()->only(['jam', 'lat', 'lng', 'hasil', 'catatan']);

        $user = User::where('id', $request->user_id)->first();
        $additional = [
            'created_by' => $user->name,
            'updated_by' => $user->name,
        ];

        $sales = Sales::where('user_id', $request->user_id)
            ->where('branch_id', $request->branch_id)
            ->where('tanggal', $request->tanggal)
            ->first();

        if ($sales) {
            $marker_cnt = SalesDetail::where('sales_id', $sales->id)->count('id');

            if ($marker_cnt > 0 && $request->waktu_detik < $sales->waktu_detik) {
                // do not update
            } else {
                $sales->update([
                    'waktu_detik' => $request->waktu_detik,
                    'waktu' => $request->waktu,
                    'jarak' => $request->jarak,
                ]);
            }
        } else {
            $marker_cnt = 0;
            $sales = Sales::create($data_master + $additional);
        }

        $detail_id = [
            'sales_id' => $sales->id,
            'marker_id' => 'marker-' . $marker_cnt + 1,
        ];

        $sales_detail = SalesDetail::create($detail_id + $data_detail + $additional);

        if (!$sales) {
            return response([
                'message' => 'Server error.'
            ], 500);
        }

        return [
            'sales' => $sales,
        ];
    }

    public function show(string $id)
    {
        //
    }
}
