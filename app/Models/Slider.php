<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Slider extends Model
{
    protected $fillable = [
        'image',
        'link',
    ];

    public function getImage()
    {
        $url = config('site.slider') . $this->image;
        return asset(file_exists($url) ? $url : config('site.slider') . 'default.jpeg');
    }

    public function link()
    {
        if (filter_var($this->link, FILTER_VALIDATE_URL)) {
            return $this->link;
        } else {
            return url($this->link);
        }
    }
}
