<?php

namespace Database\Seeders;

use App\Helpers\Collections\Configs\ConfigCollection;
use App\Models\Masters\Config;
use Illuminate\Database\Eloquent\Relations\Relation;
use Illuminate\Database\Seeder;

class ConfigSeeder extends Seeder
{

    /**
     * @var Config|Relation
     * */
    protected $config;

    public function __construct()
    {
        $this->config = new Config();
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ConfigCollection::create(['slug' => \DBTypes::status, 'config_name' => 'Status Data'], [
            ['slug' => \DBTypes::statusActive, 'config_name' => 'Aktif', 'created_at' => currentDate(), 'updated_at' => currentDate()],
            ['slug' => \DBTypes::statusNonactive, 'config_name' => 'Tidak Aktif', 'created_at' => currentDate(), 'updated_at' => currentDate()]
        ]);

        ConfigCollection::create(['slug' => \DBTypes::gender, 'config_name' => 'Jenis Kelamin'], [
            ['slug' => \DBTypes::genderMan, 'config_name' => 'Laki-Laki', 'created_at' => currentDate(), 'updated_at' => currentDate()],
            ['slug' => \DBTypes::genderWoman, 'config_name' => 'Perempuan', 'created_at' => currentDate(), 'updated_at' => currentDate()]
        ]);
    }
}
