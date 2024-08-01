<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'userNo',
        'staffType_id',
        'name',
        'email',
        'password',
        'phoneNumber',
        'urgentPhoneNumber',
        'gender',
        'birthDate',
        'role',
        'isSuspended',
        'suspendReason',
        'suspendDate',
    ];
    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function staffType() {
        return $this->belongsTo(StaffType::class);
    }

    public function aspirations() {
        return $this->hasMany(Aspiration::class);
    }

    public function reports() {
        return $this->hasMany(Report::class);
    }

    public function aspirations_upvote() {
        return $this->belongsToMany(Aspiration::class, 'userUpvotesAspiration', 'user_id', 'aspirationId');
    }

    public function user_report_aspirations() {
        return $this->hasMany(UserReportAspiration::class);
    }
  
    public function reportedAspirations()
    {
        return $this->belongsToMany(Aspiration::class, 'userReportAspirations', 'user_id', 'aspirationId')->withPivot('reportReason');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function aspirationReactions()
    {
        return $this->hasMany(AspirationReaction::class);
    }

    public function consultationEvents()
    {
        return $this->hasMany(ConsultationEvent::class, 'consultant');
    }
}
