<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'author',
        'category_id',
        'description',
        'published_at',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function borrowRecords(){
        return $this->hasMany(BorrowRecord::class);
    }

    public function ratings(){
        return $this->hasMany(Rating::class);
    }
}
