<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class NewsController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth:sanctum', except: ['index', 'show'])
        ];
    }

    public function index()
    {
        $headline = News::select(['title', 'sub_title', 'content'])
            ->selectRaw('MD5(RAND()) as xid')
            ->selectRaw('concat(lokasi, concat("/", content_image)) as content_image')
            ->selectRaw('concat(trim(left(content, 100)), "...") as content_trim')
            ->selectRaw('DATE_FORMAT(release_date, \'%M %e, %Y\') as release_date')
            ->where('isactive', 1)
            ->where('headline', 1)
            ->orderBy('release_date', 'desc')
            ->get();

        $other = News::select(['title', 'sub_title', 'content'])
            ->selectRaw('MD5(RAND()) as xid')
            ->selectRaw('concat(lokasi, concat("/", content_image)) as content_image')
            ->selectRaw('concat(trim(left(content, 100)), "...") as content_trim')
            ->selectRaw('DATE_FORMAT(release_date, \'%M %e, %Y\') as release_date')
            ->where('isactive', 1)
            ->where('headline', 0)
            ->orderBy('release_date', 'desc')
            ->limit(10)
            ->get();

        return response()->json(['headline' => $headline, 'other' => $other]);
    }

    public function show(string $id)
    {
        //
    }
}
