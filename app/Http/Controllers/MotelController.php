<?php

namespace App\Http\Controllers;

use App\Models\Motel;
use GuzzleHttp\Client;
use App\Models\Ranking;
use Illuminate\Http\Request;
use App\Http\Requests\MotelRequest;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Http;

class MotelController extends Controller
{
    protected $client;

    protected $apiKey;
    protected $url;

    public function __construct()
    {
        $this->client = new Client();
        $this->apiKey = config('services.google.api_key');
        $this->url = 'https://generativelanguage.googleapis.com/v1beta/models/gemini-2.0-flash:generateContent'
            . '?key=' . $this->apiKey;
    }

    public function store(Request $request)
    {
        Log::info('Hit /motels-store controller');
        // $query = $request->input('query') ?? 'seymour';
        $query = 'seymour';
        $perPage =  5;
        // $sort_by = $request->input('sort_by') ?? 'rating';
        $sort_by =  'rating';

        $results = [];


        // if ($query) {
        $apiKey = $this->apiKey;
        // $response = Http::get('https://maps.googleapis.com/maps/api/place/textsearch/json', [
        //     'query' => 'motels in Seymour ',
        //     'type' => 'lodging',
        //     'keyword' => 'motel',
        //     'key' => $apiKey,
        // ]);

        // $motels = collect($response['results'])->filter(function ($place) {
        //     return str_contains(strtolower($place['name']), 'motel');
        // });
        // dd($motels);

        $response = Http::get('https://maps.googleapis.com/maps/api/place/nearbysearch/json', [
            'query' => 'Motels in ' . $query,
            'type' => 'lodging',
            'keyword' => 'motel',
            'location' => '-37.0266,145.1290',
            'radius' => 8000,
            'key' => $apiKey,
        ]);

        $data = $response->json();


        if (!empty($data['results'])) {
            if ($sort_by == 'rating') {
                $topResults = collect($data['results'])->sortByDesc($sort_by)->take($perPage);
            } else {
                $topResults = collect($data['results'])->sortBy($sort_by)->take($perPage);
            }

            foreach ($topResults as $place) {

                $hotelName = $place['name'] ?? 'the motel';
                // $address = $place['formatted_address'] ?? '';
                $rating = $place['rating'] ?? 'N/A';
                $status = $place['business_status'] ?? 'N/A';
                $ratingCount = $place['user_ratings_total'] ?? 'N/A';
                $placeId = $place['place_id'] ?? null;

                // $hotelKey = strtolower($hotelName);


                // 🌐 Fetch website (optional)
                $website = 'Not available';
                if ($placeId) {
                    $details = Http::get('https://maps.googleapis.com/maps/api/place/details/json', [
                        'place_id' => $placeId,
                        'key' => $apiKey,
                    ])->json();


                    if (isset($details['result']['formatted_address'])) {
                        $address = $details['result']['formatted_address'];
                    }
                    if (isset($details['result']['rating'])) {
                        $rating = $details['result']['rating'];
                    }
                    if (isset($details['result']['user_ratings_total'])) {
                        $user_ratings_total = $details['result']['user_ratings_total'];
                    }

                    if (isset($details['result']['website'])) {
                        $website = $details['result']['website'];
                    }

                    if (isset($details['result']['wheelchair_accessible_entrance'])) {
                        $accessible = $details['result']['wheelchair_accessible_entrance'] ?? '';
                    } else {
                        $accessible = 0;
                    }
                    if (isset($details['result']['international_phone_number'])) {
                        $phone = $details['result']['international_phone_number'];
                    }
                }

                // 🤖 Gemini Prompts
                $pricePrompt = "Provide only the current approximate nightly rate in AUD for a deluxe room at {$hotelName} in {$query}
                                Respond with a numeric value only, e.g., 245. Do not include any explanation or text.";

                $distancePrompt = "How far is {$hotelName} in {$query} from the Seymour Health? Answer in numbers only.
                                        Do not explain, don't say Approximately";

                $price = trim($this->generate($pricePrompt));
                $distance = trim($this->generate($distancePrompt));
                // dd($distance);

                // 🧾 Add to results
                $results[] = [
                    'name' => $hotelName,
                    'address' => $address,
                    'rating' => $rating,
                    'status' => $status,
                    'rating_count' => $ratingCount,
                    'generated_price' => $price,
                    'distance_from_airport' => $distance,
                    'weblink' => $website,
                    'place_id' => $placeId,
                    'phone' => $phone,
                    'user_ratings_total' => $user_ratings_total,
                    'accessible' => $accessible,
                    'distance' => $distance
                ];
            }
        }
        // }


        foreach ($results as $data) {
            if (Motel::where('name', $data['name'])->exists()) {
                continue; // skip to next one
            }
            $motel = Motel::create([
                'name' => $data['name'],
                'address' => $data['address'],
                'website' => $data['weblink'] ?? '',
                'price' => $data['generated_price'] ?? '',
                'google_place_id' => $data['place_id'] ?? '',
                'accessible' => $data['accessible'] ?? 0,
                'phone' => $data['phone'] ?? '',
                'distance' => $data['distance'] ?? ''

            ]);

            $motel->ranking()->create([
                'motel_id' => $motel->id,
                'rating' => $data['rating'] ?? '',
                'user_total_rating' => $data['user_ratings_total'] ?? '',
            ]);
            $this->calculateScore();
        }
    }

