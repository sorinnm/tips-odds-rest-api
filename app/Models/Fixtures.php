<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * @property mixed $fixture_id
 * @property mixed $league
 * @property mixed $season_id
 * @property mixed $fixtures
 * @property mixed $home_team_id
 * @property mixed $away_team_id
 */
class Fixtures extends Model
{
    use HasFactory;

    protected $table = 'fixtures';
    protected $fillable = [
        'fixture_id',
        'fixtures',
        'standings',
        'home_team_squad',
        'away_team_squad',
        'injuries',
        'predictions',
        'head_to_head',
        'bets',
        'status',
        'step',
        'home_log',
        'home_team_id',
        'away_logo',
        'away_team_id',
        'created_at',
        'updated_at'
    ];

    const STATUS_COMPLETE = 'complete';
    const STATUS_PENDING = 'pending';
    const STATUS_ERROR = 'error';

    // steps
    const PROCESS_STEPS = [
        1  => 'Imported API-Football',
        2  => 'Data Integrity Error',
        3  => 'Data Integrity Warning',
        4  => 'Data Integrity OK',
        5  => 'AI Generation Fail',
        6  => 'AI Generation OK',
        7  => 'Generation Content Fail',
        8  => 'Generation Content OK',
        9  => 'Template Validation Fail',
        10 => 'Template Validation OK',
        11 => 'Not Published',
        12 => 'Published'
    ];

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

    public function league(): BelongsTo
    {
        return $this->belongsTo(Leagues::class);
    }
}
