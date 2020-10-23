<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Patch.
 *
 * @property int $id
 * @property string $patch_num
 * @property string $patch_num_dev
 * @property string $patch_title
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|Patch filter($input = [], $filter = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Patch newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patch newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Patch paginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Patch query()
 * @method static \Illuminate\Database\Eloquent\Builder|Patch simplePaginateFilter($perPage = null, $columns = [], $pageName = 'page', $page = null)
 * @method static \Illuminate\Database\Eloquent\Builder|Patch whereBeginsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Patch whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patch whereEndsWith($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Patch whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patch whereLike($column, $value, $boolean = 'and')
 * @method static \Illuminate\Database\Eloquent\Builder|Patch wherePatchNum($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patch wherePatchNumDev($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patch wherePatchTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patch whereUpdatedAt($value)
 * @mixin \Eloquent
 * @property string|null $launched_at
 * @method static \Illuminate\Database\Eloquent\Builder|Patch whereLaunchedAt($value)
 */
class Patch extends Model
{
    use Filterable, CrudTrait;

    protected $dates = [
        'launched_at',
    ];

    protected $guarded = [];

    /**
     * @return Patch|\Illuminate\Database\Eloquent\Builder|Model|object|null
     */
    public function current()
    {
        return static::latest('launched_at')->first();
    }
}
