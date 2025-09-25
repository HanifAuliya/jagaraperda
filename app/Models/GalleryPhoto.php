<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;


class GalleryPhoto extends Model
{
    use HasFactory;


    protected $fillable = ['title', 'slug', 'image', 'active'];


    protected $casts = [
        'active' => 'boolean',
    ];


    // auto-generate slug on creating/updating when title changes
    public static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (!$model->slug || $model->isDirty('title')) {
                $base = Str::slug($model->title);
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->where('id', '!=', $model->id)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $model->slug = $slug;
            }
        });
    }


    // scope only active
    public function scopeActive($q)
    {
        return $q->where('active', true);
    }
}
