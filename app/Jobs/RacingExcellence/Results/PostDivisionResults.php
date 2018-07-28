<?php

namespace App\Jobs\RacingExcellence\Results;

use App\Models\RacingExcellenceDivision;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use GuzzleHttp\Client;

class PostDivisionResults implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $division;
    public $body;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(RacingExcellenceDivision $division, $body)
    {
        $this->division = $division;
        $this->body = $body;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $race = $this->division->racingExcellence;

        $divisionSequence = $this->division->division_number - 1;

        $client = new Client();

        $accessToken = $this->authenticate($client);

        $postData = config('jcp.sports_office_api.oauth_params');
        $postData['body'] = $this->body;

        $response = $client->request('POST', "https://api06.britishhorseracing.com/coaching/v1/res/races/{$race->yearOfRace}/{$race->raceId}/{$divisionSequence}", [
            'form_params' => $this->body,
            'headers' => [
                'Authorization' => 'Bearer ' . $accessToken
            ]
        ]);

        if($response->getStatusCode() === 200) {
            $this->division->update([
                'posted_to_api' => now()
            ]);
        } else {
            // throw exception and Log error
        }

        // Add posted boolean field to divisions table - default false
        // After posting set to true.
        

        // "https://api06.britishhorseracing.com/coaching/v1/res/races/{$race->yearOfRace}/{$race->raceId}/{$divisionSequence}"

    }

    private function authenticate($client)
    {
        $response = $client->request('POST', 'https://api06.britishhorseracing.com/oauth/token', [
            'form_params' => config('jcp.sports_office_api.oauth_params')
        ]);

        $response = $response->getBody()->getContents();

        return json_decode($response)->access_token;
    }
}
