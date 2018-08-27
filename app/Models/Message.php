<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Message extends Model
{
    protected $fillable = [
        'to',
        'from',
        'message'
    ];

    public function user()
    {
        return $this->belongsTo('App\User', 'from');
    }

    public function getCreatedAtAttribute($value)
    {
        return \Carbon\Carbon::createFromFormat('Y-m-d H:i:s', $value)->format('Y-m-d H:i');
    }

    public function getShortMessage()
    {
        return strlen($this->message) < 30 ? $this->message : substr($this->message, 0, 30) . '...';
    }
}
