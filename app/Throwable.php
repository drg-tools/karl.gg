<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Throwable
 *
 * @property int $id
 * @property int $character_id
 * @property string $name
 * @property string $description
 * @property string $json_stats
 * @property int $credits_cost
 * @property int $magnite_cost
 * @property int $bismor_cost
 * @property int $umanite_cost
 * @property int $croppa_cost
 * @property int $enor_pearl_cost
 * @property int $jadiz_cost
 * @property string $icon
 * @property int $patch_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Character $character
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable filter($input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable query()
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereBismorCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereCharacterId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereCreditsCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereCroppaCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereEnorPearlCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereJadizCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereJsonStats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereMagniteCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable wherePatchId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereUmaniteCost($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Throwable whereUpdatedAt($value)
 * @mixin \Eloquent
 */
class Throwable extends Model
{
    use Filterable, CrudTrait;

    public function character()
    {
        return $this->belongsTo(Character::class);
    }
}
