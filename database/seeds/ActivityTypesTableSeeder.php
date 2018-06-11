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
        ActivityType::create([
        	'name' => 'simulation',
        ])->save();
    }
}
