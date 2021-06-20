<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;

class Admin extends BaseController
{
    public function index()
    {
        $data['_title'] = 'Admin';
        $data['_view'] = 'admin/admin/index';
        $data['_uri_datatable'] = base_url('/api/admin/admin/datatable');
        $data['_scroll_datatable'] = '300px';
        $data = array_merge($data, $this->data);
        return view($data['_view'], $data);
    }
}
