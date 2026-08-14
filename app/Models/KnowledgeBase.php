<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KnowledgeBase extends Model
{
    protected $fillable = [
        'category_id',
        'title',
        'content_body',
        'views_count',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
