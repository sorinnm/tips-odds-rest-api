<?php

namespace App\Service;

use App\Models\Countries;
use App\Models\Fixtures;
use App\Models\Leagues;
use App\Models\Rounds;
use App\Models\Seasons;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Request;

class ApiFootballService
{
    const API_ENDPOINT_FIXTURES = '/fixtures';
    const API_ENDPOINT_STANDINGS = '/standings';
    const API_ENDPOINT_SQUADS = '/players/squads';
    const API_ENDPOINT_INJURIES = '/injuries';
    const API_ENDPOINT_PREDICTIONS = '/predictions';
    const API_ENDPOINT_BETS = '/odds';
    const API_ENDPOINT_ROUND = '/fixtures/rounds';

    const DATA_TYPE_FIXTURES = 'fixtures';
    const DATA_TYPE_HOME_TEAM_SQUAD = 'home_team_squad';
    const DATA_TYPE_AWAY_TEAM_SQUAD = 'away_team_squad';
    const DATA_TYPE_STANDINGS = 'standings';
    const DATA_TYPE_PREDICTIONS = 'predictions';
    const DATA_TYPE_INJURIES = 'injuries';
    const DATA_TYPE_BETS = 'bets';
    const DATA_TYPE_HEAD_TO_HEAD = 'head_to_head';

    protected array $head2head = [];

    public int $leagueId = 0;
    public int $seasonId = 0;
    public string $round = '';

    public function __construct(
        protected Countries $countries,
        protected Seasons $seasons,
        protected Leagues $leagues,
        protected Fixtures $fixtures
    ){}

    /**
     * Initialize constant variables
     *
     * @return void
     */
    public function init(): void
    {
        if (empty($this->leagueId) || empty($this->seasonId) || empty($this->round)) {
            $season = Seasons::all()->firstWhere('is_active', 1);

            // Get current round from API (we can use DB too, but let's test using API)
            //$round = Rounds::all()->firstWhere('season_id', $season->id);
            $this->leagueId = $season->league->api_football_id;
            $this->seasonId = $season->name;
            $this->round = $this->getCurrentRound()[0];
        }
    }

    /**
     * @return false|mixed|null
     */
    public function getFixtureLeague(): mixed
    {
        return $this->callAPI(
            Request::METHOD_GET,
            env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_FIXTURES,
            [
                'league' => $this->leagueId,
                'season' => $this->seasonId,
                'round' => $this->round,
            ]
        );
    }

    /**
     * @param int $fixtureId
     * @return mixed|void
     */
    public function getFixtureMatch(int $fixtureId)
    {
        return $this->callAPI(
            Request::METHOD_GET,
            env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_FIXTURES,
            [
                'id' => $fixtureId
            ]
        );
    }

    /**
     * @return false|mixed|null
     */
    public function getStandings(): mixed
    {
        $season = Seasons::all()->firstWhere('is_active', 1);

        return $this->callAPI(
            Request::METHOD_GET,
            env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_STANDINGS,
            [
                'league' => $this->leagueId,
                'season' => $this->seasonId
            ]);
    }

    /**
     * @param int $teamId
     * @return mixed|void
     */
    public function getSquads(int $teamId)
    {
        return $this->callAPI(
            Request::METHOD_GET,
            env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_SQUADS,
            [
                'team' => $teamId
            ]
        );
    }

    /**
     * @param int $fixtureId
     * @return false|mixed|void
     */
    public function getInjuries(int $fixtureId)
    {
        $season = Seasons::all()->firstWhere('is_active', 1);

        return $this->callAPI(
            Request::METHOD_GET,
            env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_INJURIES,
            [
                'league' => $this->leagueId,
                'season' => $this->seasonId,
                'fixture' => $fixtureId
            ]
        );
    }

