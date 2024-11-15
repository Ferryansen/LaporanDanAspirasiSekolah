<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Kyslik\ColumnSortable\Sortable;

class Report extends Model
{
    use HasFactory, Sortable;

    protected $table = 'reports';

    protected $fillable = [
        'reportNo',
        'user_id',
        'category_id',
        'name',
        'description',
        'priority',
        'isUrgent',
        'isFromConsultation',
        'consultationName',
        'consultationDate',
        'isChatOpened',
        'processDate',
        'processEstimationDate',
        'processedBy',
        'approvalBy',
        'lastUpdatedBy',
        'status',
        'rejectReason',
        'closedReason'
    ];

    public $sortable = ['priority', 'created_at'];

    public function user() {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function processExecutor() {
        return $this->belongsTo(User::class, 'processedBy');
    }
    
    public function category() {
        return $this->belongsTo(Category::class);
    }

    public function evidences() {
        return $this->hasMany(Evidence::class);
    }

    public function urgentAccess() {
        return $this->hasOne(UrgentAccess::class, 'report_id');
    }
    
}
