<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    use HasFactory;

    protected $fillable = [
        'reportId',
        'image',
        'video',
        'name'
    ];

    public function report() {
        return $this->belongsTo(Report::class);
    }
}
