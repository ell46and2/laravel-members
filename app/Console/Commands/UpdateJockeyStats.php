<?php

namespace App\Console\Commands;

use App\Api\ApiGateway;
use App\Models\Jockey;
use GuzzleHttp\Client;
use Illuminate\Console\Command;

class UpdateJockeyStats extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:update-jockey-stats';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update the stats of each jockey who has an api id.';

    private $apiGateway; 

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
        $this->apiGateway = new ApiGateway;

        $this->updateJockeys();
    }

    private function updateJockeys()
    {
        $jockeys = $this->getJockeysWithApiIds();

        if(!$jockeys) {
            exit();
        }

        foreach ($jockeys->chunk(40) as $chunk) {
            $chunk->each(function($jockey) {
                $this->updateJockey($jockey);
            });
        }       
    }

    private function updateJockey(Jockey $jockey)
    {     
        $statsFromAPI = $this->apiGateway->getJockeyStats($jockey->api_id);

        if(!$statsFromAPI) {
            return;
        }

        $jockey->update([
            'wins' => isset($statsFromAPI->careerSummary[0]) ? $statsFromAPI->careerSummary[0]->numberOfWins : null,
            'rides' => isset($statsFromAPI->careerSummary[0]) ? $statsFromAPI->careerSummary[0]->numberOfRides : null,
            'lowest_riding_weight' => isset($statsFromAPI->lowestRidingWeight) ? $statsFromAPI->lowestRidingWeight : null,
            'licence_type' => isset($statsFromAPI->licences[0]) ? $statsFromAPI->licences[0]->licenceType : null,
            // 'licence_date'
            'prize_money' => $statsFromAPI->seasonDetails ? $this->apiGateway->calcPrizeMoney($statsFromAPI->seasonDetails) : null,
            'associated_content' => 'https://www.britishhorseracing.com/racing/stewards-reports/#!?q=' . $statsFromAPI->name,
            'trainer_name' => $statsFromAPI->trainerName,
        ]);
    }

    private function getJockeysWithApiIds()
    {
        return Jockey::whereNotNull('api_id')->get();
    }

}
