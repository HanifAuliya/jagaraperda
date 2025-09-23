<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Raperda extends Model
{
    protected $fillable = ['judul', 'tahun', 'status', 'aktif', 'ringkasan', 'berkas', 'slug'];

    use HasFactory;

    // hook saving
    protected static function booted()
    {
        static::saving(function ($raperda) {
            $slugJudul = Str::slug($raperda->judul, '-');
            $tahun = $raperda->tahun ?? date('Y');
            $raperda->slug = "{$tahun}-{$slugJudul}";
        });
    }
}
