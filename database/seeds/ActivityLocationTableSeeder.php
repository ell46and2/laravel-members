<?php

use App\Models\ActivityLocation;
use Illuminate\Database\Seeder;

class ActivityLocationTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            'British Racing School',
            'Oaksey House',
            'JBH',
            'Racecourse',
            'In-yard',
            'Home',
            'Other (free-text)'
        ];

        foreach ($locations as $location) {
            ActivityLocation::create([
                'name' => $location,
            ])->save();
        }
    }
}
