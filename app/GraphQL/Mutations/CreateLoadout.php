<?php

namespace App\GraphQL\Mutations;

use App\Loadout;
use GraphQL\Type\Definition\ResolveInfo;
use Illuminate\Support\Arr;
use Nuwave\Lighthouse\Support\Contracts\GraphQLContext;

class CreateLoadout
{
    /**
     * Return a value for the field.
     *
     * @param  null  $rootValue Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
	$loadout = Loadout::make($args);

	// TODO: Remove when auth Passport implemented
	$loadout->user_id = 1;
	$loadout->save();

    // TODO: do we assume mods is always here?
    // Andrew: Per above, my assumption is a loadout should have 1 selection at least in order to allow save. We need some validation.
	$loadout->mods()->sync(Arr::flatten($args['mods']));

	return $loadout;
    }
}
