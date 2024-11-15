<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
    ];

    public function categories() {
        return $this->hasMany(Category::class);
    }

    public function users() {
        return $this->hasMany(User::class, 'staffType_id');
    }
}
