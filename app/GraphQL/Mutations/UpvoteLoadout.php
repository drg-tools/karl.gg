<?php

namespace App\GraphQL\Mutations;

use App\Loadout;
use GraphQL\Type\Definition\ResolveInfo;
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

        $user = auth()->user();
        $isUpVoted = $user->upVoted()->contains('id', $args['id']);

        if ($isUpVoted) {
            $user->downvote($loadout);
        } else {
            $user->upVote($loadout);
        }

        return $loadout;
    }
}
