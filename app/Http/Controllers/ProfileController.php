<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;


class ProfileController extends Controller
{
    /**
     * Show the profile for the given user.
     *
     * @param  int  $id
     * @return View
     */
    public function index($id)
    {
        $loadouts = $this->getLoadoutsForUser($id);
        $loadoutCount = $this->getLoadoutCount($id);
        $salutesCount = $this->getVoteCount($id);
        return view('profile', ['user' => User::findOrFail($id), 'loadouts' => $loadouts, 'loadoutCount' => $loadoutCount, 'salutesCount' => $salutesCount]);
    }

    private function getLoadoutsForUser($userId) {
        $user = User::findOrFail($userId);
        $loadouts = $user->loadouts->all();

        return $loadouts;
    }

    private function getLoadoutCount($userId) {
        $user = User::findOrFail($userId);
        $loadoutCount = $user->loadouts->count();

        return $loadoutCount;
    }

    private function getVoteCount($userId) {
        $user = User::findOrFail($userId);
        $loadouts = $user->loadouts;
        $totalUpvotes = 0;
        if($loadouts->count() > 0) {
            foreach ($loadouts as $key => $item) {
                $upvotes = $item->upVotesCount();
                $totalUpvotes = $totalUpvotes + $upvotes;
            }
        }


        return $totalUpvotes;
    }
}
