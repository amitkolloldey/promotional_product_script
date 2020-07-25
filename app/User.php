<?php

namespace App;

use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use Notifiable, HasRoles;

    protected $guard_name = 'web';

    /**
     * The attributes that are mass assignable.
     * @var array
     */
    protected $fillable =
        [
            'name',
            'email',
            'password',
            'status',
            'phone_no',
            'company'
        ];

    /**
     * The attributes that should be hidden for arrays.
     * @var array
     */
    protected $hidden =
        [
            'password',
            'remember_token'
        ];

    /**
     * The attributes that should be cast to native types.
     * @var array
     */
    protected $casts =
        [
            'email_verified_at' => 'datetime'
        ];


    /**
     * @return BelongsToMany
     */
    public function orders()
    {
        return $this
            ->belongsToMany(Order::class, 'order_user');
    }
}
