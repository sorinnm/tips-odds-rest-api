<?php

namespace App\Http\Controllers;

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
     * @return JsonResponse|void
     */
    public function storeFixturesAllData(Request $request)
    {
        $leagueId = (int) $request->get('league');
        $seasonId = (int) $request->get('season');
        $round = (string) $request->get('round');
        $bookmakerId = (string) $request->get('bookmaker');

        $fixturesLeague = (array) $this->fixtures->getFixtureLeague($leagueId, $seasonId, $round);

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
        $standings = $this->standings->getStandings($leagueId, $seasonId);
        $saved = $this->standings->store([
            'league_id' => $leagueId,
            'season_id' => $seasonId,
            'round' => $round,
            'standings' => json_encode($standings)
        ]);

            if ($saved) {
                $data['standings'] = $standings;
                $data['standings']['league_id'] = $leagueId;
                $data['standings']['season_id'] = $seasonId;
                $data['standings']['round'] = $round;
            }

        foreach ($fixturesLeague as $fixture) {
            $fixtureId = $fixture['fixture']['id'];
            $homeTeamId = $fixture['teams']['home']['id'];
            $awayTeamId = $fixture['teams']['away']['id'];

            // Get fixtures and save
            $fixtureMatch = $this->fixtures->getFixtureMatch($fixtureId);
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'league_id' => $leagueId,
                'season_id' => $seasonId,
                'round' => $round,
                'fixtures' => json_encode($fixtureMatch)
            ]);

            if ($saved) {
                $data['fixtures'][] = $fixtureId;
                $data['fixtures']['total']++;
            }

            // Get home team squad and save
            $homeTeamSquad = $this->fixtures->getSquads($homeTeamId);
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'home_team_squad' => json_encode($homeTeamSquad)
            ]);

            if ($saved) {
                $data['home_team_squad'][] = $fixtureId;
                $data['home_team_squad']['total']++;
            }

            // Get away team squad and save
            $awayTeamSquads = $this->fixtures->getSquads($awayTeamId);
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'away_team_squad' => json_encode($awayTeamSquads)
            ]);

            if ($saved) {
                $data['away_team_squad'][] = $fixtureId;
                $data['away_team_squad']['total']++;
            }

            // Get injuries and save
            $injuries = $this->fixtures->getInjuries($leagueId, $seasonId, $fixtureId);
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'injuries' => json_encode($injuries)
            ]);

            if ($saved) {
                $data['injuries'][] = $fixtureId;
                $data['injuries']['total']++;
            }

            // Get predictions and save
            $predictions = $this->fixtures->getPredictions($fixtureId);
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'predictions' => json_encode($predictions)
            ]);

            if ($saved) {
                $data['predictions'][] = $fixtureId;
                $data['predictions']['total']++;
            }

            // Get head to head and save
            $head2head = $this->fixtures->getHeadToHead($fixtureId);
            $saved = $this->fixtures->store([
                'fixture_id' => $fixtureId,
                'head_to_head' => json_encode($head2head)
            ]);

            if ($saved) {
                $data['head_to_head'][] = $fixtureId;
                $data['head_to_head']['total']++;
            }

            // Get bets and save
            $bets = $this->fixtures->getBets($bookmakerId, $fixtureId);
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
}
