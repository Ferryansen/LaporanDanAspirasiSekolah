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
                    'staffTypeId' => 1,
                    'userNo' => 'USR001',
                    'name' => 'John Doe',
                    'email' => 'johndoes@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'admin',
                    'isSuspended' => false,
                    'suspendReason' => ''
                ],
                [
                    'staffTypeId' => 1,
                    'userNo' => 'USR002',
                    'name' => 'John Antonius',
                    'email' => 'johnantonius@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'student',
                    'isSuspended' => false,
                    'suspendReason' => ''
                ],
                [
                    'staffTypeId' => 1,
                    'userNo' => 'USR003',
                    'name' => 'Nelsen aja',
                    'email' => 'nelsen@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'student',
                    'isSuspended' => false,
                    'suspendReason' => ''
                ],
                [
                    'staffTypeId' => 1,
                    'userNo' => 'USR004',
                    'name' => 'Anthony',
                    'email' => 'Anthony@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'student',
                    'isSuspended' => false,
                    'suspendReason' => ''
                ],
                [
                    'staffTypeId' => 1,
                    'userNo' => 'USR005',
                    'name' => 'Montod',
                    'email' => 'Montod@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'student',
                    'isSuspended' => false,
                    'suspendReason' => ''
                ],
                [
                    'staffTypeId' => 2,
                    'userNo' => 'USR006',
                    'name' => 'Kagawa',
                    'email' => 'Kagawa@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'headmaster',
                    'isSuspended' => false,
                    'suspendReason' => ''
                ],
                [
                    'staffTypeId' => 2,
                    'userNo' => 'USR007',
                    'name' => 'Son',
                    'email' => 'Son@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'staff',
                    'isSuspended' => false,
                    'suspendReason' => ''
                ],
                [
                    'staffTypeId' => 1,
                    'userNo' => 'USR008',
                    'name' => 'Kagara',
                    'email' => 'Kagara@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'headmaster',
                    'isSuspended' => false,
                    'suspendReason' => ''
                ],
                [
                    'staffTypeId' => 1,
                    'userNo' => 'USR009',
                    'name' => 'Sonaldo',
                    'email' => 'Sonaldo@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'staff',
                    'isSuspended' => false,
                    'suspendReason' => ''
                ],
                [
                    'staffTypeId' => 3,
                    'userNo' => 'USR010',
                    'name' => 'Kurzawa',
                    'email' => 'Kagawa@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'headmaster',
                    'isSuspended' => false,
                    'suspendReason' => ''
                ],
                [
                    'staffTypeId' => 3,
                    'userNo' => 'USR011',
                    'name' => 'Sonny',
                    'email' => 'Sonny@example.com',
                    'password' => Hash::make('testing123'),
                    'phoneNumber' => '08123457689',
                    'birthDate' => Carbon::create('2000', '01', '01'),
                    'gender' => 'Male',
                    'role' => 'staff',
                    'isSuspended' => false,
                    'suspendReason' => ''
                ],
            ]
        );
}
}
