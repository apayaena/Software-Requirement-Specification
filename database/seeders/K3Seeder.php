<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\Location;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class K3Seeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. Seed Departments
        $depts = [
            ['department_name' => 'Health, Safety, and Environment', 'dept_code' => 'HSE'],
            ['department_name' => 'Mining Operations', 'dept_code' => 'MIN'],
            ['department_name' => 'Engineering & Infrastructure', 'dept_code' => 'ENG'],
            ['department_name' => 'Human Resources & Development', 'dept_code' => 'HRD'],
            ['department_name' => 'Logistics & Supply Chain', 'dept_code' => 'LOG'],
        ];

        foreach ($depts as $dept) {
            Department::firstOrCreate(['dept_code' => $dept['dept_code']], $dept);
        }

        // Fetch HSE and Mining departments for user seeding
        $hseDept = Department::where('dept_code', 'HSE')->first();
        $minDept = Department::where('dept_code', 'MIN')->first();

        // 2. Seed Locations
        $locations = [
            [
                'site_name' => 'Pit A West',
                'area_type' => 'Pit',
                'latitude' => -3.456789,
                'longitude' => 114.56789,
            ],
            [
                'site_name' => 'Pit B East',
                'area_type' => 'Pit',
                'latitude' => -3.457123,
                'longitude' => 114.568123,
            ],
            [
                'site_name' => 'Hauling Road KM 12',
                'area_type' => 'Hauling',
                'latitude' => -3.461234,
                'longitude' => 114.571234,
            ],
            [
                'site_name' => 'Main Workshop',
                'area_type' => 'Workshop',
                'latitude' => -3.452345,
                'longitude' => 114.562345,
            ],
            [
                'site_name' => 'Disposal Area South',
                'area_type' => 'Disposal',
                'latitude' => -3.468901,
                'longitude' => 114.578901,
            ],
            [
                'site_name' => 'HSE Head Office',
                'area_type' => 'Office',
                'latitude' => -3.450123,
                'longitude' => 114.560123,
            ],
        ];

        foreach ($locations as $loc) {
            Location::firstOrCreate(['site_name' => $loc['site_name']], $loc);
        }

        // 3. Seed Users (with hashed password: 'password123')
        $users = [
            [
                'name' => 'HSE Manager',
                'email' => 'manager.hse@safemine.com',
                'password' => Hash::make('password123'),
                'phone_number' => '628123456789',
                'department_id' => $hseDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'HSE Officer',
                'email' => 'officer.hse@safemine.com',
                'password' => Hash::make('password123'),
                'phone_number' => '628234567890',
                'department_id' => $hseDept->id,
                'is_active' => true,
            ],
            [
                'name' => 'Pekerja Lapangan Budi',
                'email' => 'pekerja.lapangan@safemine.com',
                'password' => Hash::make('password123'),
                'phone_number' => '628345678901',
                'department_id' => $minDept->id,
                'is_active' => true,
            ],
        ];

        foreach ($users as $userData) {
            User::firstOrCreate(['email' => $userData['email']], $userData);
        }
    }
}
