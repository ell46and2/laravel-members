<?php

namespace Tests\Feature\Jockey;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class CrmRecordTest extends TestCase
{
    use DatabaseMigrations;


    /*
   
    	- can add a crm record for an existing jcp jockey
    		- location, comment (required)
    		- document (optional) - validate filetypes - word docs, pdf, excel, image
    		- review_date (optional)
    		- Jockey get notified

    	- can edit a crm record for an existing jcp jockey
    		- location, comment (required)
    		- document (optional)
    		- review_date (optional)
    		- Can remove an existing document 
    		- Jockey get notified

		- can create a crmJockey
			- first_name, last_name, date_of_birth, gender, county_id, country_id, nationality_id, postcode, email, api_id (required)

		- can add a crm record for a crmJockey
		- can edit a crm record for a crmJockey

		- JETS can delete any CRM record

		- A Jockey can view only their CRM records

		- CRON job to notify all Jets and Jockey when a crmRecord's review_date is today.

		- JETS exporting data
			- JETS managers are required to export their data semi-annually. Due to the sporadic nature of these reports they will 	be run directly by JFD on the database when requested by JETS for a nominal fee which will be more cost effective  than building a reporting tool. An example of the reports required are shown below:
			•	Jockey name
			•	PDP status
			•	PDP last review
			•	Sex
			•	Postcode/Location
			•	Grade (apprentice/conditional/claimer/professional)
			•	Age/DOB
			•	Length of time in industry – does this need to be compared to profile in some way?

     */
}