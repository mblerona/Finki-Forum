<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function threads()
    {
        return $this->belongsToMany(Thread::class, 'thread_tag');
    }
}
