<?php

namespace App\Http\Controllers;

use App\User;
use App\Loadout;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;

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
        $profileImgClassArray = ['profileImageS', 'profileImageD', 'profileImageE', 'profileImageG'];
        $profileImgClass = Arr::random($profileImgClassArray);

        return view('profile', ['user' => User::findOrFail($id), 'loadouts' => $loadoutsWithSalutes, 'loadoutCount' => $loadoutCount, 'salutesCount' => $salutesCount, 'profileImgClass' => $profileImgClass]);
    }

    /**
     * Allow the current user to edit their current profile.
     *
     * @param  int  $id
     * @return View
     */
    public function editProfile(Request $request, $id)
    {
        $authUserId = \Auth::id();
        if ($authUserId == $id) {
            // You are authenticated and trying to edit your profile
            return view('editprofile', ['user' => User::findOrFail($id)]);
        } else {
            // Do not allow a user to edit someone else's profile
            return response()->view('errors.'.'403', [], 403);
        }
    }

    /**
     * Update the user's profile.
     *
     * @param  Request  $request
     * @return Response
     */
    public function editProfileSave(Request $request, $id)
    {
        $authUserId = \Auth::id();
        if ($authUserId == $id) {
            $user = User::find($id);
            if (\Auth::user()->email == request('email')) {
                $this->validate(request(), [
                    'name' => 'required',
                    //    'email' => 'required|email|unique:users',
                    'password' => 'required|min:6|confirmed',
                ]);

                $user->name = request('name');
                // $user->email = request('email');
                $user->password = bcrypt(request('password'));

                $user->save();

                return back();
            } else {
                $this->validate(request(), [
                    'name' => 'required',
                    'email' => 'required|email|unique:users',
                    'password' => 'required|min:6|confirmed',
                ]);

                $user->name = request('name');
                $user->email = request('email');
                $user->password = bcrypt(request('password'));

                $user->save();

                return back();
            }
        } else {
            // Do not allow a user to edit someone else's profile
            return response()->view('errors.'.'403', [], 403);
        }
    }

    public function deleteLoadout(Request $request, $id)
    {
        $authUserId = \Auth::id();
        $loadout = Loadout::find($id);
        // dd($loadout);
        if($authUserId == null) {
            return response()->view('errors.'.'403', [], 403);
        } elseif($authUserId == $loadout->user_id) {
            Loadout::destroy($id);
            return redirect('profile/' . $authUserId);
        }
    }

    private function getLoadoutsForUser($userId)
    {
        $user = User::findOrFail($userId);
        $loadouts = $user->loadouts->all();

        return $loadouts;
    }

    private function getLoadoutCount($userId)
    {
        $user = User::findOrFail($userId);
        $loadoutCount = $user->loadouts->count();

        return $loadoutCount;
    }

    private function getVoteCount($userId)
    {
        $user = User::findOrFail($userId);
        $loadouts = $user->loadouts;
        $totalUpvotes = 0;
        if ($loadouts->count() > 0) {
            foreach ($loadouts as $key => $item) {
                $upvotes = $item->upVotesCount();
                $totalUpvotes = $totalUpvotes + $upvotes;
            }
        }

        return $totalUpvotes;
    }

    private function addSalutesToLoadoutsCollection($loadouts)
    {
        foreach ($loadouts as $key => $loadout) {
            $loadout->salutes = $loadout->upVotesCount();
        }

        return $loadouts;
    }
}
