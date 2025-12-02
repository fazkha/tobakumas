<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Branch;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function getBranchList()
    {
        $branches = Branch::where('isactive', 1)->whereNot('id', 1)->orderBy('nama')->pluck('nama', 'id');

        return response()->json([
            'status' => 'success',
            'data' => $branches
        ]);
    }
}
