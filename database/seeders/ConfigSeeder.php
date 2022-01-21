<?php

namespace Database\Seeders;

use App\Helpers\Collections\Achievements\TasksCollectPlasticPayload;
use App\Helpers\Collections\Achievements\TasksCreateRequestPayload;
use App\Helpers\Collections\Achievements\TasksRegisterPayload;
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

        ConfigCollection::create(['slug' => \DBTypes::role, 'config_name' => 'Role'], [
            ['slug' => \DBTypes::roleSuperuser, 'config_name' => 'Superuser', 'created_at' => currentDate(), 'updated_at' => currentDate()],
            ['slug' => \DBTypes::roleAdministrator, 'config_name' => 'Administrator', 'created_at' => currentDate(), 'updated_at' => currentDate()],
            ['slug' => \DBTypes::roleCustomer, 'config_name' => 'Pelanggan', 'created_at' => currentDate(), 'updated_at' => currentDate()],
            ['slug' => \DBTypes::roleStaff, 'config_name' => 'Staff', 'created_at' => currentDate(), 'updated_at' => currentDate()],
        ]);

        ConfigCollection::create(['slug' => \DBTypes::tasks, 'config_name' => 'Jenis Taks Achievement'], [
            ['slug' => \DBTypes::tasksCollectPlastic, 'config_name' => 'Mengumpulkan Plastik', 'payload' => (new TasksCollectPlasticPayload())->payload(), 'created_at' => currentDate(), 'updated_at' => currentDate()],
            ['slug' => \DBTypes::tasksCreatePickup, 'config_name' => 'Membuat Request Pickup', 'payload' => (new TasksCreateRequestPayload())->payload(), 'created_at' => currentDate(), 'updated_at' => currentDate()],
            ['slug' => \DBTypes::tasksRegister, 'config_name' => 'Melakukan Pendaftaran', 'payload' => (new TasksRegisterPayload())->payload(), 'created_at' => currentDate(), 'updated_at' => currentDate()],
        ]);

        ConfigCollection::create(['slug' => \DBTypes::statusOrder, 'config_name' => 'Status Pesanan'], [
           ['slug' => \DBTypes::statusOrderNew, 'config_name' => 'Pesanan Baru', 'created_at' => currentDate(), 'updated_at' => currentDate()],
           ['slug' => \DBTypes::statusOrderInPickup, 'config_name' => 'Proses Pengambilan', 'created_at' => currentDate(), 'updated_at' => currentDate()],
           ['slug' => \DBTypes::statusOrderDone, 'config_name' => 'Selesai', 'created_at' => currentDate(), 'updated_at' => currentDate()],
        ]);

        ConfigCollection::create(['slug' => \DBTypes::rubbishCategory, 'config_name' => 'Kategori Sampah'], [
            ['config_name' => 'Plastik Makanan', 'created_at' => currentDate(), 'updated_at' => currentDate()],
            ['config_name' => 'Plastik Peralatan Makan', 'created_at' => currentDate(), 'updated_at' => currentDate()],
            ['config_name' => 'Plastik Alat Elektronik', 'created_at' => currentDate(), 'updated_at' => currentDate()],
            ['config_name' => 'Plastik Lemari', 'created_at' => currentDate(), 'updated_at' => currentDate()],
        ]);
    }
}
