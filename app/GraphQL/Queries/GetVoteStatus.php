<?php

namespace App\GraphQL\Queries;
use App\Loadout;
use App\User;
use Nagy\LaravelRating\Models\Rating;
use Illuminate\Support\Facades\Auth;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetVoteStatus
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
     public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
     {
        $loadout = Loadout::findOrFail($args['id']);
        // TODO: handle guest user
        if (Auth::check()) {
            $user  = User::find(auth()->user()->id);
        }
        $isUpVoted = $user->upVoted()->contains('id', $args['id'] );
        // 0 no, 1 yes
        return $isUpVoted;
     }
}
