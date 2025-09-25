<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class TanggapanAspirasi extends Model
{
    use HasFactory;


    protected $table = 'tanggapan_aspirasi';


    protected $fillable = ['aspirasi_id', 'actor', 'user_id', 'isi', 'file_path'];


    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class);
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
