<?php

namespace App\Services;

use App\Events\FixtureStatusUpdate;
use App\Models\Fixtures;
use App\Models\Leagues;
use App\Models\TextGenerator;
use Exception;
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
     * @param Fixtures $fixture
     * @return array
     * @throws Exception
     */
    public function processGeneration(Fixtures $fixture): array
    {
        $postData = [];
        $generation = TextGenerator::all()
            ->where('fixture_id', $fixture->fixture_id)
            ->where('status', TextGenerator::STATUS_PENDING)
            ->first();
        if (empty($generation)) {
            Log::channel('wordpress')->warning($fixture->fixture_id . ' could not found any generation for export');
            throw new Exception(' could not found any generation for export');
        }

        $response = $this->export($fixture);

        $fixtureData = json_decode($fixture->fixtures, true);
        if (empty($fixtureData)) {
            throw new Exception(' missing fixture data');
        }
        $homeTeam = $fixtureData[0]['teams']['home']['name'];
        $awayTeam = $fixtureData[0]['teams']['away']['name'];

        Log::channel('wordpress')->debug("#$fixture->fixture_id | $homeTeam - $awayTeam: WordPress response: " . JSON::encode($response));

        // set generation to status=complete if OK
        if (!empty($response) && isset($response['id']) && isset($response['link'])) {
            $saveData = [
                'fixture_id' => $fixture->fixture_id,
                'url' => $response['link'],
                'status' => TextGenerator::STATUS_COMPLETE
            ];

            if ($response['entity_type'] == self::EXPORT_TYPE_PAGE) {
                $saveData['page_id'] = $response['id'];
            } elseif ($response['entity_type'] == self::EXPORT_TYPE_POST) {
                $saveData['post_id'] = $response['id'];
            }

            $this->generatorModel->store($saveData);

            $postData['response'] = $response;
            $postData['report']['ID'] = $fixture->fixture_id;
            $postData['report']['Match'] = "$homeTeam - $awayTeam";
            $postData['report']['Updated At'] = date('Y-m-d H:i:s');
            $postData['report']['WordPress'] = "{$response['id']} - {$response['link']}";

            FixtureStatusUpdate::dispatch($fixture, 'FixturePublished', 12);
        }

        return $postData;
    }

    /**
     * @param Fixtures $fixture
     * @param string $exportType
     * @return array
     * @throws Exception
     */
    protected function export(Fixtures $fixture, string $exportType = self::EXPORT_TYPE_PAGE): array
    {
        $matchDate = $homeTeam = $awayTeam = '';
        $generation = $this->generatorModel->getGeneration($fixture->fixture_id);

        if (empty($generation)) {
            throw new Exception("#$fixture->fixture_id | $homeTeam - $awayTeam: No generation found");
        }

        $generation = Json::decode($generation);
        $fixtureData = json_decode($fixture->fixtures, true);

        if (!empty($fixtureData)) {
            $matchDate = date('F j Y', $fixtureData[0]['fixture']['timestamp']);
            $homeTeam = $fixtureData[0]['teams']['home']['name'];
            $awayTeam = $fixtureData[0]['teams']['away']['name'];
            $generation['matchDate'] = $matchDate;
        }

        $html = $this->generateHtml($generation, $exportType);
        $authorId = $fixture->league->country->author_id;

        $homeLogo = $fixture->home_logo;
        $awayLogo = $fixture->away_logo;

        $matchTitleField = '<h2 class="match-title-h1"><img alt="home-team-logo" src="'.$homeLogo.'" class="match-title-logo"/> <span class="match-title-home">'.$homeTeam.'</span> - <span class="match-title-away">'.$awayTeam.'</span> <img alt="away-team-logo" src="'.$awayLogo.'" class="match-title-logo"/><span class="match-title-date">'.$matchDate.'</span></h2>';

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
                $fixture->league->category_id,
                $fixture->league->country->category_id,
                $fixture->league->country->sport->category_id
            ];
        } elseif (self::EXPORT_TYPE_PAGE) {
            $payload['parent'] = $fixture->league->page_id;
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
        return match ($exportType) {
            self::EXPORT_TYPE_POST => view('wordpress_post', $generation)->render(),
            self::EXPORT_TYPE_PAGE => view('wordpress_page', $generation)->render(),
            default => '',
        };
    }

    /**
     * @param $httpMethod
     * @param string $endpoint
     * @param array $payload
     * @return array
     * @throws Exception
     */
    public function callAPI($httpMethod, string $endpoint, array $payload): array
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
                throw new Exception("Invalid Wordpress response: " . $response->status() . " >>> Message: " . $response->body());
            }
        } else {
            throw new Exception("Wordpress API: " . $response->status() . " >>> Message: " . $response->body());
        }

        return $result;

    }
}
