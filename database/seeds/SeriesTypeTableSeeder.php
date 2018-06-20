<?php

use App\Models\SeriesScoring;
use App\Models\SeriesType;
use Illuminate\Database\Seeder;

class SeriesTypeTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
        	'Apprentice (hands and heels)',
        	'Conditional (hands and heels)',
        	'All weather (hands and heels)',
        	'Apprentice training series',
        	'Conditional training series',
        	'Haydock series',
        ];

        $standardScoring = [
            [
                'place' => 1,
                'points' => 5
            ],
            [
                'place' => 2,
                'points' => 3
            ],
            [
                'place' => 3,
                'points' => 2
            ],
            [
                'place' => 4,
                'points' => 1
            ],
        ];

        foreach ($types as $type) {
        	$series = SeriesType::create([
        		'name' => $type
        	]);

            foreach ($standardScoring as $score) {
                $series->scoring()->create([
                    'place' => $score['place'],
                    'points' => $score['points']
                ]);
            }         
        }

        /*
        Salisbury series scored differently
        */   
        $salisbury = SeriesType::create([
            'name' => 'Salisbury series',
            'total_just_from_place' => true
        ]);

        $salisburyScoring = [
            [
                'place' => 1,
                'points' => 10
            ],
            [
                'place' => 2,
                'points' => 6
            ],
            [
                'place' => 3,
                'points' => 4
            ],
            [
                'place' => 4,
                'points' => 3
            ],
            [
                'place' => 5,
                'points' => 2
            ],
            [
                'place' => 6,
                'points' => 1
            ],
        ];

        foreach ($salisburyScoring as $score) {
            $salisbury->scoring()->create([
                'place' => $score['place'],
                'points' => $score['points']
            ]);
        }    
    }
}
