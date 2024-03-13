<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;

    protected $fillable = [
        'staffType_id',
        'name'
    ];
    
    public function aspirations() {
        return $this->hasMany(Aspiration::class);
    }

    public function reports() {
        return $this->hasMany(Report::class);
    }

    public function staffType() {
        return $this->belongsTo(StaffType::class);
    }
}
