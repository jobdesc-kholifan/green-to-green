<?php

namespace Database\Seeders;

use App\Helpers\Collections\Users\UserCollection;
use App\Models\Masters\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{

    protected $user;

    public function __construct()
    {
        $this->user = new User();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     * @throws \Exception
     */
    public function run()
    {
        $config = findConfig()->in([\DBTypes::statusActive, \DBTypes::genderMan, \DBTypes::genderWoman, \DBTypes::roleSuperuser]);

        UserCollection::create([
            'full_name' => 'Superuser',
            'gender_id' => $config->get(\DBTypes::genderMan)->getId(),
            'pob' => 'Website',
            'dob' => currentDate('Y-m-d'),
            'address' => url('/'),
            'email' => 'superuser@green-to-green.buatkerja.com',
            'phone_number' => '1234567890',
            'user_name' => 'superuser',
            'user_password' => Hash::make('d3v4pp$123'),
            'role_id' => $config->get(\DBTypes::roleSuperuser)->getId(),
            'status_id' => $config->get(\DBTypes::statusActive)->getId(),
        ]);
    }
}
