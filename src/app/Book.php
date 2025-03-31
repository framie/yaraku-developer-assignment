<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = ['title', 'author_id', 'published_at'];

    public function author()
    {
        return $this->belongsTo(Author::class);
    }
}
