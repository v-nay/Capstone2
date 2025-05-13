<?php

namespace App\Models;

use App\Models\Motel;
use Illuminate\Database\Eloquent\Model;

class Ranking extends Model
{
    protected $fillable = ['rating', 'user_total_rating', 'score', 'rank'];
    public function motel()
    {
        return $this->belongsTo(Motel::class);
    }
}
