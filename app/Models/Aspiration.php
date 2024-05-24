<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\SoftDeletes;

class Aspiration extends Model
{
    use HasFactory;
    use SoftDeletes;
    
    protected $dates = ['deleted_at'];

    protected $fillable = [
        'aspirationNo',
        'user_id',
        'category_id',
        'name',
        'description',
        'processDate',
        // 'processEstimationDate',
        'processedBy',
        'approvedBy',
        'status',
        // 'evidence',
        // 'likeCount',
        // 'dislikeCount',
        'problematicAspirationCount',
        'isPinned',
        'isChatOpened',
        'deletedBy',
        'deleteReason'
    ];

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function comments(){
        return $this->hasMany(Comment::class);
    }
    
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function reportedByUsers() {
        return $this->belongsToMany(User::class, 'user_report_aspirations', 'aspiration_id', 'user_id');
    }

    public function reactions()
    {
        return $this->hasMany(AspirationReaction::class);
    }
    
    public function likes()
    {
        return $this->reactions()->where('reaction', 'like');
    }
    
    public function dislikes()
    {
        return $this->reactions()->where('reaction', 'dislike');
    }

    public function isReportedByCurrentUser() {
        return $this->reportedByUsers()->where('user_id', Auth::id())->exists();
    }
}
