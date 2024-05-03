<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class Standings extends Model
{
    use HasFactory;

    const API_ENDPOINT_STANDINGS = '/standings';

    protected $table = 'standings';
    protected $fillable = ['league_id', 'season_id', 'round', 'standings', 'created_at', 'updated_at'];

    /**
     * @param Request $request
     * @return mixed|void
     */
    public function getStandings(int $leagueId, int $seasonId)
    {
        $fixturesStandings = false;

        try {
            $response = Http::withHeaders([
                'x-apisports-key' => env('X_RAPIDAPI_KEY'),
                'Accept' => 'application/json'
            ])->get(env('X_RAPIDAPI_HOST') . self::API_ENDPOINT_STANDINGS, [
                'league' => $leagueId,
                'season' => $seasonId
            ]);

            Log::debug("STANDINGS: " . $response->status());

            if ($response->successful()) {
                $data = $response->json();

                if (!empty($data) && isset($data['response'])) {
                    $fixturesStandings = $data['response'];
                }
            }

            return $fixturesStandings;
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
        $standings = Standings::all();
        $standings = $standings
            ->where('league_id', $data['league_id'])
            ->where('season_id', $data['season_id'])
            ->where('round', $data['round'])
            ->first();

        if (empty($standings)) {
            $standings = new Standings();
        }

        foreach ($data as $column => $value) {
            $standings->$column = $value;
        }

        return $standings->save();
    }

    /**
     * @param int $leagueId
     * @param int $seasonId
     * @param string $round
     * @return Standings
     */
    public function retrieveStandings(int $leagueId, int $seasonId, string $round): Standings
    {
        return Standings::all()
            ->where('league_id', $leagueId)
            ->where('season_id', $seasonId)
            ->where('round', $round)->first();
    }
}