    public function generate(string $prompt): ?string
    {
        $response = Http::withHeaders([
            'Content-Type' => 'application/json',
        ])->post($this->url, [
            'contents' => [
                [
                    'parts' => [
                        ['text' => $prompt],
                    ],
                ],
            ],
        ]);


        if ($response->failed()) {
            throw new \Exception('Gemini API Error: ' . $response->body());
        }
        $error = "Sorry we could not load your data this time, Please try again in few minute.";
        return $response->json()['candidates'][0]['content']['parts'][0]['text'] ?? $error;
    }

    public function calculateScore()
    {
        Motel::with('ranking')->get()->map(function ($motel) {
            $ranking = $motel->ranking;

            if (!$ranking) {
                return $motel; // skip if ranking doesn't exist
            }

            // Normalize and calculate score
            $ratingScore = ($ranking->rating ?? 0) / 5;
            $reviewScore = ($ranking->user_total_rating ?? 0) / 5;
            $websiteScore = $motel->website ? 1 : 0;
            $accessibleScore = $motel->accessible ? 1 : 0;
            $priceScore = 1 - min($motel->price / 200, 1); // adjust as needed
            $distanceScore = 1 - min(($motel->distance ?? 0) / 5, 1);

            $score = (
                ($ratingScore * 0.30) +
                ($reviewScore * 0.25) +
                ($websiteScore * 0.10) +
                ($accessibleScore * 0.15) +
                ($priceScore * 0.15) +
                ($distanceScore * 0.5)
            );

            // Store score in ranking table
            $ranking->score = $score;
            $ranking->save();

            //ge the score of motel by highestscore
            $rankings = Ranking::orderByDesc('score')->get();

            $rank = 1;
            foreach ($rankings as $ranking) {
                $ranking->rank = $rank++;
                $ranking->save();
            }

            // Attach score for further use if needed

            $motel->score = $score;
        });
    }


    public function showMotelScores()
    {

        // Join Motel and Ranking tables
        $motels = Motel::with('ranking')->get();

        $scores = $motels->map(function ($motel) {
            return [
                'name' => $motel->name,
                'score' => round($motel->ranking->score, 2),
            ];
        });

        return view('chart', ['scores' => $scores]);
    }



