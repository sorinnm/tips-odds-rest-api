<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
}
