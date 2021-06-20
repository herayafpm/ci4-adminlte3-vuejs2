<?php

namespace App\Controllers\Auth;

use App\Controllers\BaseController;

class Login extends BaseController
{
  public function index()
  {
    if ($this->session->is_login) {
      return redirect()->to(base_url('admin/dashboard'));
    }
    $config = config('App');
    $data['_session'] = $this->session;
    $data['_title'] = 'Masuk Aplikasi';
    $data['_meta_description'] = 'Login ' . $config->appName;
    $data['_meta_keywords'] = 'Auth,Login,App,' . $config->appName;
    $data['_view'] = 'auth/login';
    return view($data['_view'], $data);
  }
}
