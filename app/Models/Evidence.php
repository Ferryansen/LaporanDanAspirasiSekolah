<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Evidence extends Model
{
    use HasFactory;

    protected $table = 'evidences';

    protected $fillable = [
        'report_id',
        'image',
        'video',
        'name',
        'context',
    ];

    public function report() {
        return $this->belongsTo(Report::class);
    }

    public function aspiration() {
        return $this->belongsTo(Aspiration::class);
    }
}
