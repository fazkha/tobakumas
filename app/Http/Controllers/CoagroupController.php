<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;

class CoagroupController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware('permission:coagroup-list', only: ['index', 'fetch']),
            new Middleware('permission:coagroup-create', only: ['create', 'store']),
            new Middleware('permission:coagroup-edit', only: ['edit', 'update']),
            new Middleware('permission:coagroup-show', only: ['show']),
            new Middleware('permission:coagroup-delete', only: ['delete', 'destroy']),
        ];
    }

    public function index()
    {
        //
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        //
    }

    public function edit(string $id)
    {
        //
    }

    public function update(Request $request, string $id)
    {
        //
    }

    public function destroy(string $id)
    {
        //
    }
}
