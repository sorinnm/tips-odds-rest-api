<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fixtures extends Model
{
    use HasFactory;

    protected $table = 'fixtures';
    protected $fillable = ['fixture_id', 'fixtures', 'standings', 'home_team_squad', 'away_team_squad', 'injuries', 'predictions', 'head_to_head', 'bets', 'status', 'created_at', 'updated_at'];
}
