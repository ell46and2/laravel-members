<?php

namespace App\Policies;

use App\Models\Invoice;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class InvoicePolicy
{
    use HandlesAuthorization;

    public function invoice(User $user, Invoice $invoice)
    {
        // must be the author or an admin user
        return $user->id == $invoice->coach_id || $user->role->name === 'admin';
    }

    public function invoiceApprove(User $user, Invoice $invoice)
    {
    	return $user->role->name === 'admin';
    }
}
