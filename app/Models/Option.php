<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Option extends Model
{
    protected $fillable = [
        'key',
        'value',
    ];
    public $timestamps = false;
    protected $primaryKey = 'key';
    public $incrementing = false;

    public function scopeGetOptions($query)
    {
        $options = Option::get();
        $data = [];
        if (count($options) > 0) {
            foreach ($options as $option) {
                if (in_array($option->key, config('site.assets'))) {
                    $url = config('site.folder') . $option->value;
                    $data[$option->key] = url(file_exists($url) ? $url : config('site.folder') . $option->key . '.png');
                } else {
                    $data[$option->key] = $option->value;
                }
            }
        }

        return $data;
    }
}
