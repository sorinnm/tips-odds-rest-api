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
    const EXPORT_TYPE_POST = 'post';
    const EXPORT_TYPE_PAGE = 'page';

    const WORDPRESS_POST_ENDPOINT = '/wp-json/wp/v2/posts';
    const WORDPRESS_PAGE_ENDPOINT = '/wp-json/wp/v2/pages';

    public function __construct(
        protected TextGenerator $generatorModel)
    {}

    /**
     * @throws \Exception
     */
    public function export(Fixtures $fixture, $exportType = self::EXPORT_TYPE_PAGE)
    {
        $matchDate = $homeTeam = $awayTeam = '';
        $generation = $this->generatorModel->getGeneration($fixture->fixture_id);

        if (empty($generation)) {
            throw new \Exception("#{$fixture->fixture_id} | $homeTeam - $awayTeam: No generation found");
        }

        $generation = Json::decode($generation, true);
        $fixtureData = json_decode($fixture->fixtures, true);

        if (!empty($fixtureData)) {
            $matchDate = date('F j Y', $fixtureData[0]['fixture']['timestamp']);
            $homeTeam = $fixtureData[0]['teams']['home']['name'];
            $awayTeam = $fixtureData[0]['teams']['away']['name'];
            $generation['matchDate'] = $matchDate;
        }

        $html = $this->generateHtml($generation, $exportType);

        $league = Leagues::all()->where('api_football_id', $fixture->league_id)->first();
        $authorId = $league->country->author_id;

        $homeLogo = $fixture->home_logo;
        $awayLogo = $fixture->away_logo;

        $matchTitleField = '<h2 class="match-title-h1"><img src="'.$homeLogo.'" class="match-title-logo"/> <span class="match-title-home">'.$homeTeam.'</span> - <span class="match-title-away">'.$awayTeam.'</span> <img src="'.$awayLogo.'" class="match-title-logo"/><span class="match-title-date">'.$matchDate.'</span></h2>';

        $payload = [
            'title' => "$homeTeam vs $awayTeam $matchDate Tips & Predictions",
            'content' => $html,
            'status' => 'draft',
            'author' => $authorId,
            'template'=> 'matchpage',
            'acf' => [
                'match_title' => $matchTitleField
            ]
        ];

        if (self::EXPORT_TYPE_POST === $exportType) {
            $payload['categories'] = [
                $league->category_id,
                $league->country->category_id,
                $league->country->sport->category_id
            ];
        } elseif (self::EXPORT_TYPE_PAGE) {
            $payload['parent'] = $league->page_id;
        }

        switch ($exportType) {
            case self::EXPORT_TYPE_POST:
                $result = $this->callAPI(
                    Request::METHOD_POST,
                    env('WORDPRESS_HOST') . self::WORDPRESS_POST_ENDPOINT,
                    $payload
                );
                $result['entity_type'] = self::EXPORT_TYPE_POST;
                break;
            case self::EXPORT_TYPE_PAGE:
                $result = $this->callAPI(
                    Request::METHOD_POST,
                    env('WORDPRESS_HOST') . self::WORDPRESS_PAGE_ENDPOINT,
                    $payload
                );
                $result['entity_type'] = self::EXPORT_TYPE_PAGE;
                break;
            default:
                $result = [];
        }

        return $result;
    }

    /**
     * @param array $generation
     * @param string $exportType
     * @return string
     */
    public function generateHtml(array $generation, string $exportType): string
    {
        switch ($exportType) {
            case self::EXPORT_TYPE_POST:
                $html = view('wordpress_post', $generation)->render();
                break;
            case self::EXPORT_TYPE_PAGE:
                $html = view('wordpress_page', $generation)->render();
                break;
            default:
                $html = '';
        }

        return $html;
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
