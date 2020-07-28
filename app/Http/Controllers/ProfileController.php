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
        $loadoutsWithSalutes = $this->addSalutesToLoadoutsCollection($loadouts);
        return view('profile', ['user' => User::findOrFail($id), 'loadouts' => $loadoutsWithSalutes, 'loadoutCount' => $loadoutCount, 'salutesCount' => $salutesCount]);
    }

    /**
     * Allow the current user to edit their current profile
     *
     * @param  int  $id
     * @return View
     */
    public function editProfile($id)
    {
        return view('editprofile', ['user' => User::findOrFail($id)]);
    }
    /**
     * Update the user's profile.
     *
     * @param  Request  $request
     * @return Response
     */
    public function editProfileSave(Request $request)
    {
        // $request->user() returns an instance of the authenticated user...
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

    private function addSalutesToLoadoutsCollection($loadouts) {
        foreach ($loadouts as $key => $loadout) {
            $loadout->salutes = $loadout->upVotesCount();
         }
        return $loadouts;
    }
}
