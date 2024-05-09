<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Rounds extends Model
{
    use HasFactory;

    protected $table = 'rounds';
    protected $fillable = ['name', 'season_id', 'start_date', 'created_at', 'updated_at'];
}
