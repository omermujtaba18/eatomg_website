<?php

namespace App\Controllers;

use App\Models\CustomerModel;
use CodeIgniter\Controller;

class Pages extends Controller
{
    public function index()
    {
        return view('index');
    }

    public function view($page = 'index')
    {
        if (!is_file(APPPATH . '/Views/pages/' . $page . '.php')) {
            // Whoops, we don't have a page for that!
            throw new \CodeIgniter\Exceptions\PageNotFoundException($page);
        }

        $data['title'] = strtolower($page); // Capitalize the first letter
        $data['header'] = "header-transparent header-layout1";

        $session = session();
        $data['cus_id'] = $session->has('cus_id') ? $session->cus_id : NULL;
        echo view('templates/header', $data);
        echo view('pages/' . $page, $data);
        echo view('templates/footer', $data);
    }
}
