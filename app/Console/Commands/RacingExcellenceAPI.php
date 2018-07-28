<?php

namespace App\Console\Commands;

use App\Jobs\RacingExcellence\NotifyAdminNewRE;
use App\Models\Jockey;
use App\Models\RacingExcellence;
use App\Models\RacingExcellenceDivision;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Illuminate\Console\Command;
use App\Api\ApiGateway;

class RacingExcellenceAPI extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:racing-excellence-api';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Pull Racing Excellence races from API and create/update races.';

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
  
        $races = $this->getRaces();
        
        $this->createOrUpdateRace($this->filterRaces($races));
    }

    private function createOrUpdateRace($races)
    {
        // check if already exists by raceId && yearOfRace (raceId only unique to year)
        
        $races->each(function($race) {
            if($racingExcellence = $this->getRaceExcelIfExists($race)) {
                print_r($race);
                $this->updateRace($racingExcellence, $race);
            } else {
                $this->createRacingExcellence($race);
            }
        });
    }

    private function updateRace(RacingExcellence $racingExcellence, $race)
    {
        dd($racingExcellence);
        // check location and series still the same
        // check if each division still the same
        // participants still the same
        // each participants place
        
        // maybe push to a job, as could take awhile
        
        // notify coach and Admin of update
        // What happens if results already posted??????
    }

    private function getRaceExcelIfExists($race)
    {
        return RacingExcellence::where('raceId', $race->raceId)
            ->where('yearOfRace', $race->yearOfRace)
            ->first();
    }


    private function createDivisions(RacingExcellence $racingExcellence, $race)
    {
        $this->createDivision($race, $racingExcellence);

        if($race->numDivisions === 2) {
            $this->createDivision($race, $racingExcellence, 1);
        }
    }

    private function createDivision($race, RacingExcellence $racingExcellence, $divisionNum = 0)
    {
        $raceResult = $this->apiGateway->getRaceResult($race, $divisionNum);

        $division = $racingExcellence->divisions()->create([
            'division_number' => ($divisionNum + 1)
        ]);

        // Add Participants
        $this->addParticpantsToDivision($racingExcellence, $division, $raceResult);
        
    }

    private function addParticpantsToDivision(RacingExcellence $racingExcellence, RacingExcellenceDivision $division, $raceResult)
    {
        foreach ($raceResult as $participant) {
            if($jockey = Jockey::where('api_id', $participant->jockeyId)->first()) {
                $division->participants()->create([
                    'racing_excellence_id' => $racingExcellence->id,
                    'jockey_id' => $jockey->id,
                    'place' => $participant->position,
                    'api_id' => $participant->jockeyId,
                    'api_animal_id' => $participant->animalId,
                    'animal_name' => $participant->animalName,
                    'completed_race' => $participant->position ? true : false,
                ]);
            } else {
                $division->participants()->create([
                    'racing_excellence_id' => $racingExcellence->id,
                    'name' => $participant->jockeyName,
                    'place' => $participant->position,
                    'api_id' => $participant->jockeyId,
                    'api_animal_id' => $participant->animalId,
                    'animal_name' => $participant->animalName,
                    'completed_race' => $participant->position ? true : false,
                ]);
            }
        }
    }

    private function createRacingExcellence($race)
    {
        $racingExcellence = RacingExcellence::create([
            'raceId' => $race->raceId,
            'yearOfRace' => $race->yearOfRace,
            'location' => ucfirst(strtolower($race->course)),
            'series_name' => $race->seriesName,
            'start' => Carbon::createFromFormat('Y-m-d H:i:s', $race->raceDateTime),
        ]);

        $this->createDivisions($racingExcellence, $race);

        dispatch(new NotifyAdminNewRE($racingExcellence));
    }


    private function filterRaces($races)
    {
        $filteredRaces = $races->filter(function($race) {
            // NOTE: change to 3, 30 used for testing
            return (Carbon::parse($race->raceDateTime) > now()->subDays(300)) &&
            $race->resultsAvailable === 1;
            // return (Carbon::parse($race->raceDateTime) > now()->subDays(300));
                
        });

        // if race with same raceId and yearOfRace - we have two divisions     
        $racesWithTwoDivisionsIds = $filteredRaces->where('divisionSequence', 1)->pluck('raceId');

        // Loop through and add how many Divisions
        $filteredRaces = $filteredRaces->map(function($race) use ($racesWithTwoDivisionsIds) {
            if($racesWithTwoDivisionsIds->contains($race->raceId) && $race->divisionSequence === 0) {
                $race->numDivisions = 2;
            } else {
                $race->numDivisions = 1;
            }
            return $race;
        });

        // Remove duplicates race where second divisions
        $filteredRaces = $filteredRaces->filter(function($race) use ($racesWithTwoDivisionsIds) {
            return $race->divisionSequence !== 1;
        });

        return $filteredRaces;  
    }

    private function getRaces()
    {
        $races = $this->apiGateway->getRacesForYear(now()->year);

        // If start of january need previous year too
        if(now()->month === 1 && now()->day < 4) {
            $races = $races->concat($this->apiGateway->getRacesForYear($currentYear - 1 ));
        }

        // dd($races->where('raceId', 8916));

        return $races;
    }
}
