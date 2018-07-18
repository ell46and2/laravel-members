<?php

namespace Tests\Feature\Admin;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Mail;
use Tests\TestCase;

class InvoiceTest extends TestCase
{
    use DatabaseMigrations;


    // For a submitted invoice an admin can edit the invoiceLines value.
    //  - this will need to update the overall invoice value
    //  - On return sent them back to same point in the invoice at which they clicked edit from (Look at giving each line in the invoice a unique id that we can pass back and forth from the edit back to the showInvoice views.)
    
    // RE cannot be edited - only removed
    
    // Mileage and Misc are editable and can be removed.
    
    // Can create an invoice on behalf of coach
    // Can submit the invoice on behalf of coach
}