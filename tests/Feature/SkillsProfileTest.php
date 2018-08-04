<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class SkillsProfileTest extends TestCase
{
    use DatabaseMigrations;

    /*
    
		- An Admin can view all

		- Jockey can view all of their own

		- Coach can view all of their assigned Jockeys

		- JETS cannot view any

		- Coach can edit one they have created.

		- Admin can edit all.
    */
}