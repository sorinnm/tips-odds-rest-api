<?php

namespace App\Services;

use App\Models\Fixtures;
use App\Models\Leagues;
use App\Models\Sports;
use App\Models\TextGenerator;
use Illuminate\Database\Eloquent\Casts\Json;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

class WordpressService
{
    const WORDPRESS_POST_ENDPOINT = '/wp-json/wp/v2/posts';
    public function __construct(
        protected TextGenerator $generatorModel)
    {}

    /**
     * @throws \Exception
     */
    public function exportPost(Fixtures $fixture)
    {
        $matchDate = $homeTeam = $awayTeam = '';
        $generation = $this->generatorModel->getGeneration($fixture->fixture_id);

        if (empty($generation)) {
            throw new \Exception("#{$fixture->fixture_id} | $homeTeam - $awayTeam: No generation found");
        }

        $generation = Json::decode($generation);
        $fixtureData = json_decode($fixture->fixtures, true);

        if (!empty($fixtureData)) {
            $matchDate = date('F j Y', $fixtureData[0]['fixture']['timestamp']);
            $homeTeam = $fixtureData[0]['teams']['home']['name'];
            $awayTeam = $fixtureData[0]['teams']['away']['name'];
            $generation['matchDate'] = $matchDate;
        }

        $html = view('wordpress_post', $generation)->render();

        $league = Leagues::all()->where('api_football_id', $fixture->league_id)->first();
        $authorId = $league->country->author_id;
        $leagueCategoryId = $league->category_id;
        $countryCategoryId = $league->country->category_id;
        $sportCategoryId = $league->country->sport->category_id;
        $sportPath = $league->country->sport->category_path;
        $countryPath = $league->country->category_path;
        $leaguePath = $league->category_path;

        $payload = [
            'title' => "$homeTeam vs $awayTeam $matchDate Tips & Predictions",
            'content' => $html,
            'status' => 'draft',
            'author' => $authorId,
            'categories' => [$leagueCategoryId, $countryCategoryId, $sportCategoryId]
        ];

        return $this->callAPI(
            Request::METHOD_POST,
            env('WORDPRESS_HOST') . self::WORDPRESS_POST_ENDPOINT,
            $payload
        );
    }

    /**
     * @param $httpMethod
     * @param string $endpoint
     * @param array $payload
     * @return false|mixed|void
     * @throws \Exception
     */
    public function callAPI($httpMethod, string $endpoint, array $payload)
    {
        $response = Http::withBasicAuth(env('WORDPRESS_USER'), env('WORDPRESS_PASSWORD'))
            ->$httpMethod($endpoint, $payload);

        Log::channel('wordpress')->debug($endpoint. " >>> Status code: " . $response->status() . " >>> Response: " . Json::encode($response->json()));

        if ($response->successful()) {
            $data = $response->json();

            if (!empty($data) && isset($data['id']) && isset($data['link'])) {
                $result = [
                    'id' => $data['id'],
                    'link' => $data['link']
                ];
            } else {
                throw new \Exception("Invalid Wordpress response: " . $response->status() . " >>> Message: " . $response->body());
            }
        } else {
            throw new \Exception("Wordpress API: " . $response->status() . " >>> Message: " . $response->body());
        }

        return $result;

    }
}
