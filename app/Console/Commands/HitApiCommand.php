<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class HitApiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:hit';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Hits the /motels-store route and logs the response';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $url = config('app.url') . '/motels-store'; // Ensure this points to your correct route URL
        try {
            // Sending a GET request to the URL

            Log::info('Scheduler test ran at ' . now());

            $response = Http::get($url);


            // Check if the response is successful
            if ($response->successful()) {
                $this->info('Successfully hit the /motels-store route.');
                Log::info('Successfully hit the /motels-store route.', ['status' => $response->status()]);
            } else {
                $this->error('Failed to hit the /motels-store route. Response status: ' . $response->status());
                Log::error('Failed to hit the /motels-store route.', ['status' => $response->status()]);
            }
        } catch (\Exception $e) {
            // Log any exceptions that occur
            $this->error('Error while hitting /motels-store route: ' . $e->getMessage());
            Log::error('Error while hitting /motels-store route: ' . $e->getMessage());
        }
    }
}
