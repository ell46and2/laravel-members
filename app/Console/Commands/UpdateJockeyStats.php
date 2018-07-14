<?php

namespace App\Console\Commands;

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
        $client = new Client();

        $response = $client->request('POST', 'https://api01.britishhorseracing.com/sportsoffice/v1/authentication', [
            'form_params' => config('jcp.sports_office_api.auth_params')
        ]);

        $response = $response->getBody()->getContents();
        $accessToken = json_decode($response)->access_token;

        $jockeyId = '993419'; // would come from loop through all jockeys then $jockey->api_id

        $response= $client->request('GET', "https://api01.britishhorseracing.com/sportsoffice/v2/jockeys/{$jockeyId}", [
            'headers' => [
                'Authorization' => $accessToken
            ]
        ]);

        $response = $response->getBody()->getContents();

        dd(json_decode($response)->data[0]);

        // echo '<pre>';
        // print_r($response);
        exit();


        /* Example API Test Currently
            Need to create loop through all Jockeys with an api id,
            then do a get request to britishhorseracing and update their stats.
            Maybe run a queued job?

        */
        // $response= $client->request('GET', 'https://reqres.in/api/users');
        // $response = $response->getBody()->getContents();
        // echo '<pre>';
        // print_r($response);
        // exit();
    }
}
