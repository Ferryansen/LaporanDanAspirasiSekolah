<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::query()->insert([[
                    'staffType_id' => null,
                    'userNo' => 'USR000001',
                    'name' => 'John Doe',
                    'email' => 'johndoes@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'admin',
                    'isSuspended' => false,
                    'suspendReason' => '',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'staffType_id' => null,
                    'userNo' => 'USR000002',
                    'name' => 'John Antonius McStaffin',
                    'email' => 'staff@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'student',
                    'isSuspended' => false,
                    'suspendReason' => '',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'staffType_id' => null,
                    'userNo' => 'USR000003',
                    'name' => 'Nelsen aja',
                    'email' => 'nelsen@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'student',
                    'isSuspended' => false,
                    'suspendReason' => '',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'staffType_id' => null,
                    'userNo' => 'USR000004',
                    'name' => 'Anthony',
                    'email' => 'Anthony@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'student',
                    'isSuspended' => false,
                    'suspendReason' => '',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'staffType_id' => null,
                    'userNo' => 'USR000005',
                    'name' => 'Montod',
                    'email' => 'Montod@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'student',
                    'isSuspended' => false,
                    'suspendReason' => '',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'staffType_id' => null,
                    'userNo' => 'USR000006',
                    'name' => 'Kagawa',
                    'email' => 'Kagawa@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'headmaster',
                    'isSuspended' => false,
                    'suspendReason' => '',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'staffType_id' => 2,
                    'userNo' => 'USR000007',
                    'name' => 'Son',
                    'email' => 'Son@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'staff',
                    'isSuspended' => false,
                    'suspendReason' => '',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'staffType_id' => 2,
                    'userNo' => 'USR000008',
                    'name' => 'Sonny',
                    'email' => 'Sonny@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'staff',
                    'isSuspended' => false,
                    'suspendReason' => '',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'staffType_id' => null,
                    'userNo' => 'USR000009',
                    'name' => 'Kagara',
                    'email' => 'Kagara@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'headmaster',
                    'isSuspended' => false,
                    'suspendReason' => '',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'staffType_id' => 1,
                    'userNo' => 'USR000010',
                    'name' => 'Sonaldo',
                    'email' => 'Sonaldo@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'staff',
                    'isSuspended' => false,
                    'suspendReason' => '',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'staffType_id' => null,
                    'userNo' => 'USR000011',
                    'name' => 'Kurzawa',
                    'email' => 'Kagawa@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'headmaster',
                    'isSuspended' => false,
                    'suspendReason' => '',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
                [
                    'staffType_id' => 3,
                    'userNo' => 'USR000012',
                    'name' => 'Sonny',
                    'email' => 'Sonny@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'staff',
                    'isSuspended' => false,
                    'suspendReason' => '',
                    'created_at' => \Carbon\Carbon::now(),
                    'updated_at' => \Carbon\Carbon::now()
                ],
            ]
        );
}
}
