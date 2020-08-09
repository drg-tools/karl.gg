<?php

namespace App\GraphQL\Queries;

use App\Loadout;
use GraphQL\Type\Definition\ResolveInfo;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class GetLoadoutVotes
{
    /**
     * @param  null  $_
     * @param  array<string, mixed>  $args
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $loadout = Loadout::findOrFail($rootValue['id']);
        $votes = $loadout->upVotesCount();

        return $votes;
    }
}
