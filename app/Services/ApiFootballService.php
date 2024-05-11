<?php

namespace App\Services;

use App\Models\Countries;
use App\Models\Fixtures;
use App\Models\Leagues;
use App\Models\Rounds;
use App\Models\Seasons;
use App\Models\Standings;
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
        protected Fixtures $fixtures,
        protected Standings $standings
    ){}

    /**
     *  Initialize constant variables
     *
     * @param $leagueId
     * @param $seasonId
     * @param $round
     * @return void
     */
    public function init($leagueId, $seasonId): void
    {
        $this->leagueId = $leagueId;
        $this->seasonId = $seasonId;
        $this->round = $this->getCurrentRound($leagueId, $seasonId)[0];
    }

    /**
     * Main function to store fixtures data
     *
     * @param int $leagueId
     * @param int $seasonId
     * @param string $round
     * @return array
     */
    public function importFixtures(int $leagueId, int $seasonId, string $round): array
    {
        $result = [];
        $this->init($leagueId, $seasonId, $round);

        $fixturesLeague = (array) $this->getFixtureLeague();

        $reportData = [
            'ID' => null,
            'Match' => null,
            'Updated At' => null,
            'API-Football' => null
        ];

        $fixtureData = [
            'fixture' => false,
            'home_team_squad' => false,
            'away_team_squad' => false,
            'injuries' => false,
            'predictions' => false,
            'head_to_head' => false,
            'bets' => false
        ];

        // Get standings and save
        $standings = $this->cleanData($this->getStandings(), self::DATA_TYPE_STANDINGS);
        $this->standings->store([
            'league_id' => $this->leagueId,
            'season_id' => $this->seasonId,
            'round'     => $this->round,
            'standings' => json_encode($standings)
        ]);

        foreach ($fixturesLeague as $fixture) {
            $fixtureId = $fixture['fixture']['id'];
            $homeTeamId = $fixture['teams']['home']['id'];
            $awayTeamId = $fixture['teams']['away']['id'];

            // Get fixtures and save
            $saved = false;
            $fixtureMatch = $this->cleanData($this->getFixtureMatch($fixtureId), self::DATA_TYPE_FIXTURES);
            if (!empty($homeTeamSquad)) {
                $saved = $this->fixtures->store([
                    'fixture_id' => $fixtureId,
                    'league_id' => $this->leagueId,
                    'season_id' => $this->seasonId,
                    'round' => $this->round,
                    'fixtures' => json_encode($fixtureMatch)
                ]);
            }

            if ($saved) {
                $reportData['ID'] = $fixtureId;
                $reportData['Match'] = "{$fixture['teams']['home']['name']} - {$fixture['teams']['away']['name']}";
                $reportData['Updated At'] = date('Y-m-d H:i:s');
                $fixtureData['id'] = $fixtureId;
                $fixtureData['fixture'] = true;
            }

            // Get home team squad and save
            $saved = false;
            $homeTeamSquad = $this->cleanData($this->getSquads($homeTeamId), self::DATA_TYPE_HOME_TEAM_SQUAD);
            if (!empty($homeTeamSquad)) {
                $saved = $this->fixtures->store([
                    'fixture_id' => $fixtureId,
                    'home_team_squad' => json_encode($homeTeamSquad)
                ]);
            }

            if ($saved) {
                $fixtureData['home_team_squad'] = true;
                $reportData['Updated At'] = date('Y-m-d H:i:s');
            }

            // Get away team squad and save
            $saved = false;
            $awayTeamSquads = $this->cleanData($this->getSquads($awayTeamId), self::DATA_TYPE_AWAY_TEAM_SQUAD);
            if (!empty($awayTeamSquads)) {
                $saved = $this->fixtures->store([
                    'fixture_id' => $fixtureId,
                    'away_team_squad' => json_encode($awayTeamSquads)
                ]);
            }

            if ($saved) {
                $fixtureData['away_team_squad'] = true;
                $reportData['Updated At'] = date('Y-m-d H:i:s');
            }

            // Get injuries and save
            $saved = false;
            $injuries = $this->cleanData($this->getInjuries($fixtureId), self::DATA_TYPE_INJURIES);
            if (!empty($injuries)) {
                $saved = $this->fixtures->store([
                    'fixture_id' => $fixtureId,
                    'injuries' => json_encode($injuries)
                ]);
            }

            if ($saved) {
                $fixtureData['injuries'] = true;
                $reportData['Updated At'] = date('Y-m-d H:i:s');
            }

            // Get predictions and save
            $saved = false;
            $predictions = $this->cleanData($this->getPredictions($fixtureId), self::DATA_TYPE_PREDICTIONS);
            if (!empty($predictions)) {
                $saved = $this->fixtures->store([
                    'fixture_id' => $fixtureId,
                    'predictions' => json_encode($predictions)
                ]);
            }

            if ($saved) {
                $fixtureData['predictions'] = true;
                $reportData['Updated At'] = date('Y-m-d H:i:s');
            }

            // Get head to head and save
            $saved = false;
            $head2head = $this->cleanData($this->getHeadToHead($fixtureId), self::DATA_TYPE_HEAD_TO_HEAD);
            if (!empty($head2head)) {
                $saved = $this->fixtures->store([
                    'fixture_id' => $fixtureId,
                    'head_to_head' => json_encode($head2head)
                ]);
            }

            if ($saved) {
                $fixtureData['head_to_head'] = true;
                $reportData['Updated At'] = date('Y-m-d H:i:s');
            }

            // Get bets and save
            $saved = false;
            $bets = $this->cleanData($this->getBets($fixtureId), self::DATA_TYPE_BETS);
            if (!empty($head2head)) {
                $saved = $this->fixtures->store([
                    'fixture_id' => $fixtureId,
                    'bets' => json_encode($bets)
                ]);
            }

            if ($saved) {
                $fixtureData['bets'] = true;
                $reportData['Updated At'] = date('Y-m-d H:i:s');
            }

            $apiFootballErrors = [];
            foreach ($fixtureData as $key => $val) {
                if ($val === false) {
                    $apiFootballErrors[] = $key;
                }
            }

            if (empty($apiFootballErrors)) {
                $reportData['API-Football'] = 'OK';
            } else {
                $reportData['API-Football'] = implode(',', $apiFootballErrors);
            }

            $result['report'][] = $reportData;
            $result['fixture'][] = $fixtureData;
        }

        return $result;
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
     * @param $leagueId
     * @param $seasonId
     * @return mixed
     */
    public function getCurrentRound($leagueId, $seasonId): mixed
    {
        return $this->callAPI(
            Request::METHOD_GET,
            env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_ROUND,
            [
                'league' => $leagueId,
                'season' => $seasonId,
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
