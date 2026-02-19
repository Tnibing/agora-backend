<?php

namespace App\Models;

use Backpack\CRUD\app\Models\Traits\CrudTrait;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\Storage;

class Article extends Model
{
    use CrudTrait;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'description',
        'description_image_url',
        'main_image_url',
        'content',
        'user_id',
    ];

    public function tags(): BelongsToMany
    {
        return $this->belongsToMany(Tag::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function getArticleDescriptionImageAttribute()
    {
        if($this->description_image_url){
            return Storage::disk('public')->url($this->description_image_url);
        }

        return Storage::disk('public')->url('images/article/no-image.png');
    }

    public function getArticleMainImageAttribute()
    {
        if($this->main_image_url){
            return Storage::disk('public')->url($this->main_image_url);
        }

        return Storage::disk('public')->url('images/article/no-image.png');
    }

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class)->whereNull('parent_id');
    }
}
