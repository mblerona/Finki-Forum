<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Thread extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'content',
        'subject_id',
        'user_id',
        'file_path',
        'file_name',
        'is_anonymous',
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }
    public function likes()
    {
        return $this->hasMany(ThreadLike::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'thread_tag');
    }
}
