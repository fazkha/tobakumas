<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\BranchResource;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function getBranchList()
    {
        // $branches = Branch::where('isactive', 1)->whereNot('id', 1)->orderBy('nama')->selectRaw('nama as name, id')->pluck('name', 'id')->all();
        // $branches = Branch::where('isactive', 1)->whereNot('id', 1)->orderBy('nama')->selectRaw('nama as name, id')->get()->toArray();
        // $branches = Branch::where('isactive', 1)->whereNot('id', 1)->orderBy('nama')->selectRaw('id, nama as name')->get()->toJson();
        $branches = Branch::where('isactive', 1)->whereNot('id', 1)->orderBy('nama')->selectRaw('id, nama as name');

        return [
            'status' => 'success',
            'data' => new BranchResource($branches)
        ];
    }
}
