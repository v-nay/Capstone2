<?php

namespace App\Http\Controllers;

use GuzzleHttp\Client;
use Illuminate\Http\Request;

class MotelController extends Controller
{
    protected $client;

    protected $apiKey;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.google.api_key');
    }
    /**
     * Display the user's profile form.
     */
    public function getMotels(Request $request): View
    {
        $url = 'https://maps.googleapis.com/maps/api/geocode/json';

        $response = $this->client->get($url, [
            'query' => [
                'address' =>'Hornsby',
                'key' => $this->apiKey,
            ],
        ]);

        $data = json_decode($response->getBody(), true);
        dd($data);

        // Check if results exist
        if (! empty($data['results'][0]['geometry']['location'])) {
            $location = $data['results'][0]['geometry']['location'];

            return "{$location['lat']},{$location['lng']}";
        }

        throw new \Exception("Location for suburb '$suburb' not found");
        dd('here');
    }

    public function searchAllMotels(Request $request)
    {
        $suburb = $request->get('suburb', 'Hornsby');
        $radius = $request->get('radius', 5000);

        $url = 'https://maps.googleapis.com/maps/api/geocode/json';
        $response = $this->client->get($url, [
            'query' => [
                'address' => $suburb,
                'key' => $this->apiKey,
            ],
        ]);
        dd($response);

        $data = json_decode($response->getBody(), true);
        dd($data);

        // Check if results exist
        if (! empty($data['results'][0]['geometry']['location'])) {
            $location = $data['results'][0]['geometry']['location'];

            return "{$location['lat']},{$location['lng']}";
        }

        throw new \Exception("Location for suburb '$suburb' not found");

       
           
    }
   

      
}
