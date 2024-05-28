<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'consultant',
        'attendees',
        'attendeeLimit',
        'location',
        'status',
        'start',
        'end',
        'is_online',
    ];

    protected $casts = [
        'attendees' => 'array',
    ];

    public function consultBy()
    {
        return $this->belongsTo(User::class, 'consultant');
    }
}
