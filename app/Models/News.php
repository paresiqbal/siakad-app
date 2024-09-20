<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'body',
        'image',
        'author',
    ];

    // News belongs to a User (Author)
    public function user()
    {
        return $this->belongsTo(User::class, 'author');
    }
}
