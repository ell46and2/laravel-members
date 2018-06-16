<?php

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
        	'Salisbury series',
        ];

        foreach ($types as $type) {
        	SeriesType::create([
        		'name' => $type
        	])->save();
        }
    }
}
