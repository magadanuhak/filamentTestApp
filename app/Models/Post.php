<?php

namespace App\Models;

use App\Traits\TranslatableTrait;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use TranslatableTrait;
    use SoftDeletes;

    protected $guarded = ['id'];

    protected $casts = [
        'images' => 'array',
    ];

    public function tags(): \Illuminate\Database\Eloquent\Relations\MorphMany
    {
        return $this->morphMany(Tag::class, 'taggable');
    }


    public function country():BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

}
