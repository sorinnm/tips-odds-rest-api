<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixtures extends Model
{
    use HasFactory;

    protected $table = 'fixtures';
    protected $fillable = ['fixture_id', 'fixtures', 'standings', 'home_team_squad', 'away_team_squad', 'injuries', 'predictions', 'head_to_head', 'bets', 'status', 'created_at', 'updated_at'];

    const STATUS_COMPLETE = 'complete';
    const STATUS_PENDING = 'pending';
    const STATUS_RUNNING = 'running';
    const STATUS_RETRY = 'retry';
    const STATUS_ERROR = 'error';

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

    /**
     * @param int $leagueId
     * @param int $seasonId
     * @param string $round
     * @return Collection
     */
    public function getAll(int $leagueId, int $seasonId, string $round): Collection
    {
        return Fixtures::all()
            ->where('league_id', $leagueId)
            ->where('season_id', $seasonId)
            ->where('round', $round)
            ->where('status', '=', self::STATUS_PENDING);
    }

    /**
     * @param int $fixtureId
     * @return Collection
     */
    public function getById(int $fixtureId): Collection
    {
        return Fixtures::all()
            ->where('fixture_id', $fixtureId)
            ->where('status', '=', self::STATUS_PENDING);
    }
}
