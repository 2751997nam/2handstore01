<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Notifications\ConfirmEmailNotification;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'address',
        'phone',
        'description',
        'is_active',
        'avatar',
        'verify_token',
        'role_id',
        'social',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'verify_token'
    ];

    public function rateds()
    {
        return $this->belongsToMany('App\User', 'rates', 'rater_id', 'rated_id')->withPivot('stars');
    }

    public function raters()
    {
        return $this->belongsToMany('App\User', 'rates', 'rated_id', 'rater_id')->withPivot('stars');
    }

    public function role()
    {
        return $this->belongsTo('App\Role');
    }

    public function reports()
    {
        return $this->belongsToMany('App\Product', 'reports')->withPivot('content', 'type');
    }

    public function comments()
    {
        return $this->belongsToMany('App\Product', 'comments')->withPivot('content', 'parent_id');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Product', 'orders')->withPivot('address', 'note');
    }

    public function provider_users()
    {
        return $this->hasMany('provider_users');
    }

    public function verified()
    {
        return $this->is_active == 1;
    }

    public function sendConfirmEmail()
    {
        $user = $this;
        return $this->notify(new ConfirmEmailNotification($user));
    }
}
