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

    protected $table = 'standings';
    protected $fillable = ['league_id', 'season_id', 'round', 'standings', 'created_at', 'updated_at'];

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
