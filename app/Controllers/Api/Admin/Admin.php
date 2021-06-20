<?php

namespace App\Controllers\Api\Admin;

class Admin extends BaseResourceController
{

  protected $format       = 'json';
  protected $modelName    = 'App\Models\AdminModel';

  public function datatable_data()
  {
    try {
      $where = ['admin.role_id !=' => 1];
      $like = null;
      if (!empty($this->request->getPost('admin_username'))) {
        $like['admin.admin_username'] = htmlspecialchars(strtolower($this->request->getPost('admin_username')));
      }
      if (!empty($this->request->getPost('admin_nama'))) {
        $like['admin.admin_nama'] = htmlspecialchars(strtoupper($this->request->getPost('admin_nama')));
      }
      $params = ['where' => $where, 'like' => $like];
      return $this->respond($this->datatable($this->model, $params), 200);
    } catch (\Exception $th) {
      return $this->respond(["status" => 0, "message" => $th->getMessage(), "data" => []], 500);
    }
  }
  public function create()
  {
    try {
      $validation =  \Config\Services::validation();
      $rules = [
        'admin_nama' => [
          'label'  => 'Nama',
          'rules'  => 'required',
          'errors' => [
            'required' => '{field} tidak boleh kosong',
          ]
        ],
        'admin_username' => [
          'label'  => 'Username',
          'rules'  => 'required|is_unique[admin.admin_username]',
          'errors' => [
            'required' => '{field} tidak boleh kosong',
            'is_unique' => '{field} sudah digunakan, gunakan yang lain'
          ]
        ],
        'admin_password' => [
          'label'  => 'Password',
          'rules'  => 'required|min_length[6]',
          'errors' => [
            'required' => '{field} tidak boleh kosong',
            'min_length' => '{field} min {param} karakter'
          ]
        ],
      ];
      $validation->setRules($rules);
      $data_post = $this->request->getPost();
      $data = [
        'admin_nama' => htmlspecialchars($data_post['admin_nama']),
        'admin_username' => htmlspecialchars($data_post['admin_username']),
        'admin_aktif' => htmlspecialchars($data_post['admin_aktif']),
        'admin_password' => htmlspecialchars($data_post['admin_password']),
      ];
      if (!$validation->run($data)) {
        return $this->respond(["status" => 0, "message" => "validasi error", "data" => $validation->getErrors()], 200);
      }
      $data['role_id'] = 2;
      $create = $this->model->save($data);
      if ($create) {
        return $this->respond(["status" => 1, "message" => "Berhasil menambahkan admin", "data" => []], 200);
      } else {
        return $this->respond(["status" => 0, "message" => "Gagal menambahkan admin", "data" => []], 200);
      }
    } catch (\Exception $th) {
      return $this->respond(["status" => 0, "message" => $th->getMessage(), "data" => []], 500);
    }
  }
  public function update($admin_id = null)
  {
    try {
      $validation =  \Config\Services::validation();
      $rules = [
        'admin_nama' => [
          'label'  => 'Nama',
          'rules'  => 'required',
          'errors' => [
            'required' => '{field} tidak boleh kosong',
          ]
        ],
        'admin_username' => [
          'label'  => 'Username',
          'rules'  => "required|is_unique[admin.admin_username,admin_id,{$admin_id}]",
          'errors' => [
            'required' => '{field} tidak boleh kosong',
            'is_unique' => '{field} sudah digunakan, gunakan yang lain'
          ]
        ],
        'admin_password' => [
          'label'  => 'Password',
          'rules'  => 'update_pass[6]',
          'errors' => [
            'update_pass' => '{field} minimal {param} karakter',
          ]
        ],
      ];
      $validation->setRules($rules);
      $data_post = $this->request->getPost();
      $data = [
        'admin_nama' => htmlspecialchars($data_post['admin_nama']),
        'admin_username' => htmlspecialchars($data_post['admin_username']),
        'admin_aktif' => htmlspecialchars($data_post['admin_aktif']),
        'admin_password' => htmlspecialchars($data_post['admin_password']),
      ];
      if (!$validation->run($data)) {
        return $this->respond(["status" => 0, "message" => "validasi error", "data" => $validation->getErrors()], 200);
      }
      if (empty($data['admin_password'])) {
        unset($data['admin_password']);
      }
      $update = $this->model->update($admin_id, $data);
      if ($update) {
        return $this->respond(["status" => 1, "message" => "Berhasil mengubah data admin", "data" => []], 200);
      } else {
        return $this->respond(["status" => 0, "message" => "Gagal mengubah data admin", "data" => []], 200);
      }
    } catch (\Exception $th) {
      return $this->respond(["status" => 0, "message" => $th->getMessage(), "data" => []], 500);
    }
  }
  public function delete($admin_id = null)
  {
    try {
      $admin = $this->model->find($admin_id);
      if (!$admin) {
        return $this->respond(["status" => 0, "message" => "admin tidak ditemukan", "data" => []], 200);
      }
      if ($admin['role_id'] == 1) {
        return $this->respond(["status" => 0, "message" => "admin tidak bisa dihapus", "data" => []], 200);
      }
      if ($this->model->delete($admin_id)) {
        return $this->respond(["status" => 1, "message" => "Admin berhasil dihapus", "data" => []], 200);
      } else {
        return $this->respond(["status" => 0, "message" => "Gagal menghapus admin", "data" => []], 200);
      }
    } catch (\Exception $th) {
      return $this->respond(["status" => 0, "message" => $th->getMessage(), "data" => []], 500);
    }
  }
}
