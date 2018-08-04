<?php

namespace Tests\Feature;


use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\TestCase;

class PdpTest extends TestCase
{
    use DatabaseMigrations;

    /*
    
		- a Jets can create a Pdp for a Jockey - needs to select jockey first.
		- a jockey can create a Pdp for themselves
		- A Jets can view all Pdps
		- a jockey can view only their own pdp

		- a new pdp can only be created if no incomplete pdp already exist for the jockey
		- no fields are required (maybe goals?)
		- can be marked as completed
		- only the goals and actions checkboxes are updatable once the pdp is marked as complete.

		- if a previous pdp exists - when a new pdp is created the fields are copied from the previous
		- if a goal or action is marked as completed, they are not copied over.

		- pdp is broken down into 14 sections - and saved as each page 'next' btn is clicked.

		- jockey cannot delete a completed pdp
		- jockey can delete an incomplete pdp
		- jets cannot delete a completed pdp
		- jets can delete an incomplete pdp

		- notify jets if jockey completes the pdp
		- notify jockey if jets completes the pdp

		- jets marks a submitted pdp as complete

		- prev and next btn don't save, just links, but show warning 'Any unsaved data will not be saved.'

		- extra page after 'Present Support Team' for Jockey to submit the pdp.

		- page marked as complete/ticked when 'Save & Next' button clicked on that page.

    */
}