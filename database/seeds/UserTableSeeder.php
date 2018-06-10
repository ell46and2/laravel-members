<?php

use App\Models\Admin;
use Illuminate\Database\Seeder;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        factory(Admin::class)->create([
        	'email' => 'admin@jcp.com',
            'password' => bcrypt('secret'),
        ]);
    }
}
