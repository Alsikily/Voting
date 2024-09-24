<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

use App\Models\Vote;
use App\Models\Poll;

class VoteController extends Controller {

    public function index() {

    }

    public function create() {

    }

    public function store(Request $request) {
        $exists = Vote::select('votes.*')
                    -> join('vote_chooses as vc', 'votes.id', '=', 'vc.vote_id')
                    -> join('polls as p', function($join) use ($request) {
                        $join -> on('p.choose_id', '=', 'vc.id')
                            -> where('p.user_id', '=', Auth::user() -> id)
                            -> where('votes.id', '=', $request -> voteID);
                    })
                    -> exists();

        if (!$exists) {
            Poll::insert([
                'choose_id' => $request -> chooseID,
                'user_id' => Auth::user() -> id
            ]);

            return response() -> json([
                'status' => 'success'
            ]);

        }

    }

    public function show(string $id) {

        $vote = Vote::with([
            'chooses' => function($q) {
                return $q -> with(['poll' => function($q) {
                    return $q -> where('user_id', Auth::user() -> id);
                }]) -> withCount('polls');
            }
        ])
        -> withCount('polls')
        -> where('id', $id)
        -> first();

        return view('vote', compact('vote'));

    }

    public function edit(string $id) {

    }

    public function update(Request $request, string $id) {

    }

    public function destroy(string $id) {

    }

}
