<?php

namespace App\Controllers\Api\Auth;

use CodeIgniter\RESTful\ResourceController;

class Login extends ResourceController
{

  protected $format       = 'json';
  protected $modelName    = 'App\Models\AdminModel';

  protected $session;
  public function __construct()
  {
    $this->session = \Config\Services::session();
  }

  public function process()
  {
    try {
      $validation =  \Config\Services::validation();
      $rules = [
        'username' => [
          'label'  => 'Username',
          'rules'  => 'required',
          'errors' => [
            'required' => '{field} tidak boleh kosong',
          ]
        ],
        'password' => [
          'label'  => 'Password',
          'rules'  => 'required',
          'errors' => [
            'required' => '{field} tidak boleh kosong',
          ]
        ],
      ];
      $validation->setRules($rules);
      $dataPost = $this->request->getPost();
      $data = [
        'username' => htmlspecialchars($dataPost['username']),
        'password' => htmlspecialchars($dataPost['password']),
      ];
      if (!$validation->run($data)) {
        return $this->respond(["status" => 0, "message" => "validasi error", "data" => $validation->getErrors()], 200);
      }
      $auth = $this->model->authenticate($data['username'], $data['password']);
      if ($auth) {
        if (!$auth['is_aktif']) {
          return $this->respond(["status" => 0, "message" => "Akun tidak aktif, silahkan kontak admin", "data" => []], 200);
        }
        $auth['is_login'] = true;
        $this->session->set($auth);
        return $this->respond(["status" => 1, "message" => "Berhasil masuk", "data" => ['url' => base_url('admin/dashboard')]], 200);
      } else {
        $this->session->setFlashdata('error', 'Username atau password salah');
        return $this->respond(["status" => 0, "message" => "username atau password salah", "data" => []], 200);
      }
    } catch (\Exception $th) {
      return $this->respond(["status" => 0, "message" => $th->getMessage(), "data" => []], 500);
    }
  }
}
