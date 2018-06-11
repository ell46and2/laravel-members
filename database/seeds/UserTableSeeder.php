<?php

use App\Models\Activity;
use App\Models\Admin;
use App\Models\Coach;
use App\Models\Jockey;
use Carbon\Carbon;
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

        $jockey = factory(Jockey::class)->states('approved')->create([
            'email' => 'jockey@jcp.com',
            'password' => bcrypt('secret'),
        ]);

        $coach = factory(Coach::class)->create([
            'email' => 'coach@jcp.com',
            'password' => bcrypt('secret'),
        ]);

        $coach2 = factory(Coach::class)->create([
            'email' => 'coach2@jcp.com',
            'password' => bcrypt('secret'),
        ]);

        $coach->assignJockey($jockey);
        $coach2->assignJockey($jockey);

        // create upcoming activities
        $upcomingActivity1 = factory(Activity::class)->states('upcoming')->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->addMinutes(120),
            'end' => Carbon::now()->addMinutes(150),
        ]);
        $upcomingActivity1->addJockey($jockey);

        $upcomingActivity2 = factory(Activity::class)->states('upcoming')->create([
            'coach_id' => $coach2,
            'start' => Carbon::now()->addDays(1),
            'end' => Carbon::now()->addDays(1),
        ]);
        $upcomingActivity2->addJockey($jockey);

        $upcomingActivity3 = factory(Activity::class)->states('upcoming')->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->addDays(2),
            'end' => Carbon::now()->addDays(2),
        ]);
        $upcomingActivity3->addJockey($jockey);

        $upcomingActivity4 = factory(Activity::class)->states('upcoming')->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->addDays(3),
            'end' => Carbon::now()->addDays(3),
        ]);
        $upcomingActivity4->addJockey($jockey);

        $upcomingActivity5 = factory(Activity::class)->states('upcoming')->create([
            'coach_id' => $coach2,
            'start' => Carbon::now()->addDays(4),
            'end' => Carbon::now()->addDays(4),
        ]);
        $upcomingActivity5->addJockey($jockey);

        $upcomingActivity6 = factory(Activity::class)->states('upcoming')->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->addDays(5),
            'end' => Carbon::now()->addDays(5),
        ]);
        $upcomingActivity6->addJockey($jockey);

        $upcomingActivity7 = factory(Activity::class)->states('upcoming')->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->addDays(6),
            'end' => Carbon::now()->addDays(6),
        ]);
        $upcomingActivity7->addJockey($jockey);

        $upcomingActivity8 = factory(Activity::class)->states('upcoming')->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->addDays(7),
            'end' => Carbon::now()->addDays(7),
        ]);
        $upcomingActivity8->addJockey($jockey);

        $upcomingActivity9 = factory(Activity::class)->states('upcoming')->create([
            'coach_id' => $coach2,
            'start' => Carbon::now()->addDays(8),
            'end' => Carbon::now()->addDays(8),
        ]);
        $upcomingActivity9->addJockey($jockey);

        $upcomingActivity10 = factory(Activity::class)->states('upcoming')->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->addDays(9),
            'end' => Carbon::now()->addDays(9),
        ]);
        $upcomingActivity10->addJockey($jockey);

        $upcomingActivity11 = factory(Activity::class)->states('upcoming')->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->addDays(10),
            'end' => Carbon::now()->addDays(10),
        ]);
        $upcomingActivity11->addJockey($jockey);


        // create recent activities
        $recentActivity1 = factory(Activity::class)->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->subMinutes(120),
            'end' => Carbon::now()->subMinutes(150),
        ]);
        $recentActivity1->addJockey($jockey);

        $recentActivity2 = factory(Activity::class)->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->subDays(1),
            'end' => Carbon::now()->subDays(1),
        ]);
        $recentActivity2->addJockey($jockey);

        $recentActivity3 = factory(Activity::class)->create([
            'coach_id' => $coach2,
            'start' => Carbon::now()->subDays(2),
            'end' => Carbon::now()->subDays(2),
        ]);
        $recentActivity3->addJockey($jockey);

        $recentActivity4 = factory(Activity::class)->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->subDays(3),
            'end' => Carbon::now()->subDays(3),
        ]);
        $recentActivity4->addJockey($jockey);

        $recentActivity5 = factory(Activity::class)->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->subDays(4),
            'end' => Carbon::now()->subDays(4),
        ]);
        $recentActivity5->addJockey($jockey);

        $recentActivity6 = factory(Activity::class)->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->subDays(5),
            'end' => Carbon::now()->subDays(5),
        ]);
        $recentActivity6->addJockey($jockey);

        $recentActivity7 = factory(Activity::class)->create([
            'coach_id' => $coach2,
            'start' => Carbon::now()->subDays(6),
            'end' => Carbon::now()->subDays(6),
        ]);
        $recentActivity7->addJockey($jockey);

        $recentActivity8 = factory(Activity::class)->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->subDays(7),
            'end' => Carbon::now()->subDays(7),
        ]);
        $recentActivity8->addJockey($jockey);

        $recentActivity9 = factory(Activity::class)->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->subDays(8),
            'end' => Carbon::now()->subDays(8),
        ]);
        $recentActivity9->addJockey($jockey);

        $recentActivity10 = factory(Activity::class)->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->subDays(9),
            'end' => Carbon::now()->subDays(9),
        ]);
        $recentActivity10->addJockey($jockey);

        $recentActivity11 = factory(Activity::class)->create([
            'coach_id' => $coach,
            'start' => Carbon::now()->subDays(10),
            'end' => Carbon::now()->subDays(10),
        ]);
        $recentActivity11->addJockey($jockey);
    }
}
