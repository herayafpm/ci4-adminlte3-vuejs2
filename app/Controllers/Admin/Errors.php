<?php

namespace App\Controllers\Admin;

use App\Controllers\Admin\BaseController;

class Errors extends BaseController
{
    public function show404()
    {
        $data['_title'] = '404 Not Found';
        $data['_view'] = 'admin/errors/404';
        $data = array_merge($data, $this->data);
        return view($data['_view'], $data);
    }
}
