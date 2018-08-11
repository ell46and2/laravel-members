<?php

namespace App\Policies;

use App\Models\InvoiceLine;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoiceLinePolicy
{
    use HandlesAuthorization;

    public function edit(User $user, InvoiceLine $invoiceLine)
    {
        // check line is an activity and user is admin and invoice is not approved
        return $user->role->name === 'admin' && $invoiceLine->invoiceable_type == 'activity' && $invoiceLine->invoice->status !== 'approved';
    }
}