    /**
     * Display the user's profile form.
     */
    function haversine_distance($lat1, $lng1, $lat2, $lng2)
    {
        $earthRadius = 6371;  // Earth radius in kilometers

        // Convert degrees to radians
        $lat1 = deg2rad($lat1);
        $lng1 = deg2rad($lng1);
        $lat2 = deg2rad($lat2);
        $lng2 = deg2rad($lng2);

        // Haversine formula
        $dlat = $lat2 - $lat1;
        $dlng = $lng2 - $lng1;

        $a = sin($dlat / 2) * sin($dlat / 2) +
            cos($lat1) * cos($lat2) *
            sin($dlng / 2) * sin($dlng / 2);

        $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

        // Distance in kilometers
        return $earthRadius * $c;
    }
    public function getMotels(Request $request)
    {
        // Get coordinates for Hornsby Station (manually, you can get it from Google Maps)
        $hornsbyStation = [
            'lat' => -33.7017,  // Hornsby Station latitude
            'lng' => 151.1065,  // Hornsby Station longitude
        ];

        // Haversine formula to calculate the distance between two coordinates


        $suburb = $request->get('suburb', 'Hornsby');
        $radius = $request->get('radius', 5000);

        // Get the list of motels from the Places API (as per your code)
        $url = 'https://maps.googleapis.com/maps/api/place/textsearch/json';
        $response = $this->client->get($url, [
            'query' => [
                'query' => 'motels in Hornsby',
                'key' => $this->apiKey,
            ],
        ]);

        $placesData = json_decode($response->getBody(), true);
        dd($placesData);
        // Check if motels are found
        if (!isset($placesData['results']) || empty($placesData['results'])) {
            return response()->json(['error' => 'No motels found'], 400);
        }

        // Transform the response to extract only the required fields
        $transformedMotels = [];

        foreach ($placesData['results'] as $motel) {
            // Extract place_id for the current motel
            $placeId = $motel['place_id'];

            // Make a call to the Place Details API to get additional details (including price level)
            $placeDetailsUrl = 'https://maps.googleapis.com/maps/api/place/details/json';
            $placeDetailsResponse = $this->client->get($placeDetailsUrl, [
                'query' => [
                    'placeid' => $placeId,
                    'key' => $this->apiKey,
                ],
            ]);

            // Decode the response from the Place Details API
            $placeDetailsData = json_decode($placeDetailsResponse->getBody(), true);

            // Check if price level is available, if not set to null
            $priceLevel = isset($placeDetailsData['result']['price_level']) ? $placeDetailsData['result']['price_level'] : null;

            // Get the latitude and longitude of the motel
            $motelLat = $motel['geometry']['location']['lat'];
            $motelLng = $motel['geometry']['location']['lng'];

            // Calculate the distance from Hornsby Station
            $distance = $this->haversine_distance($hornsbyStation['lat'], $hornsbyStation['lng'], $motelLat, $motelLng);

            // Now transform and store the required fields including price level and distance
            $transformedMotels[] = [
                'name' => $motel['name'],  // Motel name
                'place_id' => $placeId,  // Place ID
                'rating' => $motel['rating'] ?? null,  // Rating (could be null)
                'reviews_count' => $motel['user_ratings_total'] ?? 0,  // Default to 0 if no reviews
                'latitude' => $motelLat,  // Latitude
                'longitude' => $motelLng,  // Longitude
                'types' => implode(',', $motel['types']),  // Types as a comma-separated string (e.g., 'lodging,hotel')
                'price_level' => $priceLevel,  // Price level (0-4, or null if not available)
                'distance_from_hornsby_station' => $distance,  // Distance from Hornsby Station in kilometers
            ];
        }

        // Output the transformed data
        dd($transformedMotels);
    }

    public function searchAllMotels(Request $request)
    {
        $suburb = $request->get('suburb', 'Hornsby');
        $radius = $request->get('radius', 5000);

        $url = 'https://maps.googleapis.com/maps/api/place/textsearch/json';

        $response = $this->client->get($url, [
            'query' => [
                'query' => 'motels in Brisbane',
                'key' => $this->apiKey,
            ],
        ]);
        $placesData = json_decode($response->getBody(), true);


        // Check if motels are found
        if (!isset($placesData['results']) || empty($placesData['results'])) {
            return response()->json(['error' => 'No motels found'], 400);
        }

        // Transform the response to extract only the required fields
        $transformedMotels = [];

        foreach ($placesData['results'] as $motel) {
            // Extract place_id for the current motel
            $placeId = $motel['place_id'];

            // Make a call to the Place Details API to get additional details (including price level)
            $placeDetailsUrl = 'https://maps.googleapis.com/maps/api/place/details/json';
            $placeDetailsResponse = $this->client->get($placeDetailsUrl, [
                'query' => [
                    'placeid' => $placeId,
                    'key' => $this->apiKey,
                    'limit' => 2
                ],
            ]);

            // Decode the response from the Place Details API
            $placeDetailsData = json_decode($placeDetailsResponse->getBody(), true);
            dd($placeDetailsData);

            // Check if price level is available, if not set to null
            $priceLevel = isset($placeDetailsData['result']['price_level']) ? $placeDetailsData['result']['price_level'] : null;

            // Now transform and store the required fields including price level
            $transformedMotels[] = [
                'name' => $motel['name'],  // Motel name
                'place_id' => $placeId,  // Place ID
                'rating' => $motel['rating'] ?? null,  // Rating (could be null)
                'reviews_count' => $motel['user_ratings_total'] ?? 0,  // Default to 0 if no reviews
                'latitude' => $motel['geometry']['location']['lat'],  // Latitude
                'longitude' => $motel['geometry']['location']['lng'],  // Longitude
                'types' => implode(',', $motel['types']),  // Types as a comma-separated string (e.g., 'lodging,hotel')
                'price_level' => $priceLevel,  // Price level (0-4, or null if not available)
            ];
        }

        // Output the transformed data (for review or further processing)
        dd($transformedMotels);
    }
}
