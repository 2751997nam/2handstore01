<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ConfirmEmailNotification;
use App\Notifications\PasswordResetNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;

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
        'status',
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
        return $this->status == 1;
    }

    public function scopeCustomer($query)
    {
        return $query->where('role_id', '>', '1');
    }

    public function getAvatar()
    {
        return asset(Storage::url($this->avatar != null ? $this->avatar : 'images/default.png'));
    }

    public function scopeVerified($query)
    {
        return $query->where('status', '=', '1');
    }

    public function scopeUnverify($query)
    {
        return $query->where('status', '=', '0');
    }

    public function scopeBlocked($query)
    {
        return $query->where('status', '=', '-1');
    }

    public function scopeOption($query, $option) {
        if($option === 'verified') return $this->scopeVerified($query);
        if($option === 'unverify') return $this->scopeUnverify($query);
        if($option === 'blocked') return $this->scopeBlocked($query);
        
        return $query;
    }
}
