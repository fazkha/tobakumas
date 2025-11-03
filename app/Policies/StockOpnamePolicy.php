<?php

namespace App\Policies;

use App\Models\StockOpname;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class StockOpnamePolicy
{
    public function __construct()
    {
        //
    }

    public function viewAny()
    {
        //
    }

    public function view()
    {
        //
    }

    public function create()
    {
        //
    }

    public function update(User $user, StockOpname $order): Response
    {
        return $order->approved == 1
            ? Response::allow()
            : Response::deny('You are not allowed!');
    }

    public function delete(User $user, StockOpname $order): Response
    {
        return $order->approved == 1
            ? Response::allow()
            : Response::deny('You are not allowed!');
    }

    public function restore()
    {
        //
    }

    public function forceDelete()
    {
        //
    }
}
