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
            'simulator session',
            'phonecall',
            'meeting',
            'fitness',
            'riding',
        ];

        foreach ($types as $type) {
            ActivityType::create([
                'name' => $type,
            ])->save();
        }
        
    }
}
