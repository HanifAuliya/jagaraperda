<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class AspirasiFile extends Model
{
    use HasFactory;


    protected $fillable = ['aspirasi_id', 'path', 'original_name', 'size'];


    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class);
    }
}
