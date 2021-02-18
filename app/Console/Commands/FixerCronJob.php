<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Goutte\Client;
use GuzzleHttp\Client as GuzzleClient;
use App\Models\Currency;

class FixerCronJob extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cronjob:FixerCronJob';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
     * @return int
     */
    public function handle()
    {
        $search_link = 'http://data.fixer.io/api/latest?access_key=23842b7fb2af771ecdda60367222b558&format=1';
        $headers = [
            'Content-Type' => 'application/json'
        ];
        $client = new GuzzleClient([
            'headers' => $headers
        ]);

        try {
            $res = $client->get($search_link);

            if ($res->getBody()) {

                $currency = Currency::whereNotNull('created_at')->first();
                // Log::info(print_r($currency, true));
                if(!$currency) {
                    $currency = new Currency();
                }
                $currency->currency = $res->getBody();
                $currency->save();
            }
        } catch (\Throwable $e) {
            Log::error('Cron Currency : ' . $e->getMessage());
        }
    }
}
