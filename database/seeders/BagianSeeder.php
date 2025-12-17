<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Bagian;
use App\Models\Section;
use Illuminate\Support\Facades\DB;

class BagianSeeder extends Seeder
{
    public function run()
    {
        $bagian = [
            ['name' => 'IT Department', 'status' => 'aktif'],
            ['name' => 'HR Department', 'status' => 'aktif'],
            ['name' => 'Finance Department', 'status' => 'aktif'],
            ['name' => 'Marketing Department', 'status' => 'aktif'],
            ['name' => 'Sales Department', 'status' => 'aktif'],
            ['name' => 'Operations Department', 'status' => 'aktif'],
            ['name' => 'Production Department', 'status' => 'aktif'],
            ['name' => 'Logistics Department', 'status' => 'aktif'],
            ['name' => 'Customer Service', 'status' => 'aktif'],
            ['name' => 'Research & Development', 'status' => 'aktif'],
        ];

        foreach ($bagian as $data) {
            Section::create($data);
        }

        $this->command->info('10 data bagian berhasil ditambahkan!');
    }
}
