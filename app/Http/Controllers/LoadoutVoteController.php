<?php

namespace App\Http\Controllers;

use App\Loadout;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class LoadoutVoteController extends Controller
{
    public function __invoke(Request $request, Loadout $loadout): RedirectResponse
    {
        if ($request->user()->getVotingValue($loadout) == 1) {
            $request->user()->downVote($loadout);
        } else {
            $request->user()->upVote($loadout);
        }

        return redirect()->route('preview.show', $loadout->id);
    }
}
