<?php

use App\Models\Role;
use Illuminate\Database\Seeder;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
    	Role::create([
        	'name' => 'jockey',
        	'label' => 'Jockey'
        ])->save();

        Role::create([
        	'name' => 'coach',
        	'label' => 'Coach'
        ])->save();

        Role::create([
        	'name' => 'admin',
        	'label' => 'Admin'
        ])->save();

        
    }
}
