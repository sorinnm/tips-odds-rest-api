<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Fixtures extends Model
{
    use HasFactory;

    const API_ENDPOINT_FIXTURES = '/fixtures';
    const API_ENDPOINT_SQUADS = '/players/squads';
    const API_ENDPOINT_INJURIES = '/injuries';
    const API_ENDPOINT_PREDICTIONS = '/predictions';
    const API_ENDPOINT_BETS = '/odds';

    protected array $head2head = [];

    protected $table = 'fixtures';
    protected $fillable = ['fixture_id', 'fixtures', 'standings', 'home_team_squad', 'away_team_squad', 'injuries', 'predictions', 'head_to_head', 'bets', 'status', 'created_at', 'updated_at'];

    /**
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse|void
     */
    public function getFixtureLeague(Request $request)
    {
        $fixturesLeague = false;

        try {
            $response = Http::withHeaders([
                'x-apisports-key' => env('X_RAPIDAPI_KEY'),
                'Accept' => 'application/json'
            ])->get(env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_FIXTURES, [
                'league' => $request->get('league'),
                'season' => $request->get('season'),
                'round' => $request->get('round'),
            ]);

            Log::debug("FIXTURE_LEAGUE: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data['response'])) {
                    $fixturesLeague = $data['response'];
                }
            }

            return $fixturesLeague;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    /**
     * @param int $fixtureId
     * @return mixed|void
     */
    public function getFixtureMatch(int $fixtureId)
    {
        $fixturesMatch = false;

        try {
            $response = Http::withHeaders([
                'x-apisports-key' => env('X_RAPIDAPI_KEY'),
                'Accept' => 'application/json'
            ])->get(env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_FIXTURES, [
                'id' => $fixtureId,
            ]);

            Log::debug("FIXTURE_MATCH: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data['response'])) {
                    $fixturesMatch = $data['response'];
                }
            }

            return $fixturesMatch;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    /**
     * @param int $teamId
     * @return mixed|void
     */
    public function getSquads(int $teamId)
    {
        $squads = false;

        try {
            $response = Http::withHeaders([
                'x-apisports-key' => env('X_RAPIDAPI_KEY'),
                'Accept' => 'application/json'
            ])->get(env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_SQUADS, [
                'team' => $teamId
            ]);

            Log::debug("SQUADS: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data['response'])) {
                    $squads = $data['response'];
                }
            }

            return $squads;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    /**
     * @param Request $request
     * @param int $fixtureId
     * @return mixed|void
     */
    public function getInjuries(Request $request, int $fixtureId)
    {
        $injuries = false;

        try {
            $response = Http::withHeaders([
                'x-apisports-key' => env('X_RAPIDAPI_KEY'),
                'Accept' => 'application/json'
            ])->get(env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_INJURIES, [
                'league' => $request->get('league'),
                'season' => $request->get('season'),
                'fixture' => $fixtureId
            ]);

            Log::debug("INJURIES: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data['response'])) {
                    $injuries = $data['response'];
                }
            }

            return $injuries;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    /**
     * @param int $fixtureId
     * @return mixed|void
     */
    public function getPredictions(int $fixtureId)
    {
        $predictions = false;
        $head2head = false;

        try {
            $response = Http::withHeaders([
                'x-apisports-key' => env('X_RAPIDAPI_KEY'),
                'Accept' => 'application/json'
            ])->get(env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_PREDICTIONS, [
                'fixture' => $fixtureId,
            ]);

            Log::debug("PREDICTIONS: " . $response->status());
            Log::debug("HEAD_TO_HEAD: " . $response->status());


            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data['response'])) {
                    $predictions = $data['response'];
                    $this->head2head = [
                        'fixtureId' => $fixtureId,
                        'h2h' => $predictions[0]['h2h']
                    ];
                }
            }

            return $predictions;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    /**
     * @param int $fixtureId
     * @return false|mixed
     */
    public function getHeadToHead(int $fixtureId)
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
     * @param Request $request
     * @param int $fixtureId
     * @return mixed|void
     */
    public function getBets(Request $request, int $fixtureId)
    {
        $bets = false;

        try {
            $response = Http::withHeaders([
                'x-apisports-key' => env('X_RAPIDAPI_KEY'),
                'Accept' => 'application/json'
            ])->get(env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_BETS, [
                'bookmaker' => $request->get('bookmaker'),
                'fixture' => $fixtureId
            ]);

            Log::debug("BETS: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data['response'])) {
                    $bets = $data['response'];
                }
            }

            return $bets;
        } catch (\Throwable $th) {
            Log::error($th->getMessage());
        }
    }

    /**
     * @param array $data
     * @return bool
     */
    public function store(array $data): bool
    {
        // Before creating a new fixture, try to find an existing one
        $fixtures = Fixtures::all();
        $fixture = $fixtures->firstWhere('fixture_id', $data['fixture_id']);

        if (empty($fixture)) {
            $fixture = new Fixtures;
        }

        foreach ($data as $column => $value) {
            $fixture->$column = $value;
        }

        return $fixture->save();
    }
}
