<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\User;

class UserService
{
    public function generateUserNo($dateOfBirth)
    {
        $dayOfBirth = Carbon::parse($dateOfBirth)->format('d');
        $currentYear = Carbon::now()->format('y');

        $lastUserNo = User::whereYear('created_at', Carbon::now()->year)->latest('userNo')->value('userNo');
        $lastIncremental = $lastUserNo ? (int)substr($lastUserNo, -5) : 0;
        $nextIncremental = $lastIncremental + 1;

        $userNo = "USR{$dayOfBirth}{$currentYear}" . sprintf('%05d', $nextIncremental);

        return $userNo;
    }
}