<?php

namespace App;

use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use App\Notifications\ConfirmEmailNotification;
use App\Notifications\PasswordResetNotification;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Traits\FullTextSearch;

class User extends Authenticatable
{
    use Notifiable;
    use FullTextSearch;

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

    protected $searchable = [
        'email',
        'name'
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

    public function commentProducts()
    {
        return $this->belongsToMany('App\Product', 'comments')->withPivot('content', 'parent_id');
    }

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function notifications()
    {
        return $this->hasMany('App\Notification');
    }

    public function orders()
    {
        return $this->belongsToMany('App\Product', 'orders', 'buyer_id')->withPivot('address', 'note')->withTimestamps();
    }

    public function purchases()
    {
        return $this->hasMany('App\Order', 'buyer_id');
    }

    public function toUsers()
    {
        return $this->belongsToMany('App\User', 'messages', 'from', 'to')->withPivot('message', 'status');
    }

    public function messages()
    {
        return $this->hasMany('App\Message', 'from', 'id');
    }

    public function provider_users()
    {
        return $this->hasMany('provider_users');
    }

    public function verified()
    {
        return $this->status == 1;
    }

    public function scopeCustomer($query, $search = null)
    {
        return $query->where('role_id', '>', '1')
                ->where('email', 'like', '%' . $search . '%')
                ->orderBy('updated_at', 'desc')
                ->paginate(config('database.paginate'));
    }

    public function getAvatar()
    {
        return url($this->avatar != null ? $this->avatar : config('filesystems.avatar'));
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

    public function scopeOption($query, $option)
    {
        if ($option === 'verified') {
            return $this->scopeVerified($query);
        }
        if ($option === 'unverify') {
            return $this->scopeUnverify($query);
        }
        if ($option === 'blocked') {
            return $this->scopeBlocked($query);
        }
        
        return $query;
    }

    public function saveUser($data)
    {
        if (isset($data['avatar']) && $this->avatar !== null) {
            Storage::delete($this->avatar);
        }
        
        $this->fill($data);
        
        return $this->save();
    }

    public function getNameAttribute($value)
    {
        return strlen($value) < 45 ? $value : (substr($value, 0, 42) . '...');
    }

    public function scopeCustomerOption($query, $option, $search = null)
    {
        return $query->option($option)->customer($search);
    }

    public function getAverageRate()
    {
        $rates = [
            'avg' => 0,
            'votes' => 0
        ];
        if (count($this->raters) > 0) {
            $rates['avg'] = round($this->raters()->avg('stars'), 1);
            $rates['votes'] = count($this->raters);
        }

        return $rates;
    }

    public function saveRater(User $user, $stars)
    {
        if (!$this->raters()->find($user->id)) {
            return $this->raters()->save($user, ['stars' => $stars]);
        } else {
            return $this->raters()->updateExistingPivot($user, ['stars' => $stars]);
        }
    }

    public function getVote(User $user)
    {
        if ($rater = $this->raters()->find($user->id)) {
            return $rater->pivot->stars;
        }
        
        return false;
    }

    public function isBought($product_id)
    {
        $order = $this->purchases()
                ->where('product_id', $product_id)
                ->where(function ($query) {
                    $query->where('orders.status', config('site.inactive'));
                    $query->orWhere('orders.status', config('site.active'));
                })
                ->first();
        if ($order) {
            return true;
        } else {
            return false;
        }
    }

    public function blocked()
    {
        return $this->status == -1;
    }

    public function myOrders()
    {
        return $this->hasManyThrough('App\Order', 'App\Product');
    }

    public function getProductOrders()
    {
        return $this->myOrders()->orderBy('product_id', 'DESC')->latest()->get();
    }

    public function getMyProducts()
    {
        return $this->products()->latest()->paginate(config('database.paginate'));
    }

    public function getMyPurchases()
    {
        return $this->purchases()->paginate(config('database.paginate'));
    }

    public function getNumberNotify()
    {
        $notifications = $this->notifications()->where('status', '>', 0)->count();

        return $notifications;
    }
}
