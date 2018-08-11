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

        $jockeyApiIds = $jockeys->pluck('api_id');

        $allJockeysData = $this->apiGateway->getAllJockeyStats()
            ->keyBy('jockeyId')
            ->filter(function($value, $key) use ($jockeyApiIds) {
                return $jockeyApiIds->contains($key);
            });

        foreach ($jockeys->chunk(40) as $chunk) {
            $chunk->each(function($jockey) use ($allJockeysData) {
                $statsFromAPI = $allJockeysData->get($jockey->api_id);
                if($statsFromAPI) {
                  $this->updateJockey($jockey, $statsFromAPI);  
                }            
            });
        }       
    }

    private function updateJockey(Jockey $jockey, $statsFromAPI)
    {     
        $jockey->update([
            'wins' => isset($statsFromAPI->careerSummary[0]) ? $statsFromAPI->careerSummary[0]->numberOfWins : null,
            'rides' => isset($statsFromAPI->careerSummary[0]) ? $statsFromAPI->careerSummary[0]->numberOfRides : null,
            'lowest_riding_weight' => isset($statsFromAPI->lowestRidingWeight) ? $statsFromAPI->lowestRidingWeight : null,
            'licence_type' => isset($statsFromAPI->licences[0]) ? $statsFromAPI->licences[0]->licenceType : null,
            'licence_date' => isset($statsFromAPI->licences[0]) ? $statsFromAPI->licences[0]->issueDate : null,
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
