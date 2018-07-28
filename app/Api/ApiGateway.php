<?php  

namespace App\Api;

use GuzzleHttp\Client;

class ApiGateway
{
	private $client;
	private $tokenAccess;

	public function __construct()
	{
	    $this->client = new Client();
	    $this->accessToken = $this->authenticate();
	}

	private function authenticate()
	{
		try {
            $response = $this->client->request('POST', 'https://api06.britishhorseracing.com/oauth/token', [
                'form_params' => config('jcp.sports_office_api.oauth_params')
            ]);

            $response = $response->getBody()->getContents();
            return 'Bearer ' . json_decode($response)->access_token;

        } catch (Exception $e) {
            // log error and exit
            report($e);
        }     
	}

	public function getJockeyStats($apiId)
    {
        try {
            $response = $this->client->request('GET', "https://api06.britishhorseracing.com/coaching/v1/jockeys/{$apiId}", [
                'headers' => [
                    'Authorization' => $this->accessToken
                ]
            ]);

            return json_decode($response->getBody()->getContents());

        } catch (Exception $e) {
            // log error and resume.
            report($e);
            
            return null;
        }       
    }

    public function getRacesForYear($year)
    {

        $response = $this->client->request('GET', "https://api06.britishhorseracing.com/coaching/v1/res/races?year={$year}", [
            'headers' => [
                'Authorization' => $this->accessToken
            ]
        ]);

        $response = json_decode($response->getBody()->getContents());

        $raceDataArray = collect($response->data);
        
        if($response->next_page_url) {
            for ($i=2; $i <= $response->last_page ; $i++) { 
                
                $response = $this->client->request('GET', "https://api06.britishhorseracing.com/coaching/v1/res/races?year={$year}&page={$i}", [
                    'headers' => [
                        'Authorization' => $this->accessToken
                    ]
                ]);

                $response = json_decode($response->getBody()->getContents());

                $raceDataArray = $raceDataArray->concat(collect($response->data));
            }
        }

        return $raceDataArray;
    }

    public function getRaceResult($race, $divisionNum)
    {
        $response = $this->client->request('GET', "https://api06.britishhorseracing.com/coaching/v1/res/races/{$race->yearOfRace}/{$race->raceId}/{$divisionNum}/result", [
            'headers' => [
                'Authorization' => $this->accessToken
            ]
        ]);

        return collect(json_decode($response->getBody()->getContents()));
    }

    public function calcPrizeMoney(Array $seasonDetails)
    {
        $prizeMoney = 0;

        foreach ($seasonDetails as $season) {
            $seasonPrizeMoney = isset($season->prizeMoney) ? $season->prizeMoney : 0;
            $prizeMoney = $prizeMoney + $seasonPrizeMoney;
        }

        return $prizeMoney;
    }
}

