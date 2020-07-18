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
     * @param  null  $rootValue  Usually contains the result returned from the parent field. In this case, it is always `null`.
     * @param  mixed[]  $args  The arguments that were passed into the field.
     * @param  \Nuwave\Lighthouse\Support\Contracts\GraphQLContext  $context  Arbitrary data that is shared between all fields of a single query.
     * @param  \GraphQL\Type\Definition\ResolveInfo  $resolveInfo  Information about the query itself, such as the execution state, the field name, path to the field from the root, and more.
     * @return mixed
     */
    public function __invoke($rootValue, array $args, GraphQLContext $context, ResolveInfo $resolveInfo)
    {
        $loadout = Loadout::make($args);

        if($context->user() == null) {
            $loadout->user_id = null;
        } else {
            $loadout->user_id = $context->user()->id;
        }
        $loadout->save();

        $loadout->mods()->sync(Arr::flatten($args['mods']));
        $loadout->equipment_mods()->sync(Arr::flatten($args['equipment_mods']));
        $loadout->overclocks()->sync(Arr::flatten($args['overclocks']));

        return $loadout;
    }
}
