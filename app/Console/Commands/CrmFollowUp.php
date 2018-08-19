<?php

namespace App\Console\Commands;

use App\Models\CrmRecord;
use App\Models\Jet;
use Carbon\Carbon;
use Illuminate\Console\Command;

class CrmFollowUp extends Command
{
    /*
        A JETS may set a reminder for a follow-up activity. This will send a reminder notification to the JETS and the Jockey.
     */

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:crm-follow-up-notify';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Notify Jets and Jockey if Crm Records review date is today';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        // Get all CrmRecords with a review date that is today
        // Notify all Jets and the Jockey (only if actual jcp jockey)
        $crms = CrmRecord::whereDate('review_date', Carbon::today())->get();

        $jets = Jet::all();

        foreach ($crms as $crm) {
            if($crm->managable_type === 'jockey') {
                $crm->notifications()->create([
                    'user_id' => $crm->managable->id,
                    'body' => 'You have a JETS CRM record with a review date of today.'
                ]);
            }
            
            foreach ($jets as $jet) {
                $crm->notifications()->create([
                    'user_id' => $jet->id,
                    'body' => 'There is a JETS CRM record with a review date of today.'
                ]);
            }
            
        }
    }
}
