<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;

class Dashboard extends BaseController
{
  public function index()
  {
    $data['_title'] = 'Dashboard';
    $data['_view'] = 'admin/dashboard/index';
    $data = array_merge($data, $this->data);
    return view($data['_view'], $data);
  }
}
