<?php

use App\Models\ActivityType;
use Illuminate\Database\Seeder;

class ActivityTypesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $types = [
            ['simulator session', 'simulator'],
            ['phonecall', 'phonecall'],
            ['meeting', 'meeting'],
            ['fitness', 'fitness'],
            ['riding', 'riding'],
        ];

        foreach ($types as $type) {
            ActivityType::create([
                'name' => $type[0],
                'icon' => $type[1]
            ])->save();
        }
        
    }
}
