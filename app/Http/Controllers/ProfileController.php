<?php

namespace App\Http\Controllers;

use App\User;
use Artesaos\SEOTools\Facades\SEOTools;
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
    public function index(Request $request, $id)
    {
        SEOTools::setTitle('Profile');

        $loadouts = $request->user()->loadouts()->paginate();
        $loadoutCount = $request->user()->loadouts->count();

        $salutesCount = $this->getVoteCount($id);
        $loadoutsWithSalutes = $this->addSalutesToLoadoutsCollection($loadouts);
        $profileImgClassArray = ['profileImageS', 'profileImageD', 'profileImageE', 'profileImageG'];
        $profileImgClass = Arr::random($profileImgClassArray);

        return view('profile.show', [
            'user' => User::findOrFail($id),
            'loadouts' => $loadoutsWithSalutes,
            'loadoutCount' => $loadoutCount,
            'salutesCount' => $salutesCount,
            'profileImgClass' => $profileImgClass
        ]);
    }

    /**
     * Allow the current user to edit their current profile.
     */
    public function editProfile(Request $request, $id)
    {
        // TODO: make this a static path rather than by ID, then we can remove a lot of this logic
        SEOTools::setTitle('Profile');

        $authUserId = \Auth::id();
        if ($authUserId == $id) {
            // You are authenticated and trying to edit your profile
            return view('profile.edit', ['user' => $request->user()]);
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
        // TODO: simplify this
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
            $loadout->votes_count = $loadout->upVotesCount();
        }

        return $loadouts;
    }
}
