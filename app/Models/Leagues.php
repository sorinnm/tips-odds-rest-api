<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

/**
 * @property mixed $api_football_id
 * @property mixed $name
 * @property mixed $country_id
 * @property mixed $category_id
 * @property mixed $category_path
 * @property mixed $page_id
 */
class Leagues extends Model
{
    use HasFactory;

    protected $table = 'leagues';
    protected $fillable = ['name', 'country_id', 'api_football_id', 'category_id', 'created_at', 'updated_at'];

    /**
     * Get the league associated with the season.
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Countries::class);
    }

    public function season(): HasOne
    {
        return $this->hasOne(Seasons::class, 'league_id', 'id');
    }
}
