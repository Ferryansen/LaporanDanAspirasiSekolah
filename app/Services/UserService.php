<?php

namespace App\Services;

use Illuminate\Support\Carbon;
use App\Models\User;
use DateTime;

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

    public function generateUserNoByExcelFormat($DOB)
    {
        $dateOfBirth = Carbon::createFromFormat('Ymd', $DOB);
        return $this->generateUserNo($dateOfBirth);
    }

    public function generateUserNoByFormFormat($DOB)
    {
        $dateOfBirth = Carbon::createFromFormat('d/m/Y', $DOB)->format('Ymd');
        return $this->generateUserNo($dateOfBirth);
    }
}