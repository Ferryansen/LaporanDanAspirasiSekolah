<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aspiration extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'aspiration_no',
        'user_id',
        'category_id',
        'name',
        'description',
        'processDate',
        'processEstimationDate',
        'processedBy',
        'status',
        'evidence',
        'likeCount',
        'dislikeCount',
        'problematicAspirationCount',
        'isPinned',
        'isChatOpened',
        'deletedBy',
        'deleteReason'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }
    
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function reportedByUsers() {
        return $this->belongsToMany(User::class, 'user_report_aspirations', 'aspiration_id', 'user_id');
    }
}
