<?php

namespace Database\Seeders;

use App\Models\StaffType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StaffTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        StaffType::query()->insert([
            [
                'name' => 'Fasilitas Sekolah'
            ],
            [
                'name' => 'Finance'
            ],
            [
                'name' => 'Lingkungan'
            ],
            [
                'name' => 'Tata tertib'
            ],
        ]);
    }
}
