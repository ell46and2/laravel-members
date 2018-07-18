<?php

namespace Tests\Feature\Admin;

use App\Mail\Coach\Account\CoachCreatedEmail;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class ActivityTest extends TestCase
{
    use DatabaseMigrations;


    // Cannot create activities
    
    // Can edit them (cannot change the coach).
    
    // Cannot edit the activity if its attached to an approved invoice
    
    // If edit activity and attached to an invoice thats at status review, will need to update the invoiceLine and the overall Invoice total
    
    // Can delete activity - if attached to an invoice thats at status review, will need to update the invoiceLine and the overall Invoice total
}