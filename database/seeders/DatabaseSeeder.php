<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\role;

use App\Http\Controllers\api_support;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $as = new api_support();

        $role_name = [
            'admin',
            'pengajar',
            'siswa'
        ];

        $role_deskripsi = [
            'Administrator',
            'Pengajar / Guru',
            'Siswa / Murid'
        ];

        foreach ($role_name as $key => $value) {
            role::create([
                'nama_role' => $role_name[$key],
                'deskripsi_role' => $role_deskripsi[$key]
            ]);
        }

        User::create([
            'email'         => 'haganeizzy@gmail.com ',
            'password'      => $as->my_encrypt('12345678'),

            'nama_lengkap'  => 'Administrator',
            'jenis_kelamin' => 'L',
            'tanggal_lahir' => '2000-01-01',
            'sekolah'       => '-',
            'kelas'         => '-',
            'role_id'       => 1
        ]);
    }
}
