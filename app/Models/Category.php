<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'thumbnail',
        'parent_id',
    ];

    public function products()
    {
        return $this->hasMany('App\Product');
    }

    public function categories()
    {
        return $this->hasMany('App\Category', 'parent_id', 'id');
    }

    public function category()
    {
        return $this->belongsTo('App\Category');
    }

    public function getThumbnail()
    {
        return url($this->thumbnail);
    }

    public function scopeParentCategories($query)
    {
        return $query->where('parent_id', '=', null)
            ->orderBy('name', 'asc')
            ->with('categories')
            ->paginate(config('database.paginate'));
    }

    public function saveCategory($data)
    {
        if ($this->thumbnail !== null && isset($data['thumbnail'])) {
            Storage::delete($this->thumbnail);
        }

        if (isset($data['is_parent']) && $data['is_parent'] == 1) {
            $data['parent_id'] = null;
        }

        $this->fill($data);

        return $this->save();
    }

    public function scopeArrayCategories($query)
    {
        return $query->where('parent_id', '=', null)
                ->pluck('name', 'id')
                ->toArray();
    }

    public function deleteCategory()
    {
        Storage::delete($this->thumbnail);
        Category::destroy($this->id);
    }


    public function scopeFindBySlug($query, $slug)
    {
        $category = Category::where('slug', $slug)->first();
        if ($category) {
            return $category;
        }

        return false;
    }

    public function getProducts($sort)
    {
        if (!is_array($sort)) {
            return false;
        }
        $other_path = '?sortBy=' . $sort['sortBy'] . '&type=' . $sort['type'];
        
        return $this->products()
                ->orderBy($sort['sortBy'], $sort['type'])
                ->paginate(config('database.paginate'))
                ->withPath($other_path);
    }
    
    public function scopeConvertToArray($query)
    {
        $categories = Category::get();
        $arrayCategories = [];
        foreach ($categories as $category) {
            $arrayCategories[$category->id] = $category->name;
        }
        
        return $arrayCategories;
    }
    
    public function scopeDeleteSubCategory($query, $id)
    {
        return $query->where('parent_id', '=', $id)->delete();
    }

    public function getNameAttribute($value)
    {
        return __($value);
    }
}
