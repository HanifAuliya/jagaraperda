<?php

// app/Models/AspirasiFeedback.php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AspirasiFeedback extends Model
{
    protected $table = 'aspirasi_feedback';

    protected $fillable = [
        'aspirasi_id',
        'rating',
        'comment',
        'submitted_by_ip',
        'user_agent',
    ];

    public function aspirasi()
    {
        return $this->belongsTo(Aspirasi::class);
    }
}
