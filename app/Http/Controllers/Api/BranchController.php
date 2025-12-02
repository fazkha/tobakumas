<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function getBranchList()
    {
        $branches = Branch::where('isactive', 1)->whereNot('id', 1)->orderBy('nama')->selectRaw('id, nama as name')->get();

        // $branches = Branch::where('isactive', 1)->whereNot('id', 1)->orderBy('nama')->selectRaw('nama as name, id')->pluck('name', 'id')->all();
        // $branches = Branch::where('isactive', 1)->whereNot('id', 1)->orderBy('nama')->selectRaw('nama as name, id')->get()->toArray();

        $transformedData = $branches->transform(function ($item) {
            $item->json_column_name = json_decode($item->json_column_name);
            return $item;
        });

        return response()->json([
            'status' => 'success',
            'data' => $transformedData->toJson()
        ]);
    }
}
