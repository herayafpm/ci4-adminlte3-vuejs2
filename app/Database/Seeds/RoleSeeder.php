<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use App\Models\RoleModel;

class RoleSeeder extends Seeder
{
	public function run()
	{
		$init_datas = [
			["role_nama" => "admin"],
			["role_nama" => "kasir"],
		];
		$role_model = new RoleModel();
		$role_model->insertBatch($init_datas);
	}
}
