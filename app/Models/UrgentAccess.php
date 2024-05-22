<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UrgentAccess extends Model
{
    use HasFactory;

    protected $table = 'urgent_accesses';

    protected $fillable = [
        'report_id',
        'accessCode',
        'expires_at'
    ];

    public function report() {
        return $this->belongsTo(Report::class, 'report_id');
    }

}