    /**
     * @param int $fixtureId
     * @return mixed|void
     */
    public function getPredictions(int $fixtureId)
    {
        $predictions = $this->callAPI(
            Request::METHOD_GET,
            env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_PREDICTIONS,
            [
                'fixture' => $fixtureId,
            ]
        );

        if (!empty($predictions)) {
            $this->head2head = [
                'fixtureId' => $fixtureId,
                'h2h' => $predictions[0]['h2h']
            ];
        }

        return $predictions;
    }

    /**
     * @param int $fixtureId
     * @return false|mixed
     */
    public function getHeadToHead(int $fixtureId): mixed
    {
        $head2head = false;
        if (!empty($this->head2head) && isset($this->head2head['fixtureId']) && isset($this->head2head['h2h'])) {
            if ($this->head2head['fixtureId'] == $fixtureId) {
                $head2head = $this->head2head['h2h'];
            }
        }

        return $head2head;
    }

    /**
     * @param int $fixtureId
     * @return mixed|void
     */
    public function getBets(int $fixtureId)
    {
        return $this->callAPI(
            Request::METHOD_GET,
            env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_BETS,
            [
                'bookmaker' => env('BOOKMAKER_ID'),
                'fixture' => $fixtureId
            ]
        );
    }

    /**
     * @return false|mixed|null
     */
    public function getCurrentRound(): mixed
    {
        return $this->callAPI(
            Request::METHOD_GET,
            env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_ROUND,
            [
                'league' => $this->leagueId,
                'season' => $this->seasonId,
                'current' => 'true'
            ]
        );
    }

    /**
     * @param array $jsonArray
     * @param string $type
     * @return array
     */
    public function cleanData(array $jsonArray, string $type): array
    {
        switch ($type) {
            case self::DATA_TYPE_FIXTURES:
                $keysToRemove = ['id', 'flag', 'logo', 'comments', 'players'];
                $result = $this->removeKeys($jsonArray, $keysToRemove);
                break;
            case self::DATA_TYPE_HOME_TEAM_SQUAD|self::DATA_TYPE_AWAY_TEAM_SQUAD:
                $keysToRemove = ['id', 'logo', 'photo'];
                $result = $this->removeKeys($jsonArray, $keysToRemove);
                break;
            case self::DATA_TYPE_INJURIES:
                $keysToRemove = ['id', 'logo', 'flag', 'photo'];
                $result = $this->removeKeys($jsonArray, $keysToRemove);
                break;
            case self::DATA_TYPE_PREDICTIONS|self::DATA_TYPE_STANDINGS|self::DATA_TYPE_HEAD_TO_HEAD:
                $keysToRemove = ['id', 'logo', 'flag'];
                $result = $this->removeKeys($jsonArray, $keysToRemove);
                break;
            case self::DATA_TYPE_BETS:
                $keysToRemove = ['id'];
                $result = $this->removeKeys($jsonArray, $keysToRemove);
                break;
            default:
                $result = $jsonArray;
        }

        return $result;
    }

    /**
     * Recursive function to remove specific keys from an array
     *
     * @param array $array
     * @param array $keysToRemove
     * @return array
     */
    private function removeKeys(array $array, array $keysToRemove): array {
        foreach ($array as $key => &$value) {
            // If the current key is in the list of keys to remove, unset it
            if (in_array($key, $keysToRemove, true)) {
                unset($array[$key]);
            } elseif (is_array($value)) {
                // If the value is an array, recurse
                $value = $this->removeKeys($value, $keysToRemove);
            }
        }

        return $array;
    }

    /**
     * @param $httpMethod
     * @param string $endpoint
     * @param array $payload
     * @return false|mixed|void
     */
    public function callAPI($httpMethod, string $endpoint, array $payload)
    {
        $result = false;
        // env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_FIXTURES

        try {
            $response = Http::withHeaders([
                'x-apisports-key' => env('X_RAPIDAPI_KEY'),
                'Accept' => 'application/json'
            ])->$httpMethod($endpoint, $payload);

            Log::debug($endpoint. " >>> Status code: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data['response'])) {
                    $result = $data['response'];
                }
            }

            return $result;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }
}
