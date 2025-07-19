<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    protected $fillable = [
        'name',
        'description',
        'published_at',
        'author_id',
    ];

    protected $casts = [
        'published_at' => 'date',
    ];

    public function author() {
        return $this->belongsTo(Author::class);
    }
}
