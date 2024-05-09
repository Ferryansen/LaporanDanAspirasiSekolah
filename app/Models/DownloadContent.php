<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DownloadContent extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'description',
        'file',
        'createdBy',
        'updatedBy',
    ];
}
