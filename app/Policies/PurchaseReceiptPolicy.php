<?php

namespace App\Policies;

use App\Models\User;
use App\Models\PurchaseOrder;
use Illuminate\Auth\Access\Response;

class PurchaseReceiptPolicy
{
    public function __construct()
    {
        //
    }

    public function update(User $user, PurchaseOrder $order): Response
    {
        return $order->isaccepted == 0
            ? Response::allow()
            : Response::deny('You do not own this order.');
    }
}
