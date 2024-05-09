<?php

namespace App\Http\Controllers;

use App\Service\ApiFootballService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use App\Models\Fixtures;
use App\Models\Standings;
use Illuminate\Support\Facades\DB;

class FixtureController extends Controller
{
    /**
     * @param Fixtures $fixtures
     * @param Standings $standings
     */
    public function __construct(
        protected ApiFootballService $apiFootballService,
        protected Fixtures $fixtures,
        protected Standings $standings
    ) {}

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $paginate = $request->get('paginate');

        if (empty($paginate)) {
            return response()->json("Please use a paginate parameter", 400);
        }
        $fixtures = Fixtures::paginate($paginate);
        return response()->json($fixtures);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function save(Request $request)
    {
        $saved = false;

        // Before creating a new fixture, try to find an existing one
        $fixtures = Fixtures::all();
        $fixture = $fixtures->firstWhere('fixture_id', $request->get('fixture_id'));

        if (empty($fixture)) {
            $fixture = new Fixtures;
        }

        $columns = $request->keys();
        foreach ($columns as $column) {
            $fixture->$column = json_encode($request->$column);
        }

        $saved = $fixture->save();
        $message = 'Fixture has been saved';

        if (!$saved) {
            $message = 'Unable to save Fixture';
        }

        return response()->json([
            'message' => $message
        ]);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $fixtures = Fixtures::all();
        return response()->json($fixtures->where('fixture_id', $id));
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function delete($id): JsonResponse
    {
        DB::table('fixtures')->where('fixture_id', '=', $id)->delete();
        return response()->json(null, 204);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function escape(Request $request): JsonResponse
    {
        $jsonData = $request->getContent();
        if (empty($jsonData) || !$request->isJson()) {
            $jsonData = ['error' => 'Unable to provide results'];
        }

        return response()->json(["data" => $jsonData]);
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function storeFixturesAllData(Request $request): JsonResponse
    {
        $this->_init();

        $fixturesLeague = (array) $this->apiFootballService->getFixtureLeague();

        $data = [
            'fixtures' => ['total' => 0],
            'standings' => ['league_id' => null, 'season_id' => null, 'round' => null],
            'home_team_squad' => ['total' => 0],
            'away_team_squad' => ['total' => 0],
            'injuries' => ['total' => 0],
            'predictions' => ['total' => 0],
            'head_to_head' => ['total' => 0],
            'bets' => ['total' => 0],
        ];

        // Get standings and save
        $standings = $this->apiFootballService->getStandings();
        $standings = $this->apiFootballService->cleanData($standings, ApiFootballService::DATA_TYPE_STANDINGS);
        $saved = $this->standings->store([
            'league_id' => $this->apiFootballService->leagueId,
            'season_id' => $this->apiFootballService->seasonId,
            'round'     => $this->apiFootballService->round,
            'standings' => json_encode($standings)
        ]);

        if ($saved) {
            $data['standings']['league_id'] = $this->apiFootballService->leagueId;
            $data['standings']['season_id'] = $this->apiFootballService->seasonId;
            $data['standings']['round'] = $this->apiFootballService->round;
        }

        foreach ($fixturesLeague as $fixture) {
            $fixtureId = $fixture['fixture']['id'];
            $homeTeamId = $fixture['teams']['home']['id'];
            $awayTeamId = $fixture['teams']['away']['id'];

            // Get fixtures and save
            $fixtureMatch = $this->apiFootballService->getFixtureMatch($fixtureId);
            $fixtureMatch = $this->apiFootballService->cleanData($fixtureMatch, ApiFootballService::DATA_TYPE_FIXTURES);
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'league_id' => $this->apiFootballService->leagueId,
                'season_id' => $this->apiFootballService->seasonId,
                'round' => $this->apiFootballService->round,
                'fixtures' => json_encode($fixtureMatch)
            ]);

            if ($saved) {
                $data['fixtures'][] = $fixtureId;
                $data['fixtures']['total']++;
            }

            // Get home team squad and save
            $homeTeamSquad = $this->apiFootballService->getSquads($homeTeamId);
            $homeTeamSquad = $this->apiFootballService->cleanData($homeTeamSquad, ApiFootballService::DATA_TYPE_HOME_TEAM_SQUAD);
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'home_team_squad' => json_encode($homeTeamSquad)
            ]);

            if ($saved) {
                $data['home_team_squad'][] = $fixtureId;
                $data['home_team_squad']['total']++;
            }

            // Get away team squad and save
            $awayTeamSquads = $this->apiFootballService->getSquads($awayTeamId);
            $awayTeamSquads = $this->apiFootballService->cleanData($awayTeamSquads, ApiFootballService::DATA_TYPE_AWAY_TEAM_SQUAD);
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'away_team_squad' => json_encode($awayTeamSquads)
            ]);

            if ($saved) {
                $data['away_team_squad'][] = $fixtureId;
                $data['away_team_squad']['total']++;
            }

            // Get injuries and save
            $injuries = $this->apiFootballService->getInjuries($fixtureId);
            $injuries = $this->apiFootballService->cleanData($injuries, ApiFootballService::DATA_TYPE_INJURIES);
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'injuries' => json_encode($injuries)
            ]);

            if ($saved) {
                $data['injuries'][] = $fixtureId;
                $data['injuries']['total']++;
            }

            // Get predictions and save
            $predictions = $this->apiFootballService->getPredictions($fixtureId);
            $predictions = $this->apiFootballService->cleanData($predictions, ApiFootballService::DATA_TYPE_PREDICTIONS);
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'predictions' => json_encode($predictions)
            ]);

            if ($saved) {
                $data['predictions'][] = $fixtureId;
                $data['predictions']['total']++;
            }

            // Get head to head and save
            $head2head = $this->apiFootballService->getHeadToHead($fixtureId);
            $head2head = $this->apiFootballService->cleanData($head2head, ApiFootballService::DATA_TYPE_HEAD_TO_HEAD);
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'head_to_head' => json_encode($head2head)
            ]);

            if ($saved) {
                $data['head_to_head'][] = $fixtureId;
                $data['head_to_head']['total']++;
            }

            // Get bets and save
            $bets = $this->apiFootballService->getBets($fixtureId);
            $bets = $this->apiFootballService->cleanData($head2head, ApiFootballService::DATA_TYPE_BETS);
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'bets' => json_encode($bets)
            ]);

            if ($saved) {
                $data['bets'][] = $fixtureId;
                $data['bets']['total']++;
            }
        }

        return response()->json($data);
    }

    protected function _init(): void
    {
        $this->apiFootballService->init();
    }
}
