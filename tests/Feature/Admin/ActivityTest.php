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

}