<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\AddVoteRequest;
use Illuminate\Support\Facades\Auth;
use App\Http\Resources\VoteResource;

use App\Models\Vote;
use App\Models\VoteChoose;

class DashboardController extends Controller {

    public function index() {

        $votes = Vote::with([
                        'chooses' => function($q) {
                            return $q -> withCount('polls');
                        }
                    ])
                    -> withCount('polls')
                    -> where('user_id', Auth::user() -> id) -> get();
        return view('dashboard', compact('votes'));

    }

    public function store(AddVoteRequest $request) {

        $vote = Vote::create([
            'title' => $request -> title,
            'user_id' => Auth::user() -> id
        ]);

        $dataToInsert = [];

        foreach ($request -> chooses as $choose) {
            $dataToInsert[] = [
                'choose' => $choose,
                'vote_id' => $vote -> id,
            ];
        }

        VoteChoose::insert($dataToInsert);

        return redirect() -> route('dashboard.index');

    }

}
