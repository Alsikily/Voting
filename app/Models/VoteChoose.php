<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\Poll;

class VoteChoose extends Model {
    use HasFactory;

    protected $guarded = [];

    public function polls() {
        return $this -> hasMany(Poll::class, 'choose_id', 'id');
    }

    public function poll() {
        return $this -> hasOne(Poll::class, 'choose_id', 'id');
    }

}
