<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\VoteChoose;
use App\Models\Poll;

class Vote extends Model {
    use HasFactory;

    protected $guarded = [];

    public function chooses() {
        return $this -> hasMany(VoteChoose::class, 'vote_id', 'id');
    }

    public function polls() {
        return $this -> hasManyThrough(Poll::class, VoteChoose::class, 'vote_id', 'choose_id', 'id', 'id');
    }

}
