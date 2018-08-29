<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Storage;

class Image extends Model
{
    protected $fillable = [
        'file_name',
        'product_id',
    ];

    public function product()
    {
        return $this->belongsTo('App\Product');
    }

    public function delete()
    {
        parent::delete();
        Storage::delete('public/' . $this->file_name);
    }
}
