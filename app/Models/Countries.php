<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Countries extends Model
{
    use HasFactory;

    protected $table = 'countries';
    protected $fillable = ['name', 'category_id', 'created_at', 'updated_at'];

    public function sport(): BelongsTo
    {
        return $this->belongsTo(Sports::class);
    }
}
