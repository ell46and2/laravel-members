<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class ProfilesTest extends TestCase
{
    use DatabaseMigrations;

    /*
    
		- An admin can view all profiles

		- a coach can view all jockey profiles
			- they see the activities, re, skills profile if its one their jockeys

		- jets can view all jockey profiles

		- jockey can view all coach profiles
    */
}