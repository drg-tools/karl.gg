<?php

namespace App\GraphQL\Mutations;
use App\Loadout;
use App\User;
use Illuminate\Support\Facades\Log;
use Nagy\LaravelRating\Models\Rating;
use Illuminate\Support\Facades\Auth;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class UpvoteLoadout
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
     public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $loadout = Loadout::findOrFail($args['id']);
        if (Auth::check()) {
            $user  = User::find(auth()->user()->id);
        }
        $isUpVoted = $user->upVoted()->contains('id', $args['id'] );
        Log::debug($isUpVoted);
        if ($isUpVoted) {
            $user->downvote($loadout);
        } else {
            $user->upVote($loadout);
        }
        return $loadout;
    }
}
