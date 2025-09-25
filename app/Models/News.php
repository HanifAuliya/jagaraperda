<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'date',
        'place',
        'description',
        'image',
        'active'
    ];

    protected $casts = [
        'date'   => 'date',
        'active' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($m) {
            if (!$m->slug || $m->isDirty('title')) {
                $base = Str::slug($m->title);
                $slug = $base;
                $i = 2;
                while (static::where('slug', $slug)->where('id', '!=', $m->id)->exists()) {
                    $slug = $base . '-' . $i++;
                }
                $m->slug = $slug;
            }
        });
    }

    // hanya yang aktif
    public function scopeActive($q)
    {
        return $q->where('active', true);
    }
}
