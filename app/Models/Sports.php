<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Sports extends Model
{
    use HasFactory;

    protected $table = 'sports';
    protected $fillable = ['name', 'category_id', 'created_at', 'updated_at'];
}
