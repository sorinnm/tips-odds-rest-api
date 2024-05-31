<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seasons extends Model
{
    use HasFactory;

    protected $table = 'seasons';
    protected $fillable = ['name', 'league_id', 'is_active', 'created_at', 'updated_at'];

    /**
     * Get the league associated with the season.
     */
    public function league(): BelongsTo
    {
        return $this->belongsTo(Leagues::class);
    }

    public function round(): HasMany
    {
        return $this->hasMany(Rounds::class, 'id', 'season_id');
    }
}
