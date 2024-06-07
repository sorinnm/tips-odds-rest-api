<?php

namespace App\Services;

use App\Models\Countries;
use App\Models\Fixtures;
use App\Models\Leagues;
use App\Models\Seasons;
use App\Models\Standings;
use Illuminate\Database\Eloquent\Casts\Json;
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
    const DATA_TYPE_SQUADS = 'home_team_squad';
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
     * @return void
     * @throws \Exception
     */
    public function init($leagueId, $seasonId): void
    {
        $this->leagueId = $leagueId;
        $this->seasonId = $seasonId;
        $currentRound = $this->getCurrentRound($leagueId, $seasonId);

        if (empty($currentRound)) {
            throw new \Exception('Round not found');
        }
        $this->round = $this->getCurrentRound($leagueId, $seasonId)[0];
    }

    /**
     *  Main function to store fixtures data
     *
     * @param int $leagueId
     * @param int $seasonId
     * @return array
     * @throws \Exception
     */
    public function importFixtures(int $leagueId, int $seasonId): array
    {
        $result = [];
        $this->init($leagueId, $seasonId);

        $fixturesLeague = (array) $this->getFixtureLeague();

        $reportData = [
            'ID' => null,
            'Match' => null,
            'Updated At' => null,
            'API-Football' => null
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
            $fixtureData = [
                'fixture' => false,
                'home_team_squad' => false,
                'away_team_squad' => false,
                'injuries' => false,
                'predictions' => false,
                'head_to_head' => false,
                'bets' => false
            ];

            $fixtureId = $fixture['fixture']['id'];
            $homeTeamId = $fixture['teams']['home']['id'];
            $awayTeamId = $fixture['teams']['away']['id'];

            // Get fixtures and save
            $saved = false;
            $fixtureMatch = $this->getFixtureMatch($fixtureId);
            if (!empty($fixtureMatch)) {
                $leagueId = Leagues::all()->where('api_football_id', $this->leagueId)->first()->id;
                $seasonId = Seasons::all()->where('league_id', $leagueId)->first()->id;
                $saved = $this->fixtures->store([
                    'fixture_id' => $fixtureId,
                    'league_id' => $leagueId,
                    'season_id' => $seasonId,
                    'round' => $this->round,
                    'fixtures' => json_encode($fixtureMatch),
                    'home_team_id' => $homeTeamId,
                    'away_team_id' => $awayTeamId,
                    'status' => Fixtures::STATUS_PENDING
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
            $homeTeamSquad = $this->getSquads($homeTeamId);
            $homeTeamLogo = $homeTeamSquad[0]['team']['logo'];
            $homeTeamSquad = $this->cleanData($homeTeamSquad, self::DATA_TYPE_SQUADS);
            if (!empty($homeTeamSquad)) {
                $saved = $this->fixtures->store([
                    'fixture_id' => $fixtureId,
                    'home_logo' => $homeTeamLogo,
                    'home_team_squad' => json_encode($homeTeamSquad)
                ]);
            }

            if ($saved) {
                $fixtureData['home_team_squad'] = true;
                $reportData['Updated At'] = date('Y-m-d H:i:s');
            }

            // Get away team squad and save
            $saved = false;
            $awayTeamSquad = $this->getSquads($awayTeamId);
            $awayTeamLogo = $awayTeamSquad[0]['team']['logo'];
            $awayTeamSquads = $this->cleanData($awayTeamSquad, self::DATA_TYPE_SQUADS);
            if (!empty($awayTeamSquads)) {
                $saved = $this->fixtures->store([
                    'fixture_id' => $fixtureId,
                    'away_logo' => $awayTeamLogo,
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
            if (!empty($bets)) {
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

    public function reimportFixture(Fixtures $fixture): array
    {
        $fixtureId = $fixture->fixture_id;
        $this->leagueId = $fixture->league_id;
        $this->seasonId = $fixture->season_id;

        $fixtureData = json_decode($fixture->fixtures, true);
        if (empty($fixtureData)) {
            throw new \Exception(' missing fixtures data');
        }

        $homeTeam = $fixtureData[0]['teams']['home']['name'];
        $awayTeam = $fixtureData[0]['teams']['away']['name'];

        $fixtureData = [
            'fixture' => false,
            'home_team_squad' => false,
            'away_team_squad' => false,
            'injuries' => false,
            'predictions' => false,
            'head_to_head' => false,
            'bets' => false
        ];

        // Get fixtures and save
        $saved = false;
        $fixtureMatch = $this->cleanData($this->getFixtureMatch($fixtureId), self::DATA_TYPE_FIXTURES);
        if (!empty($fixtureMatch)) {
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'fixtures' => json_encode($fixtureMatch)
            ]);
        }

        if ($saved) {
            $reportData['ID'] = $fixtureId;
            $reportData['Match'] = "$homeTeam - $awayTeam";
            $reportData['Updated At'] = date('Y-m-d H:i:s');
            $fixtureData['id'] = $fixtureId;
            $fixtureData['fixture'] = true;
        }

        // Get home team squad and save
        $saved = false;
        $homeTeamSquad = $this->getSquads($fixture->home_team_id);
        $homeTeamLogo = $homeTeamSquad[0]['team']['logo'];
        $homeTeamSquad = $this->cleanData($homeTeamSquad, self::DATA_TYPE_SQUADS);
        if (!empty($homeTeamSquad)) {
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'home_logo' => $homeTeamLogo,
                'home_team_squad' => json_encode($homeTeamSquad)
            ]);
        }

        if ($saved) {
            $fixtureData['home_team_squad'] = true;
            $reportData['Updated At'] = date('Y-m-d H:i:s');
        }

        // Get away team squad and save
        $saved = false;
        $awayTeamSquad = $this->getSquads($fixture->away_team_id);
        $awayTeamLogo = $awayTeamSquad[0]['team']['logo'];
        $awayTeamSquads = $this->cleanData($awayTeamSquad, self::DATA_TYPE_SQUADS);
        if (!empty($awayTeamSquads)) {
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'away_logo' => $awayTeamLogo,
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
        if (!empty($bets)) {
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

        return [
            'report' => $reportData,
            'fixtures' => $fixtureData
        ];
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
        $response = $this->callAPI(
            Request::METHOD_GET,
            env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_FIXTURES,
            [
                'id' => $fixtureId
            ]
        );

        return $this->cleanData($response, self::DATA_TYPE_FIXTURES);
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
            case self::DATA_TYPE_SQUADS:
                $keysToRemove = ['id', 'logo', 'photo'];
                $result = $this->removeKeys($jsonArray, $keysToRemove);
                break;
            case self::DATA_TYPE_INJURIES:
                $keysToRemove = ['id', 'logo', 'flag', 'photo'];
                $result = $this->removeKeys($jsonArray, $keysToRemove);
                break;
            case self::DATA_TYPE_PREDICTIONS || self::DATA_TYPE_STANDINGS || self::DATA_TYPE_HEAD_TO_HEAD:
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
    private function removeKeys(array $array, array $keysToRemove, array $keysToSkip = []): array
    {
        foreach ($array as $key => &$value) {
            // If the current key is in the list of keys to remove and not in the list of keys to skip, unset it
            if (in_array($key, $keysToRemove, true) && !in_array($key, $keysToSkip, true)) {
                unset($array[$key]);
            } elseif (is_array($value)) {
                // If the value is an array, recurse
                $value = $this->removeKeys($value, $keysToRemove, $keysToSkip);
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

            Log::channel('api-football')->debug($endpoint. " >>> Status code: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data['response'])) {
                    $result = $data['response'];
                }
            }

            return $result;
        } catch (\Throwable $th) {
            Log::channel('api-football')->error(__CLASS__ . ' >>> ' . $th->getMessage());
        }
    }
}
