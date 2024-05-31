<?php
namespace App\Http\Controllers;

use App\Models\Domain;
use App\Models\Url;
use GuzzleHttp\Client;
use Illuminate\Http\Request;

class AIController extends Controller
{
    protected $iaResponseTemplate = [
        'demographic' => [
            'language_code' => 'xx',
        ],
        'interests' => ['xx', 'xx'],
        'purchase_interests' => ['xx', 'xx'],
        'professional_profile' => [
            'field_of_work' => ['xx'],
            'profession' => ['xxx'],
        ],
        "categories"=> [
            [
                "category"=> "xx",
                "details" => ['xx', 'xx'],
            ]
        ],
    ];

    protected $client;

    public function __construct()
    {
        $this->client = new Client();
    }

    public function categorize(Request $request)
    {

        $request->validate([
            'titles' => 'required|array',
        ]);

        $data = $this->mapUserProfile($request->titles);

        return response()->json([
            'profile' => json_decode($data, true) ?? $data,
        ]);
    }


    public function mapUserProfile(array $browserHistory)
    {
        $prompt = 'map the profile:
        '.json_encode($browserHistory);

        $command = 'You are an assistant that maps user profiles based on title browsing history. Return only this raw JSON format without formatting and with the values standardized, formatted as slugs in English, with detailed activities. Use the provided browsing history to infer the details. Ensure consistency across multiple calls. Example browsing history and output are provided below for reference.'.json_encode($this->iaResponseTemplate);

        $response = $this->client->post('https://api.openai.com/v1/chat/completions', [
            'headers' => [
                'Authorization' => 'Bearer ' . env('OPENAI_API_KEY'),
                'Content-Type'  => 'application/json',
            ],
            'json' => [
                'model' => 'gpt-4o',
                // 'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'system', 'content' => $command],
                    ['role' => 'user', 'content' => $prompt]
                ],
                'max_tokens' => 2000,
                'temperature' => 1,
            ],
        ]);

        $responseBody = json_decode($response->getBody(), true);
        return $responseBody['choices'][0]['message']['content'];
    }
}