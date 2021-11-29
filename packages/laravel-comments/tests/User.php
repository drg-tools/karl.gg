<?php

namespace Hazzard\Comments\Tests;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use Notifiable;

    protected $guarded = ['id'];

    public $timestamps = false;

    /**
     * @return array
     */
    public function authorAttributes()
    {
        return [
            'name' => $this->name,
            'email' => $this->email,
            'url' => $this->url,  // optional
            'avatar' => 'gravatar', // optional
        ];
    }

    // Gate::define('moderate-comments', function ($user) {
    //     return $user->role === 'admin';
    // });
}
