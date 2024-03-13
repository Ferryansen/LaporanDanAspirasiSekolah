<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $table = 'reports';

    protected $fillable = [
        'reportNo',
        'user_id',
        'category_id',
        'name',
        'description',
        'isUrgent',
        'isChatOpened',
        'processDate',
        'processEstimationDate',
        'approvalBy',
        'lastUpdatedBy',
        'status',
        'deletedBy',
        'deleteReason'
    ];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }
    
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function evidences() {
        return $this->hasMany(Evidence::class);
    }
}
