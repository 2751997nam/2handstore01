<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\FullTextSearch;

class Product extends Model
{
    use SoftDeletes;
    use FullTextSearch;

    protected $fillable = [
        'name',
        'price',
        'thumbnail',
        'detail',
        'brand',
        'category_id',
        'province_id',
        'user_id',
        'status',
        'quantity'
    ];

    protected $searchable = [
        'name'
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['deleted_at'];


    public function user()
    {
        return $this->belongsTo('App\User');
    }

    public function commentUsers()
    {
        return $this->belongsToMany('App\User', 'comments')->withPivot('content', 'parent_id');
    }

    public function reports()
    {
        return $this->belongsToMany('App\User', 'reports')->withPivot('type', 'content');
    }

    public function orders()
    {
        return $this->belongsToMany('App\User', 'orders')->withPivot('address', 'note');
    }

    public function images()
    {
        return $this->hasMany('App\Image');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function province()
    {
        return $this->belongsTo('App\Province');
    }

    public function thumbnail()
    {
        $url = config('site.thumbnail') . $this->thumbnail;
        return asset(file_exists($url) ? $url : config('site.thumbnail') . 'default.jpeg');
    }

    public function status()
    {
        switch ($this->status) {
            case 0:
                return [
                    'css' => 'dark',
                    'status' => __('admin.product.status.sold')
                ];
                break;
            case 1:
                return [
                        'css' => 'success',
                        'status' => __('admin.product.status.available')
                    ];
                break;
        }
    }

    public function scopeList($query)
    {
        return $query->with('category', 'user')
                    ->latest();
    }

    public function getMoneyAttribute()
    {
        return number_format($this->price) . ' VND';
    }

    public function comments()
    {
        return $this->hasMany('App\Comment');
    }

    public function scopeSuggestedProducts($query, $id)
    {
        return $query->where('category_id', '=', $id)
        ->where('status', '=', '1')
        ->withProvince()
        ->limit(config('database.suggested'))->get();
    }

    public function getBrandAttribute($value)
    {
        return strtoupper($value);
    }

    public function scopeDeleteProductByCategory($query, $id)
    {
        return $query->where('category_id', '=', $id)->delete();
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', '=', '1');
    }

    public function scopeWithProvince($query)
    {
        return $query->with(['province' => function ($query) {
            $query->with('city');
        }]);
    }
    
    public function scopeSearchProduct($query, $request)
    {
        $path = '?search=' . $request->search
                . '&category=' . $request->category
                . '&city=' . $request->city
                . '&province=' . ($request->has('city') ? $request->province : '')
                . '&minprice=' . $request->minprice
                . '&maxprice=' . $request->maxprice
                . '&sort=' . $request->sort
                . '&orderBy=' . $request->orderBy
                . '&type=' . $request->type;

        return $query->where(function ($sub) use ($request) {
            if ($request->search) {
                $sub->where('name', 'like', '%' . $request->search . '%');
            }
            if ($request->category) {
                $sub->where('category_id', '=', $request->category);
            }
            if ($request->province) {
                $sub->where('province_id', '=', $request->province);
            }
            if ($request->minprice) {
                $sub->where('price', '>=', $request->minprice);
            }
            if ($request->maxprice) {
                $sub->where('price', '<=', $request->maxprice);
            }
        })->withProvince()
            ->orderBy($request->orderBy ?: 'price', $request->type?: 'asc')
            ->paginate(config('database.paginate'))->withPath($path);
    }

    public function getAddress()
    {
        return $this->province->name . ' ' . $this->province->city->name;
    }
}
