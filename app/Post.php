<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use CrudTrait;

    protected $guarded = [];

    protected $dates = [
        'published_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopePublished($query)
    {
        return $query->where('published_at', '<', Carbon::now())
            ->whereNotNull('published_at');
    }
}
