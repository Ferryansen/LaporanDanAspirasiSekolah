<?php

namespace App\Imports;

use App\Models\User;
use App\Services\UserService;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ImportUser implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $rules = [
            'nama' => 'required|max:250',
            'email' => 'required|email|unique:users',
            'nomor_telepon' => 'required|numeric|digits_between:10,12|regex:/^08[0-9]+$/',
            'gender' => 'required|in:L,P',
            'tanggal_lahir' => 'required|date|before:today|after:1900-01-01',
        ];

        $validator = Validator::make($row, $rules);

        if ($validator->fails()) {
            return null;
        }

        $birthDate = Carbon::parse($row['tanggal_lahir'])->format('Ymd');

        $credentials = [
            'name' => $row['nama'],
            'email' => $row['email'],
            'phoneNumber' => $row['nomor_telepon'],
            'birthDate' => $birthDate,
            'isSuspended' => false,
            'staffType_id' => null,
            'role' => "student",
        ];

        $formattedDate = date('Ymd', strtotime($row['tanggal_lahir']));
        $freshPassword = "D3f@ult" . $formattedDate;
        $credentials['password'] = Hash::make($freshPassword);

        if ($row['gender'] == 'L') {
            $credentials['gender'] = "Male";
        } else {
            $credentials['gender'] = "Female";
        }

        $userService = new UserService;
        $user_no = $userService->generateUserNo($birthDate);
        $credentials['userNo'] = $user_no;

        return new User($credentials);
    }
}
