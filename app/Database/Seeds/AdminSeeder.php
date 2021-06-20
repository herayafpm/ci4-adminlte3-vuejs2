<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\AdminModel;

class AdminSeeder extends Seeder
{
    public function run()
    {
        $password = password_hash("123456", PASSWORD_DEFAULT);
        $init_datas = [
            [
                "admin_username" => strtolower(trim("admin")),
                "admin_nama" => "admin",
                "role_id"         => 1,
                "admin_aktif"         => 1,
                "admin_password"   => $password,
            ],
            [
                "admin_username" => strtolower(trim("kasir")),
                "admin_nama" => "kasir",
                "role_id"         => 2,
                "admin_aktif"         => 1,
                "admin_password"   => $password,
            ],
        ];
        $admin_model = new AdminModel();
        $admin_model->insertBatch($init_datas);
    }
}
