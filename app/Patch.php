<?php

namespace App;

use EloquentFilter\Filterable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * App\Patch.
 *
 * @property int $id
 * @property string $patch_num
 * @property string $patch_title
 * @property string|null $short_name
 * @property-read string $label
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 *
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
 * @method static \Illuminate\Database\Eloquent\Builder|Patch wherePatchTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Patch whereUpdatedAt($value)
 *
 * @mixin \Eloquent
 *
 * @property string|null $launched_at
 *
 * @method static \Illuminate\Database\Eloquent\Builder|Patch whereLaunchedAt($value)
 */
class Patch extends Model
{
    use Filterable;
    use HasFactory;

    protected $casts = [
        'launched_at' => 'datetime',
    ];

    protected $guarded = [];

    /**
     * The short name shown in lists and badges, falling back to the public patch number.
     */
    protected function label(): Attribute
    {
        return Attribute::get(fn (): string => $this->short_name ?: 'Patch '.$this->patch_num);
    }

    /**
     * @return Patch|Builder|Model|object|null
     */
    public static function current()
    {
        return static::latest('launched_at')->first();
    }

    /**
     * @return Patch|Builder|Model|object|null
     */
    public static function previous()
    {
        return static::latest('launched_at')->take(2)->get()[1];
    }
}
