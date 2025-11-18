<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Laravel\Sanctum\HasApiTokens;
use Laravel\Scout\Searchable;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasApiTokens, HasFactory, AuthorizesRequests, Searchable, SoftDeletes;

    protected $table = 'posts';
    protected $fillable = [
        'user_id',
        'title',
        'id',
        'slug',
        'image_url',
        'content',
        'created_at',
        'updated_at',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function toSearchableArray()
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'content' => $this->content,
        ];
    }
}
