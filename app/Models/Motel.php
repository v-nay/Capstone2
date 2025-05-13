<?php

namespace App\Models;

use App\Models\Ranking;
use Illuminate\Database\Eloquent\Model;

class Motel extends Model
{
    //
    protected $fillable = ['name', 'address', 'website', 'price', 'google_place_id', 'accessible', 'phone', 'distance'];

    public function ranking()
    {
        return $this->hasOne(Ranking::class);
    }
}
