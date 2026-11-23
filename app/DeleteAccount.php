<?php

namespace App;

use App\Models\Comment;
use App\Models\Loadout;
use App\Models\Report;
use App\Models\User;
use App\Models\Vote;
use ChristianKuri\LaravelFavorite\Models\Favorite;
use Illuminate\Support\Facades\DB;
use Nagy\LaravelRating\Models\Rating;

/**
 * Permanently removes a user's personal data. Builds and comments they
 * published stay up without an owner and display as "Anonymous".
 */
class DeleteAccount
{
    public function handle(User $user): void
    {
        DB::transaction(function () use ($user): void {
            Loadout::where('user_id', $user->id)->update(['user_id' => null]);

            Comment::where('user_id', $user->id)->update([
                'user_id' => null,
                'author_name' => 'Anonymous',
                'author_email' => null,
                'author_url' => null,
                'author_ip' => null,
                'user_agent' => null,
            ]);
            Vote::where('user_id', $user->id)->delete();
            Report::where('user_id', $user->id)->delete();

            Favorite::where('user_id', $user->id)->delete();
            Rating::where('model_type', $user->getMorphClass())->where('model_id', $user->id)->delete();

            $user->notifications()->delete();
            DB::table('sessions')->where('user_id', $user->id)->delete();

            $user->roles()->detach();
            $user->permissions()->detach();
            $user->forceDelete();
        });
    }
}
