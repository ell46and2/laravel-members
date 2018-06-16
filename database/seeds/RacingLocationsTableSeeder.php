<?php

use App\Models\RacingLocation;
use Illuminate\Database\Seeder;

class RacingLocationsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
	       'Aintree',
	        'Ascot',
	        'Ayr',
	        'Bangor on Dee',
	        'Bath',
	        'Beverley',
	        'Brighton', 	
	        'Carlisle',
			'Cartmel',
			'Catterick',
			'Cheltenham',
			'Chelmsford City',  
			'Chepstow',
			'Chester',
			'Doncaster',
			'Epsom Downs', 
			'Exeter',
			'Fakenham',
			'Ffos Las',
			'Fontwell Park', 
			'Goodwood',
			'Great Yarmouth',
			'Hamilton Park',
			'Haydock Park', 
			'Hereford',
			'Hexham',
			'Huntingdon',
			'Kelso', 
			'Kempton Park',
			'Leicester',
			'Lingfield Park',
			'Ludlow', 
			'Market Rasen',
			'Musselburgh',
			'Newbury',
			'Newcastle', 
			'Newmarket',
			'Newton Abbot',
			'Nottingham',
			'Perth', 
			'Plumpton',
			'Pontefract',
			'Redcar',
			'Ripon', 
			'Salisbury',
			'Sandown Park',
			'Sedgefield',
			'Southwell', 
			'Stratford-on-Avon',
			'Taunton',
			'Thirsk',
			'Towcester', 
			'Uttoxeter',
			'Warwick',
			'Wetherby',
			'Wincanton', 
			'Windsor',
			'Wolverhampton',
			'Worcester',
			'York' 
		];

		foreach ($locations as $location) {
        	RacingLocation::create([
        		'name' => $location
        	])->save();
        }
    }
}
