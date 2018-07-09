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

        /* Example API Test Currently
            Need to create loop through all Jockeys with an api id,
            then do a get request to britishhorseracing and update their stats.
            Maybe run a queued job?

        */
        $response= $client->request('GET', 'https://reqres.in/api/users');
        $response = $response->getBody()->getContents();
        echo '<pre>';
        print_r($response);
        exit();
    }
}
