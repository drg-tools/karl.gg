<?php

namespace App;

use Backpack\CRUD\app\Models\Traits\CrudTrait; 
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use ChristianKuri\LaravelFavorite\Traits\Favoriteability;

class User extends Authenticatable
{
    use Notifiable, Favoriteability;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function builds()
    {
        return $this->hasMany(Build::class);
    }
}
